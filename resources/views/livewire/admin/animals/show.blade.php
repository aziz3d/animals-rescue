<?php

use App\Models\Animal;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    public Animal $animal;

    public function mount(Animal $animal)
    {
        $this->animal = $animal;
    }

    public function toggleFeatured()
    {
        $this->animal->update(['featured' => !$this->animal->featured]);
        session()->flash('success', 'Featured status updated successfully!');
    }

    public function updateStatus($newStatus)
    {
        $this->animal->update(['adoption_status' => $newStatus]);
        session()->flash('success', 'Adoption status updated successfully!');
    }

    public function deleteAnimal()
    {
        $this->animal->delete();
        return redirect()->route('admin.animals.index')
                        ->with('success', 'Animal deleted successfully.');
    }

    public function getStatusColorProperty()
    {
        return match($this->animal->adoption_status) {
            'available' => 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900',
            'pending' => 'text-yellow-600 bg-yellow-100 dark:text-yellow-400 dark:bg-yellow-900',
            'adopted' => 'text-purple-600 bg-purple-100 dark:text-purple-400 dark:bg-purple-900',
            default => 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-900',
        };
    }
}; ?>

<div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $animal->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">{{ $animal->breed }} • {{ $animal->age }} {{ Str::plural('year', $animal->age) }} old</p>
                </div>
                <div class="flex items-center space-x-3">
                    <flux:button href="{{ route('admin.animals.edit', $animal) }}" variant="primary" size="sm">
                        <flux:icon.pencil class="w-4 h-4 mr-2" />
                        Edit Profile
                    </flux:button>
                    <flux:dropdown>
                        <flux:button variant="ghost" size="sm">
                            <flux:icon.ellipsis-horizontal class="w-4 h-4" />
                        </flux:button>
                        <flux:menu>
                            <flux:menu.item wire:click="toggleFeatured">
                                <flux:icon.star class="w-4 h-4 mr-2" />
                                {{ $animal->featured ? 'Remove from Featured' : 'Mark as Featured' }}
                            </flux:menu.item>
                            <flux:menu.separator />
                            @if($animal->adoption_status !== 'available')
                                <flux:menu.item wire:click="updateStatus('available')">
                                    <flux:icon.check-circle class="w-4 h-4 mr-2" />
                                    Mark as Available
                                </flux:menu.item>
                            @endif
                            @if($animal->adoption_status !== 'pending')
                                <flux:menu.item wire:click="updateStatus('pending')">
                                    <flux:icon.clock class="w-4 h-4 mr-2" />
                                    Mark as Pending
                                </flux:menu.item>
                            @endif
                            @if($animal->adoption_status !== 'adopted')
                                <flux:menu.item wire:click="updateStatus('adopted')">
                                    <flux:icon.home class="w-4 h-4 mr-2" />
                                    Mark as Adopted
                                </flux:menu.item>
                            @endif
                            <flux:menu.separator />
                            <flux:menu.item 
                                wire:click="deleteAnimal" 
                                wire:confirm="Are you sure you want to delete {{ $animal->name }}? This action cannot be undone."
                            >
                                <flux:icon.trash class="w-4 h-4 mr-2" />
                                Delete Animal
                            </flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>
        </div>

        <!-- Back Link -->
        <div class="mb-6">
            <flux:button href="{{ route('admin.animals.index') }}" variant="ghost" size="sm">
                <flux:icon.arrow-left class="w-4 h-4 mr-2" />
                Back to Animals
            </flux:button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Images -->
                @if($animal->image_urls && count($animal->image_urls) > 0)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
                            @foreach($animal->image_urls as $index => $imageUrl)
                                <div class="relative">
                                    <img 
                                        src="{{ $imageUrl }}" 
                                        alt="{{ $animal->name }} - Photo {{ $index + 1 }}"
                                        class="w-full aspect-square object-cover rounded-lg"
                                    >
                                    @if($index === 0)
                                        <div class="absolute top-2 left-2">
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-500 text-white rounded">
                                                Main Photo
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Description -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">About {{ $animal->name }}</h2>
                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $animal->description }}</p>
                    </div>
                </div>

                <!-- Medical History -->
                @if($animal->medical_history)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Medical History</h2>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $animal->medical_history }}</p>
                        </div>
                    </div>
                @endif

                <!-- Public Profile Link -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100">Public Profile</h3>
                            <p class="text-sm text-blue-800 dark:text-blue-200">View how this profile appears to visitors</p>
                        </div>
                        <flux:button href="{{ route('animals.show', $animal) }}" variant="outline" size="sm" target="_blank">
                            <flux:icon.arrow-top-right-on-square class="w-4 h-4 mr-2" />
                            View Public Profile
                        </flux:button>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Basic Information -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Name</label>
                            <p class="text-gray-900 dark:text-white">{{ $animal->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Species</label>
                            <p class="text-gray-900 dark:text-white">{{ ucfirst($animal->species) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Breed</label>
                            <p class="text-gray-900 dark:text-white">{{ $animal->breed }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Age</label>
                            <p class="text-gray-900 dark:text-white">{{ $animal->age }} {{ Str::plural('year', $animal->age) }} old</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Gender</label>
                            <p class="text-gray-900 dark:text-white">{{ ucfirst($animal->gender) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Size</label>
                            <p class="text-gray-900 dark:text-white">{{ ucfirst($animal->size) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Status</label>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $this->statusColor }}">
                                {{ ucfirst($animal->adoption_status) }}
                            </span>
                        </div>

                        @if($animal->featured)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Featured</label>
                                <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-400 rounded-full">
                                    Featured Animal
                                </span>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Added</label>
                            <p class="text-gray-900 dark:text-white">{{ $animal->created_at->format('M j, Y') }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $animal->created_at->format('g:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                    
                    <div class="space-y-3">
                        <flux:button href="{{ route('admin.animals.edit', $animal) }}" variant="outline" class="w-full justify-start">
                            <flux:icon.pencil class="w-4 h-4 mr-2" />
                            Edit Profile
                        </flux:button>

                        <flux:button wire:click="toggleFeatured" variant="outline" class="w-full justify-start">
                            <flux:icon.star class="w-4 h-4 mr-2" />
                            {{ $animal->featured ? 'Remove from Featured' : 'Mark as Featured' }}
                        </flux:button>

                        @if($animal->adoption_status !== 'available')
                            <flux:button wire:click="updateStatus('available')" variant="outline" class="w-full justify-start">
                                <flux:icon.check-circle class="w-4 h-4 mr-2" />
                                Mark as Available
                            </flux:button>
                        @endif

                        @if($animal->adoption_status !== 'pending')
                            <flux:button wire:click="updateStatus('pending')" variant="outline" class="w-full justify-start">
                                <flux:icon.clock class="w-4 h-4 mr-2" />
                                Mark as Pending
                            </flux:button>
                        @endif

                        @if($animal->adoption_status !== 'adopted')
                            <flux:button wire:click="updateStatus('adopted')" variant="outline" class="w-full justify-start">
                                <flux:icon.home class="w-4 h-4 mr-2" />
                                Mark as Adopted
                            </flux:button>
                        @endif

                        <hr class="border-gray-200 dark:border-zinc-700">

                        <flux:button 
                            wire:click="deleteAnimal" 
                            wire:confirm="Are you sure you want to delete {{ $animal->name }}? This action cannot be undone."
                            variant="danger" 
                            class="w-full justify-start"
                        >
                            <flux:icon.trash class="w-4 h-4 mr-2" />
                            Delete Animal
                        </flux:button>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Profile Statistics</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Profile Created</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $animal->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Last Updated</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $animal->updated_at->diffForHumans() }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Photos</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $animal->images ? count($animal->images) : 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>