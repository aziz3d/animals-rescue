<?php

use App\Models\Animal;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $status = '';
    public string $species = '';
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

    public function updatingSpecies()
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

    public function toggleFeatured($animalId)
    {
        $animal = Animal::findOrFail($animalId);
        $animal->update(['featured' => !$animal->featured]);
        
        $this->dispatch('animal-updated');
    }

    public function updateStatus($animalId, $newStatus)
    {
        $animal = Animal::findOrFail($animalId);
        $animal->update(['adoption_status' => $newStatus]);
        
        $this->dispatch('animal-updated');
    }

    public function deleteAnimal($animalId)
    {
        Animal::findOrFail($animalId)->delete();
        
        $this->dispatch('animal-deleted');
    }

    public function with()
    {
        $query = Animal::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('breed', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status) {
            $query->where('adoption_status', $this->status);
        }

        if ($this->species) {
            $query->where('species', $this->species);
        }

        $animals = $query->orderBy($this->sortBy, $this->sortDirection)
                        ->paginate(12);

        $stats = [
            'total' => Animal::count(),
            'available' => Animal::where('adoption_status', 'available')->count(),
            'pending' => Animal::where('adoption_status', 'pending')->count(),
            'adopted' => Animal::where('adoption_status', 'adopted')->count(),
            'featured' => Animal::where('featured', true)->count(),
        ];

        return compact('animals', 'stats');
    }
}; ?>

<div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Animals Management</h1>
                    <p class="text-gray-600 dark:text-gray-400">Manage animal listings and adoption status</p>
                </div>
                <flux:button href="{{ route('admin.animals.create') }}" variant="primary">
                    <flux:icon.plus class="w-4 h-4 mr-2" />
                    Add New Animal
                </flux:button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <flux:icon.heart class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Animals</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                        <flux:icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Available</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['available'] }}</p>
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
                    <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <flux:icon.home class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Adopted</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['adopted'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                        <flux:icon.star class="w-5 h-5 text-orange-600 dark:text-orange-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Featured</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['featured'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <flux:field>
                        <flux:label>Search Animals</flux:label>
                        <flux:input 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Search by name, breed, or description..."
                        />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Filter by Status</flux:label>
                        <flux:select wire:model.live="status">
                            <option value="">All Statuses</option>
                            <option value="available">Available</option>
                            <option value="pending">Pending</option>
                            <option value="adopted">Adopted</option>
                        </flux:select>
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Filter by Species</flux:label>
                        <flux:select wire:model.live="species">
                            <option value="">All Species</option>
                            <option value="dog">Dogs</option>
                            <option value="cat">Cats</option>
                            <option value="rabbit">Rabbits</option>
                            <option value="bird">Birds</option>
                            <option value="other">Other</option>
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

        <!-- Animals Grid -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden">
            @if($animals->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
                    @foreach($animals as $animal)
                        <div class="relative bg-gray-50 dark:bg-zinc-700 rounded-lg overflow-hidden">
                            <!-- Animal Image -->
                            <div class="aspect-square relative">
                                @if($animal->first_image)
                                    <img 
                                        src="{{ $animal->first_image }}" 
                                        alt="{{ $animal->name }}"
                                        class="w-full h-full object-cover"
                                    >
                                @else
                                    <div class="w-full h-full bg-gray-200 dark:bg-zinc-600 flex items-center justify-center">
                                        <flux:icon.photo class="w-12 h-12 text-gray-400 dark:text-zinc-500" />
                                    </div>
                                @endif

                                <!-- Featured Badge -->
                                @if($animal->featured)
                                    <div class="absolute top-2 left-2">
                                        <span class="px-2 py-1 text-xs font-medium bg-orange-500 text-white rounded-full">
                                            Featured
                                        </span>
                                    </div>
                                @endif

                                <!-- Status Badge -->
                                <div class="absolute top-2 right-2">
                                    @php
                                        $statusColor = match($animal->adoption_status) {
                                            'available' => 'bg-green-500',
                                            'pending' => 'bg-yellow-500',
                                            'adopted' => 'bg-purple-500',
                                            default => 'bg-gray-500',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium text-white rounded-full {{ $statusColor }}">
                                        {{ ucfirst($animal->adoption_status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Animal Info -->
                            <div class="p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $animal->name }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $animal->breed }} • {{ $animal->age }} {{ Str::plural('year', $animal->age) }} old
                                        </p>
                                    </div>
                                </div>

                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                    {{ Str::limit($animal->description, 80) }}
                                </p>

                                <!-- Actions -->
                                <div class="flex items-center justify-between">
                                    <div class="flex space-x-2">
                                        <flux:button 
                                            href="{{ route('admin.animals.show', $animal) }}" 
                                            variant="ghost" 
                                            size="sm"
                                        >
                                            <flux:icon.eye class="w-4 h-4" />
                                        </flux:button>
                                        <flux:button 
                                            href="{{ route('admin.animals.edit', $animal) }}" 
                                            variant="ghost" 
                                            size="sm"
                                        >
                                            <flux:icon.pencil class="w-4 h-4" />
                                        </flux:button>
                                    </div>

                                    <flux:dropdown>
                                        <flux:button variant="ghost" size="sm">
                                            <flux:icon.ellipsis-horizontal class="w-4 h-4" />
                                        </flux:button>
                                        <flux:menu>
                                            <flux:menu.item wire:click="toggleFeatured({{ $animal->id }})">
                                                <flux:icon.star class="w-4 h-4 mr-2" />
                                                {{ $animal->featured ? 'Remove from Featured' : 'Mark as Featured' }}
                                            </flux:menu.item>
                                            <flux:menu.separator />
                                            @if($animal->adoption_status !== 'available')
                                                <flux:menu.item wire:click="updateStatus({{ $animal->id }}, 'available')">
                                                    <flux:icon.check-circle class="w-4 h-4 mr-2" />
                                                    Mark as Available
                                                </flux:menu.item>
                                            @endif
                                            @if($animal->adoption_status !== 'pending')
                                                <flux:menu.item wire:click="updateStatus({{ $animal->id }}, 'pending')">
                                                    <flux:icon.clock class="w-4 h-4 mr-2" />
                                                    Mark as Pending
                                                </flux:menu.item>
                                            @endif
                                            @if($animal->adoption_status !== 'adopted')
                                                <flux:menu.item wire:click="updateStatus({{ $animal->id }}, 'adopted')">
                                                    <flux:icon.home class="w-4 h-4 mr-2" />
                                                    Mark as Adopted
                                                </flux:menu.item>
                                            @endif
                                            <flux:menu.separator />
                                            <flux:menu.item 
                                                wire:click="deleteAnimal({{ $animal->id }})" 
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
                    @endforeach
                </div>

                @if($animals->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
                        {{ $animals->links() }}
                    </div>
                @endif
            @else
                <div class="px-6 py-12 text-center">
                    <div class="text-gray-500 dark:text-gray-400">
                        <flux:icon.heart class="w-12 h-12 mx-auto mb-4" />
                        <h3 class="text-lg font-medium mb-2">No animals found</h3>
                        <p class="text-sm mb-4">
                            @if($search || $status || $species)
                                Try adjusting your search criteria or filters.
                            @else
                                Get started by adding your first animal to the rescue database.
                            @endif
                        </p>
                        <flux:button href="{{ route('admin.animals.create') }}" variant="primary">
                            <flux:icon.plus class="w-4 h-4 mr-2" />
                            Add First Animal
                        </flux:button>
                    </div>
                </div>
            @endif
        </div>
</div>