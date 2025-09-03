<?php

use App\Models\Volunteer;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    public Volunteer $volunteer;

    public function mount(Volunteer $volunteer)
    {
        $this->volunteer = $volunteer;
    }

    public function updateStatus($newStatus)
    {
        $this->volunteer->update(['status' => $newStatus]);
        session()->flash('success', 'Volunteer status updated successfully!');
    }

    public function deleteVolunteer()
    {
        $this->volunteer->delete();
        return redirect()->route('admin.volunteers.index')
                        ->with('success', 'Volunteer application deleted successfully.');
    }

    public function getStatusColorProperty()
    {
        return match($this->volunteer->status) {
            'pending' => 'text-yellow-600 bg-yellow-100 dark:text-yellow-400 dark:bg-yellow-900',
            'approved' => 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900',
            'active' => 'text-purple-600 bg-purple-100 dark:text-purple-400 dark:bg-purple-900',
            'inactive' => 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-900',
            default => 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-900',
        };
    }
}; ?>

<div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $volunteer->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">Volunteer Application</p>
                </div>
                <div class="flex items-center space-x-3">
                    <flux:dropdown>
                        <flux:button variant="primary" size="sm">
                            <flux:icon.cog class="w-4 h-4 mr-2" />
                            Update Status
                        </flux:button>
                        <flux:menu>
                            @if($volunteer->status !== 'approved')
                                <flux:menu.item wire:click="updateStatus('approved')">
                                    <flux:icon.check-circle class="w-4 h-4 mr-2" />
                                    Approve Application
                                </flux:menu.item>
                            @endif
                            @if($volunteer->status !== 'active')
                                <flux:menu.item wire:click="updateStatus('active')">
                                    <flux:icon.heart class="w-4 h-4 mr-2" />
                                    Mark as Active
                                </flux:menu.item>
                            @endif
                            @if($volunteer->status !== 'inactive')
                                <flux:menu.item wire:click="updateStatus('inactive')">
                                    <flux:icon.pause class="w-4 h-4 mr-2" />
                                    Mark as Inactive
                                </flux:menu.item>
                            @endif
                            @if($volunteer->status !== 'pending')
                                <flux:menu.item wire:click="updateStatus('pending')">
                                    <flux:icon.clock class="w-4 h-4 mr-2" />
                                    Mark as Pending
                                </flux:menu.item>
                            @endif
                            <flux:menu.separator />
                            <flux:menu.item 
                                wire:click="deleteVolunteer" 
                                wire:confirm="Are you sure you want to delete {{ $volunteer->name }}'s application? This action cannot be undone."
                            >
                                <flux:icon.trash class="w-4 h-4 mr-2" />
                                Delete Application
                            </flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>
        </div>

        <!-- Back Link -->
        <div class="mb-6">
            <flux:button href="{{ route('admin.volunteers.index') }}" variant="ghost" size="sm">
                <flux:icon.arrow-left class="w-4 h-4 mr-2" />
                Back to Volunteers
            </flux:button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Contact Information -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Full Name</label>
                            <p class="text-gray-900 dark:text-white">{{ $volunteer->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Email Address</label>
                            <p class="text-gray-900 dark:text-white">
                                <a href="mailto:{{ $volunteer->email }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $volunteer->email }}
                                </a>
                            </p>
                        </div>

                        @if($volunteer->phone)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Phone Number</label>
                                <p class="text-gray-900 dark:text-white">
                                    <a href="tel:{{ $volunteer->phone }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $volunteer->phone }}
                                    </a>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Areas of Interest -->
                @if($volunteer->areas_of_interest)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Areas of Interest</h2>
                        
                        <div class="flex flex-wrap gap-2">
                            @if(is_array($volunteer->areas_of_interest))
                                @foreach($volunteer->areas_of_interest as $area)
                                    <span class="px-3 py-2 text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-lg">
                                        {{ $area }}
                                    </span>
                                @endforeach
                            @else
                                <span class="px-3 py-2 text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-lg">
                                    {{ $volunteer->areas_of_interest }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Availability -->
                @if($volunteer->availability)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Availability</h2>
                        
                        <div class="space-y-3">
                            @foreach($volunteer->availability as $day => $times)
                                @if($times && is_array($times) && count($times) > 0)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-zinc-700 rounded-lg">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($day) }}</span>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($times as $time)
                                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded">
                                                    {{ $time }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif($times && !is_array($times))
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-zinc-700 rounded-lg">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($day) }}</span>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded">
                                                {{ $times }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Experience -->
                @if($volunteer->experience)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Experience & Background</h2>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $volunteer->experience }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Application Status -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Application Status</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Current Status</label>
                            <span class="px-3 py-2 text-sm font-medium rounded-lg {{ $this->statusColor }}">
                                {{ ucfirst($volunteer->status) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Applied</label>
                            <p class="text-gray-900 dark:text-white">{{ $volunteer->applied_at->format('M j, Y') }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $volunteer->applied_at->format('g:i A') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Time Since Application</label>
                            <p class="text-gray-900 dark:text-white">{{ $volunteer->applied_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                    
                    <div class="space-y-3">
                        <flux:button href="mailto:{{ $volunteer->email }}" variant="outline" class="w-full justify-start">
                            <flux:icon.envelope class="w-4 h-4 mr-2" />
                            Send Email
                        </flux:button>

                        @if($volunteer->phone)
                            <flux:button href="tel:{{ $volunteer->phone }}" variant="outline" class="w-full justify-start">
                                <flux:icon.phone class="w-4 h-4 mr-2" />
                                Call Volunteer
                            </flux:button>
                        @endif

                        @if($volunteer->status !== 'approved')
                            <flux:button wire:click="updateStatus('approved')" variant="outline" class="w-full justify-start">
                                <flux:icon.check-circle class="w-4 h-4 mr-2" />
                                Approve Application
                            </flux:button>
                        @endif

                        @if($volunteer->status !== 'active')
                            <flux:button wire:click="updateStatus('active')" variant="outline" class="w-full justify-start">
                                <flux:icon.heart class="w-4 h-4 mr-2" />
                                Mark as Active
                            </flux:button>
                        @endif

                        <hr class="border-gray-200 dark:border-zinc-700">

                        <flux:button 
                            wire:click="deleteVolunteer" 
                            wire:confirm="Are you sure you want to delete {{ $volunteer->name }}'s application? This action cannot be undone."
                            variant="danger" 
                            class="w-full justify-start"
                        >
                            <flux:icon.trash class="w-4 h-4 mr-2" />
                            Delete Application
                        </flux:button>
                    </div>
                </div>

                <!-- Application Summary -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Application Summary</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Areas of Interest</span>
                            <span class="text-sm text-gray-900 dark:text-white">
                                {{ $volunteer->areas_of_interest ? (is_array($volunteer->areas_of_interest) ? count($volunteer->areas_of_interest) : 1) : 0 }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Available Days</span>
                            <span class="text-sm text-gray-900 dark:text-white">
                                @if($volunteer->availability)
                                    {{ count(array_filter($volunteer->availability, fn($times) => $times && (is_array($times) ? count($times) > 0 : !empty($times)))) }}
                                @else
                                    0
                                @endif
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Has Experience</span>
                            <span class="text-sm text-gray-900 dark:text-white">
                                {{ $volunteer->experience ? 'Yes' : 'No' }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Contact Methods</span>
                            <span class="text-sm text-gray-900 dark:text-white">
                                {{ $volunteer->phone ? 'Email & Phone' : 'Email Only' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Status History -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-4">
                    <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">Status Guidelines</h3>
                    <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                        <li>• <strong>Pending:</strong> New application awaiting review</li>
                        <li>• <strong>Approved:</strong> Application approved, ready to volunteer</li>
                        <li>• <strong>Active:</strong> Currently volunteering regularly</li>
                        <li>• <strong>Inactive:</strong> Not currently volunteering</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>