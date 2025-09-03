<div>
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

            <!-- Search Bar -->
            <div class="max-w-md mx-auto mb-8">
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search stories..." 
                        class="w-full px-4 py-2 pl-10 pr-4 border border-amber-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Featured Story -->
            @if($featuredStory)
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-12">
                <div class="md:flex">
                    <div class="md:w-1/2">
                        <div class="h-64 md:h-full overflow-hidden">
                            @if($featuredStory->featured_image_url)
                                <img src="{{ $featuredStory->featured_image_url }}" 
                                     alt="{{ $featuredStory->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="h-full bg-amber-100 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="md:w-1/2 p-8">
                        <div class="flex items-center mb-4">
                            <span class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-sm font-medium">Featured Story</span>
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium ml-2">
                                {{ ucfirst($featuredStory->category) }}
                            </span>
                        </div>
                        <h2 class="text-2xl font-bold text-amber-800 mb-4">{{ $featuredStory->title }}</h2>
                        <p class="text-gray-700 mb-6">
                            {{ $featuredStory->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($featuredStory->content), 200) }}
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">{{ $featuredStory->published_at->format('F j, Y') }}</span>
                            <a href="{{ route('stories.show', $featuredStory) }}" 
                               class="inline-flex items-center text-amber-600 hover:text-amber-800 font-medium">
                                Read Full Story
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Story Categories -->
            <div class="flex flex-wrap gap-4 mb-8 justify-center">
                <button 
                    wire:click="filterByCategory('all')"
                    class="px-4 py-2 rounded-full transition-colors duration-200 {{ $selectedCategory === 'all' ? 'bg-amber-600 text-white' : 'bg-white text-amber-600 border border-amber-600 hover:bg-amber-50' }}">
                    All Stories
                </button>
                @foreach($categories as $category)
                <button 
                    wire:click="filterByCategory('{{ $category }}')"
                    class="px-4 py-2 rounded-full transition-colors duration-200 {{ $selectedCategory === $category ? 'bg-amber-600 text-white' : 'bg-white text-amber-600 border border-amber-600 hover:bg-amber-50' }}">
                    {{ ucfirst(str_replace('_', ' ', $category)) }}
                </button>
                @endforeach
            </div>

            <!-- Stories Grid -->
            @if($stories->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($stories as $story)
                    <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                        <div class="h-48 overflow-hidden">
                            @if($story->featured_image_url)
                                <img src="{{ $story->featured_image_url }}" 
                                     alt="{{ $story->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="h-full bg-amber-100 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                    {{ ucfirst(str_replace('_', ' ', $story->category)) }}
                                </span>
                                <span class="text-sm text-gray-500">{{ $story->published_at->format('M j, Y') }}</span>
                            </div>
                            <h3 class="text-xl font-semibold text-amber-800 mb-2">{{ $story->title }}</h3>
                            <p class="text-gray-700 mb-4">
                                {{ $story->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($story->content), 120) }}
                            </p>
                            <a href="{{ route('stories.show', $story) }}" 
                               class="inline-flex items-center text-amber-600 hover:text-amber-800 font-medium">
                                Read More
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $stories->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-amber-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-amber-800 mb-2">No Stories Found</h3>
                    <p class="text-amber-600">
                        @if($search)
                            No stories match your search criteria. Try adjusting your search terms.
                        @else
                            No stories are available in this category yet.
                        @endif
                    </p>
                    @if($search || $selectedCategory !== 'all')
                        <button 
                            wire:click="$set('search', ''); $set('selectedCategory', 'all')"
                            class="mt-4 text-amber-600 hover:text-amber-800 font-medium">
                            Clear filters
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>