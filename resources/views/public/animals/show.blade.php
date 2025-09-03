<x-layouts.public title="Animal Details - Lovely Paws Rescue">
    <div class="bg-amber-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-8">
                <a href="{{ route('animals.index') }}" 
                   class="inline-flex items-center text-amber-600 hover:text-amber-800 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Animals
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Animal Images -->
                <div>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="h-96 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">Main Animal Photo</span>
                        </div>
                    </div>
                    <!-- Image Gallery -->
                    <div class="grid grid-cols-4 gap-2 mt-4">
                        @for($i = 1; $i <= 4; $i++)
                        <div class="h-20 bg-gray-200 rounded-md flex items-center justify-center cursor-pointer hover:opacity-75 transition-opacity">
                            <span class="text-xs text-gray-500">{{ $i }}</span>
                        </div>
                        @endfor
                    </div>
                </div>

                <!-- Animal Details -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <div class="flex justify-between items-start mb-4">
                        <h1 class="text-3xl font-bold text-amber-800">Animal Name</h1>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Available</span>
                    </div>

                    <!-- Basic Info -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <span class="text-sm font-medium text-amber-700">Species:</span>
                            <p class="text-gray-800">Dog</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-amber-700">Breed:</span>
                            <p class="text-gray-800">Golden Retriever Mix</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-amber-700">Age:</span>
                            <p class="text-gray-800">3 years</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-amber-700">Gender:</span>
                            <p class="text-gray-800">Female</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-amber-700">Size:</span>
                            <p class="text-gray-800">Medium</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-amber-700">Weight:</span>
                            <p class="text-gray-800">45 lbs</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-amber-800 mb-2">About Me</h3>
                        <p class="text-gray-700 leading-relaxed">
                            This is a placeholder description for the animal. In the actual implementation, 
                            this will contain detailed information about the animal's personality, history, 
                            special needs, and what kind of home would be best suited for them.
                        </p>
                    </div>

                    <!-- Medical History -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-amber-800 mb-2">Medical History</h3>
                        <ul class="text-gray-700 space-y-1">
                            <li>• Spayed/Neutered: Yes</li>
                            <li>• Vaccinations: Up to date</li>
                            <li>• Microchipped: Yes</li>
                            <li>• Special needs: None</li>
                        </ul>
                    </div>

                    <!-- Adoption Requirements -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-amber-800 mb-2">Adoption Requirements</h3>
                        <ul class="text-gray-700 space-y-1">
                            <li>• Fenced yard preferred</li>
                            <li>• Good with children</li>
                            <li>• Good with other dogs</li>
                            <li>• Cats unknown</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button class="w-full bg-amber-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-amber-700 transition-colors duration-200">
                            Start Adoption Process
                        </button>
                        <button class="w-full border-2 border-amber-600 text-amber-600 px-6 py-3 rounded-lg font-semibold hover:bg-amber-50 transition-colors duration-200">
                            Ask a Question
                        </button>
                        <div class="flex space-x-2">
                            <button class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                Share on Facebook
                            </button>
                            <button class="flex-1 bg-blue-400 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition-colors duration-200">
                                Share on Twitter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>