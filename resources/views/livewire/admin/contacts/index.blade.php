<?php

use App\Models\Contact;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $sortBy = 'created_at';
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

    public function markAsRead($contactId)
    {
        $contact = Contact::findOrFail($contactId);
        $contact->markAsRead();
        
        session()->flash('message', 'Contact message marked as read.');
        $this->dispatch('contact-updated');
    }

    public function markAsReplied($contactId)
    {
        $contact = Contact::findOrFail($contactId);
        $contact->markAsReplied();
        
        session()->flash('message', 'Contact message marked as replied.');
        $this->dispatch('contact-updated');
    }

    public function deleteContact($contactId)
    {
        Contact::findOrFail($contactId)->delete();
        
        // Reset pagination if we're on a page that no longer has items
        $this->resetPage();
        
        $this->dispatch('contact-deleted');
        
        // Add a success message
        session()->flash('message', 'Contact message deleted successfully.');
    }

    public function with()
    {
        $query = Contact::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('subject', 'like', '%' . $this->search . '%')
                  ->orWhere('message', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $contacts = $query->orderBy($this->sortBy, $this->sortDirection)
                         ->paginate(15);

        $stats = [
            'total' => Contact::count(),
            'new' => Contact::where('status', 'new')->count(),
            'read' => Contact::where('status', 'read')->count(),
            'replied' => Contact::where('status', 'replied')->count(),
        ];

        return compact('contacts', 'stats');
    }
    
    public function toJSON()
    {
        return json_encode([
            'component' => 'admin.contacts.index',
            'id' => $this->getId()
        ]);
    }
}; ?>

<div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Contact Messages</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage and respond to visitor inquiries</p>
        </div>

        <!-- Success Message -->
        @if(session('message'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <flux:icon.envelope class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Messages</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                        <flux:icon.exclamation-circle class="w-5 h-5 text-red-600 dark:text-red-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">New Messages</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['new'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <flux:icon.eye class="w-5 h-5 text-yellow-600 dark:text-yellow-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Read Messages</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['read'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                        <flux:icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Replied Messages</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['replied'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <flux:field>
                        <flux:label>Search Messages</flux:label>
                        <flux:input 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Search by name, email, subject, or message..."
                        />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Filter by Status</flux:label>
                        <flux:select wire:model.live="status">
                            <option value="">All Statuses</option>
                            <option value="new">New</option>
                            <option value="read">Read</option>
                            <option value="replied">Replied</option>
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

        <!-- Messages Table -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-zinc-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <button wire:click="sortBy('name')" class="flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                    Contact
                                    @if($sortBy === 'name')
                                        <flux:icon.chevron-up class="w-4 h-4 ml-1 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" />
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <button wire:click="sortBy('subject')" class="flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                    Subject
                                    @if($sortBy === 'subject')
                                        <flux:icon.chevron-up class="w-4 h-4 ml-1 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" />
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <button wire:click="sortBy('created_at')" class="flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                    Date
                                    @if($sortBy === 'created_at')
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
                        @forelse($contacts as $contact)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $contact->name }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $contact->email }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-900 dark:text-white">{{ $contact->subject }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-xs">
                                        {{ Str::limit($contact->message, 60) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColor = match($contact->status) {
                                            'new' => 'text-red-600 bg-red-100 dark:text-red-400 dark:bg-red-900',
                                            'read' => 'text-yellow-600 bg-yellow-100 dark:text-yellow-400 dark:bg-yellow-900',
                                            'replied' => 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900',
                                            default => 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-900',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                        {{ ucfirst($contact->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $contact->created_at->format('M j, Y') }}
                                    <div class="text-xs">{{ $contact->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <flux:button 
                                            href="{{ route('admin.contacts.show', $contact) }}" 
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
                                                @if($contact->status !== 'read')
                                                    <flux:menu.item wire:click="markAsRead({{ $contact->id }})">
                                                        <flux:icon.eye class="w-4 h-4 mr-2" />
                                                        Mark as Read
                                                    </flux:menu.item>
                                                @endif
                                                @if($contact->status !== 'replied')
                                                    <flux:menu.item wire:click="markAsReplied({{ $contact->id }})">
                                                        <flux:icon.check class="w-4 h-4 mr-2" />
                                                        Mark as Replied
                                                    </flux:menu.item>
                                                @endif
                                                <flux:menu.separator />
                                                <flux:menu.item 
                                                    wire:click="deleteContact({{ $contact->id }})" 
                                                    wire:confirm="Are you sure you want to delete this contact message?"
                                                >
                                                    <flux:icon.trash class="w-4 h-4 mr-2" />
                                                    Delete
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
                                        <flux:icon.inbox class="w-12 h-12 mx-auto mb-4" />
                                        <h3 class="text-lg font-medium mb-2">No contact messages found</h3>
                                        <p class="text-sm">
                                            @if($search || $status)
                                                Try adjusting your search criteria or filters.
                                            @else
                                                Contact messages will appear here when visitors submit the contact form.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($contacts->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
                    {{ $contacts->links() }}
                </div>
            @endif
            </div>
        </div>
</div>