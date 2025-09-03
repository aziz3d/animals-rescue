

<div class="bg-amber-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h1 class="text-4xl md:text-5xl font-bold text-amber-800 mb-4">
                        Animals Looking for Homes
                    </h1>
                    <p class="text-lg text-amber-700 max-w-2xl mx-auto">
                        Meet our wonderful animals who are ready to find their forever families. Each one has a unique story and is waiting for the perfect match.
                    </p>
                </div>

                <!-- Search and Filters Section -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <!-- Search Bar -->
                    <div class="mb-6">
                        <label for="search" class="block text-sm font-medium text-amber-700 mb-2">Search Animals</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="search"
                                wire:model.live.debounce.300ms="search"
                                placeholder="Search by name, breed, or description..."
                                class="w-full border border-amber-200 rounded-md px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-amber-500"
                            >
                            <svg class="absolute left-3 top-2.5 h-5 w-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-amber-700 mb-2">Species</label>
                            <select wire:model.live="species" class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option value="">All Species</option>
                                @foreach($species_options as $option)
                                    <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-amber-700 mb-2">Age Group</label>
                            <select wire:model.live="age" class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option value="">All Ages</option>
                                <option value="puppy_kitten">Puppy/Kitten (≤1 year)</option>
                                <option value="young">Young (2-3 years)</option>
                                <option value="adult">Adult (4-7 years)</option>
                                <option value="senior">Senior (8+ years)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-amber-700 mb-2">Size</label>
                            <select wire:model.live="size" class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option value="">All Sizes</option>
                                <option value="small">Small</option>
                                <option value="medium">Medium</option>
                                <option value="large">Large</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-amber-700 mb-2">Breed</label>
                            <select wire:model.live="breed" class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option value="">All Breeds</option>
                                @foreach($breed_options as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-amber-700 mb-2">Status</label>
                            <select wire:model.live="adoption_status" class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option value="">All Status</option>
                                <option value="available">Available</option>
                                <option value="pending">Pending</option>
                                <option value="adopted">Adopted</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex justify-between items-center">
                        <button 
                            wire:click="clearFilters"
                            class="text-amber-600 hover:text-amber-800 text-sm font-medium"
                        >
                            Clear All Filters
                        </button>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-amber-700">View:</span>
                            <button 
                                wire:click="toggleViewMode"
                                class="flex items-center space-x-1 px-3 py-1 rounded-md border border-amber-200 hover:bg-amber-50 transition-colors"
                            >
                                @if($view_mode === 'grid')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                    <span class="text-sm">Grid</span>
                                @else
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm">List</span>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Results Count -->
                <div class="mb-6">
                    <p class="text-amber-700">
                        Showing {{ $animals->count() }} of {{ $animals->total() }} animals
                    </p>
                </div>

                <!-- Animals Display -->
                @if($animals->count() > 0)
                    @if($view_mode === 'grid')
                        <!-- Grid View -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
                            @foreach($animals as $animal)
                                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                                    <div class="h-64 bg-gray-200 flex items-center justify-center overflow-hidden">
                                        @if($animal->first_image)
                                            <img src="{{ $animal->first_image }}" alt="{{ $animal->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-gray-500 mt-2 block">No Photo Available</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-6">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="text-xl font-semibold text-amber-800">{{ $animal->name }}</h3>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                                @if($animal->adoption_status === 'available') bg-green-100 text-green-800
                                                @elseif($animal->adoption_status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($animal->adoption_status) }}
                                            </span>
                                        </div>
                                        <p class="text-amber-600 mb-2">{{ $animal->breed }} • {{ $animal->age }} {{ $animal->age == 1 ? 'year' : 'years' }} • {{ ucfirst($animal->gender) }}</p>
                                        <p class="text-gray-700 mb-4 line-clamp-3">{{ Str::limit($animal->description, 100) }}</p>
                                        <a href="{{ route('animals.show', $animal) }}" 
                                           class="block w-full text-center bg-amber-600 text-white px-4 py-2 rounded-md hover:bg-amber-700 transition-colors duration-200">
                                            Learn More
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- List View -->
                        <div class="space-y-6 mb-8">
                            @foreach($animals as $animal)
                                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                                    <div class="flex flex-col md:flex-row">
                                        <div class="md:w-1/3 h-48 md:h-auto bg-gray-200 flex items-center justify-center overflow-hidden">
                                            @if($animal->first_image)
                                                <img src="{{ $animal->first_image }}" alt="{{ $animal->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="text-center">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="text-gray-500 mt-2 block">No Photo Available</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="md:w-2/3 p-6">
                                            <div class="flex justify-between items-start mb-2">
                                                <h3 class="text-2xl font-semibold text-amber-800">{{ $animal->name }}</h3>
                                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                                    @if($animal->adoption_status === 'available') bg-green-100 text-green-800
                                                    @elseif($animal->adoption_status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($animal->adoption_status) }}
                                                </span>
                                            </div>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 text-sm">
                                                <div>
                                                    <span class="font-medium text-amber-700">Species:</span>
                                                    <p class="text-gray-800">{{ ucfirst($animal->species) }}</p>
                                                </div>
                                                <div>
                                                    <span class="font-medium text-amber-700">Breed:</span>
                                                    <p class="text-gray-800">{{ $animal->breed }}</p>
                                                </div>
                                                <div>
                                                    <span class="font-medium text-amber-700">Age:</span>
                                                    <p class="text-gray-800">{{ $animal->age }} {{ $animal->age == 1 ? 'year' : 'years' }}</p>
                                                </div>
                                                <div>
                                                    <span class="font-medium text-amber-700">Size:</span>
                                                    <p class="text-gray-800">{{ ucfirst($animal->size) }}</p>
                                                </div>
                                            </div>
                                            <p class="text-gray-700 mb-4">{{ Str::limit($animal->description, 200) }}</p>
                                            <a href="{{ route('animals.show', $animal) }}" 
                                               class="inline-block bg-amber-600 text-white px-6 py-2 rounded-md hover:bg-amber-700 transition-colors duration-200">
                                                Learn More
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $animals->links() }}
                    </div>
                @else
                    <!-- No Results -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33"/>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-amber-800">No animals found</h3>
                        <p class="mt-1 text-amber-600">Try adjusting your search criteria or clearing the filters.</p>
                        <button 
                            wire:click="clearFilters"
                            class="mt-4 bg-amber-600 text-white px-4 py-2 rounded-md hover:bg-amber-700 transition-colors duration-200"
                        >
                            Clear Filters
                        </button>
                    </div>
                @endif
        </div>
    </div>
</div>