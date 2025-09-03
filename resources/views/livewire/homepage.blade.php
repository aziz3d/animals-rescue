<div>
    <!-- Hero Section -->
    <section class="relative text-white py-16 sm:py-20 lg:py-24 overflow-hidden" aria-labelledby="hero-heading">
        <!-- Background -->
        <div class="absolute inset-0">
            @if($heroSettings['background_image'])
                @php
                    $heroImageUrl = setting_asset('hero_background_image');
                @endphp
                <img src="{{ $heroImageUrl }}" 
                     alt="Hero background" 
                     class="w-full h-full object-cover"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="w-full h-full bg-gradient-to-r from-amber-500 to-orange-500" style="display: none;"></div>
            @else
                <div class="w-full h-full bg-gradient-to-r from-amber-500 to-orange-500"></div>
            @endif
        </div>
        
        <!-- Content -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 id="hero-heading" class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 sm:mb-6">
                {{ $heroSettings['title'] }}
            </h1>
            <p class="text-lg sm:text-xl md:text-2xl mb-6 sm:mb-8 max-w-3xl mx-auto leading-relaxed">
                {{ $heroSettings['subtitle'] }}
            </p>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-center max-w-2xl mx-auto">
                <a href="{{ $heroSettings['cta1_url'] }}" 
                   class="w-full sm:w-auto bg-white text-amber-600 px-6 sm:px-8 py-3 rounded-lg font-semibold hover:bg-amber-50 transition-colors duration-200 focus-visible touch-target"
                   aria-describedby="cta1-description">
                    {{ $heroSettings['cta1_text'] }}
                </a>
                <a href="{{ $heroSettings['cta2_url'] }}" 
                   class="w-full sm:w-auto bg-amber-700 text-white px-6 sm:px-8 py-3 rounded-lg font-semibold hover:bg-amber-800 transition-colors duration-200 focus-visible touch-target"
                   aria-describedby="cta2-description">
                    {{ $heroSettings['cta2_text'] }}
                </a>
                <a href="{{ $heroSettings['cta3_url'] }}" 
                   class="w-full sm:w-auto border-2 border-white text-white px-6 sm:px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-amber-600 transition-colors duration-200 focus-visible touch-target"
                   aria-describedby="cta3-description">
                    {{ $heroSettings['cta3_text'] }}
                </a>
            </div>
            <!-- Screen reader descriptions -->
            <div class="sr-only">
                <p id="cta1-description">{{ $heroSettings['cta1_text'] }} - Navigate to {{ $heroSettings['cta1_url'] }}</p>
                <p id="cta2-description">{{ $heroSettings['cta2_text'] }} - Navigate to {{ $heroSettings['cta2_url'] }}</p>
                <p id="cta3-description">{{ $heroSettings['cta3_text'] }} - Navigate to {{ $heroSettings['cta3_url'] }}</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 sm:py-16 bg-white" aria-labelledby="stats-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 id="stats-heading" class="sr-only">Our Impact Statistics</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 text-center">
                <div class="bg-amber-50 p-4 sm:p-6 rounded-lg" role="img" aria-labelledby="stat-rescued">
                    <div class="text-2xl sm:text-3xl font-bold text-amber-600 mb-2" aria-hidden="true">{{ $stats['total_rescued'] }}+</div>
                    <div class="text-amber-800 text-sm sm:text-base" id="stat-rescued">Animals Rescued</div>
                </div>
                <div class="bg-amber-50 p-4 sm:p-6 rounded-lg" role="img" aria-labelledby="stat-adoptions">
                    <div class="text-2xl sm:text-3xl font-bold text-amber-600 mb-2" aria-hidden="true">{{ $stats['successful_adoptions'] }}+</div>
                    <div class="text-amber-800 text-sm sm:text-base" id="stat-adoptions">Successful Adoptions</div>
                </div>
                <div class="bg-amber-50 p-4 sm:p-6 rounded-lg" role="img" aria-labelledby="stat-available">
                    <div class="text-2xl sm:text-3xl font-bold text-amber-600 mb-2" aria-hidden="true">{{ $stats['available_animals'] }}</div>
                    <div class="text-amber-800 text-sm sm:text-base" id="stat-available">Animals Available</div>
                </div>
                <div class="bg-amber-50 p-4 sm:p-6 rounded-lg" role="img" aria-labelledby="stat-stories">
                    <div class="text-2xl sm:text-3xl font-bold text-amber-600 mb-2" aria-hidden="true">{{ $stats['total_stories'] }}</div>
                    <div class="text-amber-800 text-sm sm:text-base" id="stat-stories">Success Stories</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Animals Section -->
    <section class="py-16 bg-amber-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-amber-800 mb-4">
                    {{ $featuredAnimalsSettings['title'] }}
                </h2>
                <p class="text-lg text-amber-700 max-w-2xl mx-auto">
                    {{ $featuredAnimalsSettings['description'] }}
                </p>
            </div>
            
            @if($featuredAnimals->count() > 0)
                @php
                    $count = $featuredAnimals->count();
                    $columns = match(true) {
                        $count == 1 => 'grid-cols-1',
                        $count == 2 => 'grid-cols-1 sm:grid-cols-2',
                        $count >= 3 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3'
                    };
                @endphp
                <div class="grid {{ $columns }} gap-8">
                    @foreach($featuredAnimals as $animal)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                            <div class="h-48 bg-gray-200 overflow-hidden">
                                @if($animal->first_image)
                                    <img src="{{ $animal->first_image }}" 
                                         alt="Photo of {{ $animal->name }}, a {{ $animal->age }} year old {{ $animal->breed }}" 
                                         class="w-full h-full object-cover"
                                         loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-amber-100" role="img" aria-label="No photo available for {{ $animal->name }}">
                                        <svg class="w-16 h-16 text-amber-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-semibold text-amber-800">{{ $animal->name }}</h3>
                                    <span class="bg-amber-100 text-amber-800 text-xs px-2 py-1 rounded-full">
                                        {{ ucfirst($animal->adoption_status) }}
                                    </span>
                                </div>
                                <p class="text-amber-600 text-sm mb-2">
                                    {{ $animal->breed }} • {{ $animal->age }} {{ $animal->age == 1 ? 'year' : 'years' }} old • {{ ucfirst($animal->gender) }}
                                </p>
                                <p class="text-gray-700 text-sm mb-4">
                                    {{ \Illuminate\Support\Str::limit($animal->description, 100) }}
                                </p>
                                <a href="{{ route('animals.show', $animal) }}" 
                                   class="inline-block bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-amber-700 transition-colors duration-200">
                                    Learn More
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                @php
                    $count = max(1, (int) app('App\Models\Setting')::get('animals_homepage_count', 6));
                    $columns = match(true) {
                        $count == 1 => 'grid-cols-1',
                        $count == 2 => 'grid-cols-1 sm:grid-cols-2',
                        $count >= 3 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3'
                    };
                @endphp
                <div class="grid {{ $columns }} gap-8">
                    @for($i = 1; $i <= $count; $i++)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">Featured Animal {{ $i }}</span>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-amber-800 mb-2">Coming Soon</h3>
                                <p class="text-amber-600">Featured animals will be displayed here</p>
                            </div>
                        </div>
                    @endfor
                </div>
            @endif
            
            <div class="text-center mt-8">
                <a href="{{ route('animals.index') }}" 
                   class="bg-amber-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-amber-700 transition-colors duration-200">
                    View All Animals
                </a>
            </div>
        </div>
    </section>

    <!-- Latest Stories Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-amber-800 mb-4">
                    {{ $storiesSettings['title'] }}
                </h2>
                <p class="text-lg text-amber-700 max-w-2xl mx-auto">
                    {{ $storiesSettings['description'] }}
                </p>
            </div>
            
            @if($latestStories->count() > 0)
                @php
                    $count = $latestStories->count();
                    $columns = match(true) {
                        $count == 1 => 'grid-cols-1',
                        $count >= 2 => 'grid-cols-1 md:grid-cols-2'
                    };
                @endphp
                <div class="grid {{ $columns }} gap-8">
                    @foreach($latestStories as $story)
                        <div class="bg-amber-50 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                            @if($story->featured_image)
                                <div class="h-48 overflow-hidden">
                                    <img src="{{ $story->featured_image_url }}" 
                                         alt="{{ $story->title }}" 
                                         class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="flex items-center mb-2">
                                    @if($story->featured)
                                    <span class="bg-amber-200 text-amber-800 text-xs px-2 py-1 rounded-full mr-2">
                                        Featured
                                    </span>
                                    @endif
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-2">
                                        {{ ucfirst(str_replace('_', ' ', $story->category)) }}
                                    </span>
                                    <span class="text-amber-600 text-sm">
                                        {{ $story->published_at->format('M j, Y') }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-semibold text-amber-800 mb-3">{{ $story->title }}</h3>
                                <p class="text-amber-700 mb-4">
                                    {{ $story->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($story->content), 150) }}
                                </p>
                                <a href="{{ route('stories.show', $story) }}" 
                                   class="inline-flex items-center text-amber-600 font-semibold hover:text-amber-800 transition-colors duration-200">
                                    Read More
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                @php
                    $count = max(1, (int) app('App\Models\Setting')::get('stories_homepage_count', 6));
                    $columns = match(true) {
                        $count == 1 => 'grid-cols-1',
                        $count >= 2 => 'grid-cols-1 md:grid-cols-2'
                    };
                @endphp
                <div class="grid {{ $columns }} gap-8">
                    @for($i = 1; $i <= $count; $i++)
                        <div class="bg-amber-50 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-amber-800 mb-3">Coming Soon</h3>
                            <p class="text-amber-700 mb-4">Latest success stories will be displayed here</p>
                            <span class="text-sm text-amber-600">Story preview placeholder</span>
                        </div>
                    @endfor
                </div>
            @endif
            
            <div class="text-center mt-8">
                <a href="{{ route('stories.index') }}" 
                   class="bg-amber-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-amber-700 transition-colors duration-200">
                    Read All Stories
                </a>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-16 bg-amber-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                {{ $ctaSettings['title'] }}
            </h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                {{ $ctaSettings['description'] }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ $ctaSettings['button1_url'] }}" 
                   class="bg-white text-amber-600 px-8 py-3 rounded-lg font-semibold hover:bg-amber-50 transition-colors duration-200">
                    {{ $ctaSettings['button1_text'] }}
                </a>
                <a href="{{ $ctaSettings['button2_url'] }}" 
                   class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-amber-600 transition-colors duration-200">
                    {{ $ctaSettings['button2_text'] }}
                </a>
            </div>
        </div>
    </section>
</div>