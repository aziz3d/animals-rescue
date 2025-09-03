<div>
    <div class="bg-amber-50 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-8">
                <a href="{{ route('stories.index') }}" 
                   class="inline-flex items-center text-amber-600 hover:text-amber-800 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Stories
                </a>
            </div>

            <!-- Story Header -->
            <article class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($story->featured_image_url)
                <div class="h-64 md:h-96 overflow-hidden">
                    <img src="{{ $story->featured_image_url }}" 
                         alt="{{ $story->title }}" 
                         class="w-full h-full object-cover">
                </div>
                @endif
                
                <div class="p-8">
                    <!-- Story Meta -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                        <div class="flex items-center space-x-4 mb-4 md:mb-0">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                {{ ucfirst(str_replace('_', ' ', $story->category)) }}
                            </span>
                            @if($story->featured)
                            <span class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-sm font-medium">
                                Featured
                            </span>
                            @endif
                            <span class="text-gray-500">Published on {{ $story->published_at->format('F j, Y') }}</span>
                        </div>
                        <div class="flex space-x-2">
                            <!-- Social Sharing Placeholders -->
                            <button 
                                onclick="navigator.share ? navigator.share({title: '{{ addslashes($story->title) }}', url: window.location.href}) : alert('Sharing not supported')"
                                class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition-colors duration-200 text-sm">
                                Share
                            </button>
                            <button 
                                onclick="window.print()"
                                class="bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700 transition-colors duration-200 text-sm">
                                Print
                            </button>
                        </div>
                    </div>

                    <!-- Story Title -->
                    <h1 class="text-3xl md:text-4xl font-bold text-amber-800 mb-6">
                        {{ $story->title }}
                    </h1>

                    <!-- Story Excerpt -->
                    @if($story->excerpt)
                    <div class="bg-amber-50 border-l-4 border-amber-400 p-6 mb-8">
                        <p class="text-amber-800 text-lg italic">
                            {{ $story->excerpt }}
                        </p>
                    </div>
                    @endif

                    <!-- Story Content -->
                    <div class="prose prose-lg max-w-none">
                        {!! nl2br(e($story->content)) !!}
                    </div>

                    <!-- Story Footer -->
                    <div class="border-t border-gray-200 pt-6 mt-8">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                            <div class="mb-4 md:mb-0">
                                <p class="text-sm text-gray-600">
                                    Want to help more animals like this one?
                                </p>
                            </div>
                            <div class="flex space-x-4">
                                <a href="{{ route('donate') }}" 
                                   class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition-colors duration-200">
                                    Donate Now
                                </a>
                                <a href="{{ route('volunteer') }}" 
                                   class="border border-amber-600 text-amber-600 px-4 py-2 rounded-lg hover:bg-amber-50 transition-colors duration-200">
                                    Volunteer
                                </a>
                                <a href="{{ route('animals.index') }}" 
                                   class="border border-amber-600 text-amber-600 px-4 py-2 rounded-lg hover:bg-amber-50 transition-colors duration-200">
                                    Adopt
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Comments Section -->
            @livewire('stories.comments', ['story' => $story])

            <!-- Related Stories -->
            @if($relatedStories->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-amber-800 mb-6">More Success Stories</h2>
                <div class="grid grid-cols-1 md:grid-cols-{{ $relatedStories->count() >= 3 ? '3' : $relatedStories->count() }} gap-6">
                    @foreach($relatedStories as $relatedStory)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                        <div class="h-32 overflow-hidden">
                            @if($relatedStory->featured_image_url)
                                <img src="{{ $relatedStory->featured_image_url }}" 
                                     alt="{{ $relatedStory->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="h-full bg-amber-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                    {{ ucfirst(str_replace('_', ' ', $relatedStory->category)) }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $relatedStory->published_at->format('M j') }}</span>
                            </div>
                            <h3 class="text-lg font-semibold text-amber-800 mb-2">{{ $relatedStory->title }}</h3>
                            <p class="text-gray-600 text-sm mb-3">
                                {{ $relatedStory->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($relatedStory->content), 100) }}
                            </p>
                            <a href="{{ route('stories.show', $relatedStory) }}" 
                               class="text-amber-600 hover:text-amber-800 text-sm font-medium">
                                Read More →
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>