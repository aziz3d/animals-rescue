<x-layouts.public title="Success Stories - Lovely Paws Rescue">
    <div class="bg-amber-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-amber-800 mb-4">
                    Success Stories
                </h1>
                <p class="text-lg text-amber-700 max-w-2xl mx-auto">
                    Read heartwarming tales of rescue, recovery, and the joy of finding forever homes. 
                    These stories showcase the incredible impact of our rescue work.
                </p>
            </div>

            <!-- Featured Story -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-12">
                <div class="md:flex">
                    <div class="md:w-1/2">
                        <div class="h-64 md:h-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">Featured Story Image</span>
                        </div>
                    </div>
                    <div class="md:w-1/2 p-8">
                        <div class="flex items-center mb-4">
                            <span class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-sm font-medium">Featured Story</span>
                        </div>
                        <h2 class="text-2xl font-bold text-amber-800 mb-4">Featured Success Story Title</h2>
                        <p class="text-gray-700 mb-6">
                            This is a placeholder for the featured success story. It will contain an excerpt 
                            of the most recent or highlighted rescue story that showcases the impact of our work.
                        </p>
                        <a href="{{ route('stories.show', ['story' => 'featured']) }}" 
                           class="inline-flex items-center text-amber-600 hover:text-amber-800 font-medium">
                            Read Full Story
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Story Categories -->
            <div class="flex flex-wrap gap-4 mb-8 justify-center">
                <button class="bg-amber-600 text-white px-4 py-2 rounded-full hover:bg-amber-700 transition-colors duration-200">
                    All Stories
                </button>
                <button class="bg-white text-amber-600 border border-amber-600 px-4 py-2 rounded-full hover:bg-amber-50 transition-colors duration-200">
                    Rescue Stories
                </button>
                <button class="bg-white text-amber-600 border border-amber-600 px-4 py-2 rounded-full hover:bg-amber-50 transition-colors duration-200">
                    Adoption Updates
                </button>
                <button class="bg-white text-amber-600 border border-amber-600 px-4 py-2 rounded-full hover:bg-amber-50 transition-colors duration-200">
                    News & Updates
                </button>
            </div>

            <!-- Stories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Placeholder stories - will be replaced with Livewire component in later tasks -->
                @for($i = 1; $i <= 6; $i++)
                <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">Story Image {{ $i }}</span>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center mb-2">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                {{ $i % 3 == 0 ? 'Rescue Story' : ($i % 2 == 0 ? 'Adoption Update' : 'News') }}
                            </span>
                            <span class="text-sm text-gray-500 ml-auto">{{ now()->subDays($i)->format('M j, Y') }}</span>
                        </div>
                        <h3 class="text-xl font-semibold text-amber-800 mb-2">Success Story Title {{ $i }}</h3>
                        <p class="text-gray-700 mb-4">
                            This is a placeholder excerpt for success story {{ $i }}. It will contain a brief 
                            summary of the rescue or adoption story...
                        </p>
                        <a href="{{ route('stories.show', ['story' => $i]) }}" 
                           class="inline-flex items-center text-amber-600 hover:text-amber-800 font-medium">
                            Read More
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </article>
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