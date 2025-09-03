<?php

use App\Models\Volunteer;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $sortBy = 'applied_at';
    public string $sortDirection = 'desc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updateStatus($volunteerId, $newStatus)
    {
        $volunteer = Volunteer::findOrFail($volunteerId);
        $volunteer->update(['status' => $newStatus]);
        
        $this->dispatch('volunteer-updated');
    }

    public function deleteVolunteer($volunteerId)
    {
        Volunteer::findOrFail($volunteerId)->delete();
        
        $this->dispatch('volunteer-deleted');
    }

    public function with()
    {
        $query = Volunteer::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('experience', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $volunteers = $query->orderBy($this->sortBy, $this->sortDirection)
                           ->paginate(15);

        $stats = [
            'total' => Volunteer::count(),
            'pending' => Volunteer::where('status', 'pending')->count(),
            'approved' => Volunteer::where('status', 'approved')->count(),
            'active' => Volunteer::where('status', 'active')->count(),
            'inactive' => Volunteer::where('status', 'inactive')->count(),
        ];

        return compact('volunteers', 'stats');
    }
}; ?>

<div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Volunteer Management</h1>
                    <p class="text-gray-600 dark:text-gray-400">Manage volunteer applications and assignments</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <flux:icon.user-group class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Volunteers</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <flux:icon.clock class="w-5 h-5 text-yellow-600 dark:text-yellow-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                        <flux:icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Approved</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['approved'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <flux:icon.heart class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['active'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-gray-100 dark:bg-gray-900 rounded-lg">
                        <flux:icon.pause class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Inactive</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['inactive'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <flux:field>
                        <flux:label>Search Volunteers</flux:label>
                        <flux:input 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Search by name, email, phone, or experience..."
                        />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Filter by Status</flux:label>
                        <flux:select wire:model.live="status">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending Review</option>
                            <option value="approved">Approved</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </flux:select>
                    </flux:field>
                </div>

                <div class="flex items-end">
                    <flux:button wire:click="$set('search', '')" variant="ghost" class="w-full">
                        <flux:icon.x-mark class="w-4 h-4 mr-2" />
                        Clear Filters
                    </flux:button>
                </div>
            </div>
        </div>

        <!-- Volunteers Table -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-zinc-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <button wire:click="sortBy('name')" class="flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                    Volunteer
                                    @if($sortBy === 'name')
                                        <flux:icon.chevron-up class="w-4 h-4 ml-1 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" />
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Areas of Interest
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <button wire:click="sortBy('applied_at')" class="flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                    Applied
                                    @if($sortBy === 'applied_at')
                                        <flux:icon.chevron-up class="w-4 h-4 ml-1 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" />
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse($volunteers as $volunteer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $volunteer->name }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $volunteer->email }}</div>
                                        @if($volunteer->phone)
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $volunteer->phone }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($volunteer->areas_of_interest)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($volunteer->areas_of_interest as $area)
                                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                                    {{ $area }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">No areas specified</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColor = match($volunteer->status) {
                                            'pending' => 'text-yellow-600 bg-yellow-100 dark:text-yellow-400 dark:bg-yellow-900',
                                            'approved' => 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900',
                                            'active' => 'text-purple-600 bg-purple-100 dark:text-purple-400 dark:bg-purple-900',
                                            'inactive' => 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-900',
                                            default => 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-900',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                        {{ ucfirst($volunteer->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $volunteer->applied_at->format('M j, Y') }}
                                    <div class="text-xs">{{ $volunteer->applied_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <flux:button 
                                            href="{{ route('admin.volunteers.show', $volunteer) }}" 
                                            variant="ghost" 
                                            size="sm"
                                        >
                                            <flux:icon.eye class="w-4 h-4" />
                                        </flux:button>
                                        
                                        <flux:dropdown>
                                            <flux:button variant="ghost" size="sm">
                                                <flux:icon.ellipsis-horizontal class="w-4 h-4" />
                                            </flux:button>
                                            <flux:menu>
                                                @if($volunteer->status !== 'approved')
                                                    <flux:menu.item wire:click="updateStatus({{ $volunteer->id }}, 'approved')">
                                                        <flux:icon.check-circle class="w-4 h-4 mr-2" />
                                                        Approve Application
                                                    </flux:menu.item>
                                                @endif
                                                @if($volunteer->status !== 'active')
                                                    <flux:menu.item wire:click="updateStatus({{ $volunteer->id }}, 'active')">
                                                        <flux:icon.heart class="w-4 h-4 mr-2" />
                                                        Mark as Active
                                                    </flux:menu.item>
                                                @endif
                                                @if($volunteer->status !== 'inactive')
                                                    <flux:menu.item wire:click="updateStatus({{ $volunteer->id }}, 'inactive')">
                                                        <flux:icon.pause class="w-4 h-4 mr-2" />
                                                        Mark as Inactive
                                                    </flux:menu.item>
                                                @endif
                                                @if($volunteer->status !== 'pending')
                                                    <flux:menu.item wire:click="updateStatus({{ $volunteer->id }}, 'pending')">
                                                        <flux:icon.clock class="w-4 h-4 mr-2" />
                                                        Mark as Pending
                                                    </flux:menu.item>
                                                @endif
                                                <flux:menu.separator />
                                                <flux:menu.item 
                                                    wire:click="deleteVolunteer({{ $volunteer->id }})" 
                                                    wire:confirm="Are you sure you want to delete {{ $volunteer->name }}'s application? This action cannot be undone."
                                                >
                                                    <flux:icon.trash class="w-4 h-4 mr-2" />
                                                    Delete Application
                                                </flux:menu.item>
                                            </flux:menu>
                                        </flux:dropdown>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <flux:icon.user-group class="w-12 h-12 mx-auto mb-4" />
                                        <h3 class="text-lg font-medium mb-2">No volunteer applications found</h3>
                                        <p class="text-sm">
                                            @if($search || $status)
                                                Try adjusting your search criteria or filters.
                                            @else
                                                Volunteer applications will appear here when people submit the volunteer form.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($volunteers->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
                    {{ $volunteers->links() }}
                </div>
            @endif
        </div>
</div>