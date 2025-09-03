<x-layouts.public title="Story Details - Lovely Paws Rescue">
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
                <div class="h-64 md:h-96 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-500">Story Featured Image</span>
                </div>
                
                <div class="p-8">
                    <!-- Story Meta -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-4">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                Rescue Story
                            </span>
                            <span class="text-gray-500">Published on {{ now()->subDays(5)->format('F j, Y') }}</span>
                        </div>
                        <div class="flex space-x-2">
                            <button class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition-colors duration-200">
                                Share
                            </button>
                        </div>
                    </div>

                    <!-- Story Title -->
                    <h1 class="text-3xl md:text-4xl font-bold text-amber-800 mb-6">
                        A Heartwarming Success Story Title
                    </h1>

                    <!-- Story Content -->
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-6">
                            This is a placeholder for the full story content. In the actual implementation, 
                            this will contain the complete rescue or adoption story with rich text formatting, 
                            images, and detailed narrative about the animal's journey.
                        </p>

                        <p class="text-gray-700 leading-relaxed mb-6">
                            The story will include details about how the animal was rescued, their rehabilitation 
                            process, any medical care they received, and ultimately their successful adoption 
                            into a loving home.
                        </p>

                        <div class="bg-amber-50 border-l-4 border-amber-400 p-6 my-8">
                            <p class="text-amber-800 italic">
                                "This is where we might include a quote from the adopter or volunteer 
                                about their experience with the rescue process."
                            </p>
                        </div>

                        <p class="text-gray-700 leading-relaxed mb-6">
                            Additional paragraphs would continue the story, potentially including updates 
                            from the new family, photos of the animal in their new home, and the positive 
                            impact this rescue has had on everyone involved.
                        </p>

                        <!-- Story Images Gallery -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-8">
                            <div class="h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500">Story Image 1</span>
                            </div>
                            <div class="h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500">Story Image 2</span>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed mb-6">
                            The story would conclude with information about how readers can help support 
                            similar rescues, whether through adoption, volunteering, or donations.
                        </p>
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
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Related Stories -->
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-amber-800 mb-6">More Success Stories</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @for($i = 1; $i <= 2; $i++)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="h-32 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">Related Story {{ $i }}</span>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-amber-800 mb-2">Related Story Title {{ $i }}</h3>
                            <p class="text-gray-600 text-sm mb-3">Brief excerpt of the related story...</p>
                            <a href="{{ route('stories.show', ['story' => 'related-' . $i]) }}" 
                               class="text-amber-600 hover:text-amber-800 text-sm font-medium">
                                Read More →
                            </a>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>