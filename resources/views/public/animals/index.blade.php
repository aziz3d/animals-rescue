<x-layouts.public title="Animals - Lovely Paws Rescue">
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

            <!-- Filters Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-lg font-semibold text-amber-800 mb-4">Filter Animals</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-2">Species</label>
                        <select class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option>All Species</option>
                            <option>Dogs</option>
                            <option>Cats</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-2">Age</label>
                        <select class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option>All Ages</option>
                            <option>Puppy/Kitten</option>
                            <option>Young</option>
                            <option>Adult</option>
                            <option>Senior</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-2">Size</label>
                        <select class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option>All Sizes</option>
                            <option>Small</option>
                            <option>Medium</option>
                            <option>Large</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button class="w-full bg-amber-600 text-white px-4 py-2 rounded-md hover:bg-amber-700 transition-colors duration-200">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Animals Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Placeholder animals - will be replaced with Livewire component in later tasks -->
                @for($i = 1; $i <= 6; $i++)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                    <div class="h-64 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">Animal Photo {{ $i }}</span>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-semibold text-amber-800">Animal Name {{ $i }}</h3>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Available</span>
                        </div>
                        <p class="text-amber-600 mb-2">Breed • Age • Gender</p>
                        <p class="text-gray-700 mb-4">Brief description of the animal's personality and needs...</p>
                        <a href="{{ route('animals.show', ['animal' => $i]) }}" 
                           class="block w-full text-center bg-amber-600 text-white px-4 py-2 rounded-md hover:bg-amber-700 transition-colors duration-200">
                            Learn More
                        </a>
                    </div>
                </div>
                @endfor
            </div>

            <!-- Pagination placeholder -->
            <div class="mt-12 flex justify-center">
                <div class="flex space-x-2">
                    <button class="px-3 py-2 border border-amber-200 rounded-md text-amber-600 hover:bg-amber-50">Previous</button>
                    <button class="px-3 py-2 bg-amber-600 text-white rounded-md">1</button>
                    <button class="px-3 py-2 border border-amber-200 rounded-md text-amber-600 hover:bg-amber-50">2</button>
                    <button class="px-3 py-2 border border-amber-200 rounded-md text-amber-600 hover:bg-amber-50">3</button>
                    <button class="px-3 py-2 border border-amber-200 rounded-md text-amber-600 hover:bg-amber-50">Next</button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>