

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

                <!-- Success Message -->
                @if(session('inquiry_sent'))
                    <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">Thank you!</span>
                            <span class="ml-1">Your {{ $inquiry_type }} inquiry has been sent. We'll get back to you soon!</span>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Animal Images -->
                    <div>
                        <!-- Main Image -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-4" wire:key="main-image-{{ $selectedImageIndex }}">
                            <div class="h-96 bg-gray-200 flex items-center justify-center overflow-hidden">
                                @if($animal->image_urls && count($animal->image_urls) > $selectedImageIndex)
                                    <img 
                                        src="{{ $animal->image_urls[$selectedImageIndex] }}" 
                                        alt="{{ $animal->name }}" 
                                        class="w-full h-full object-cover"
                                        wire:key="image-{{ $selectedImageIndex }}"
                                        onload="console.log('Image loaded:', this.src)"
                                        onerror="console.error('Image failed to load:', this.src)"
                                    >
                                @else
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-gray-500 mt-2 block">No photos available</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Image Gallery -->
                        @if($animal->image_urls && count($animal->image_urls) > 1)
                            <div class="grid grid-cols-4 gap-2" wire:key="gallery">
                                @foreach($animal->image_urls as $index => $imageUrl)
                                    <div 
                                        wire:click="selectImage({{ $index }})"
                                        wire:key="thumb-{{ $index }}"
                                        class="h-20 bg-gray-200 rounded-md overflow-hidden cursor-pointer hover:opacity-75 transition-opacity {{ $selectedImageIndex === $index ? 'ring-2 ring-amber-500' : '' }}"
                                    >
                                        <img src="{{ $imageUrl }}" alt="{{ $animal->name }} photo {{ $index + 1 }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Animal Details -->
                    <div class="bg-white rounded-lg shadow-md p-8">
                        <div class="flex justify-between items-start mb-4">
                            <h1 class="text-3xl font-bold text-amber-800">{{ $animal->name }}</h1>
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $this->statusColor }}">
                                {{ ucfirst($animal->adoption_status) }}
                            </span>
                        </div>

                        <!-- Basic Info -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <span class="text-sm font-medium text-amber-700">Species:</span>
                                <p class="text-gray-800">{{ ucfirst($animal->species) }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-amber-700">Breed:</span>
                                <p class="text-gray-800">{{ $animal->breed }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-amber-700">Age:</span>
                                <p class="text-gray-800">{{ $animal->age }} {{ $animal->age == 1 ? 'year' : 'years' }} old</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-amber-700">Gender:</span>
                                <p class="text-gray-800">{{ ucfirst($animal->gender) }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-amber-700">Size:</span>
                                <p class="text-gray-800">{{ ucfirst($animal->size) }}</p>
                            </div>
                            @if($animal->featured)
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Featured
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Description -->
                        @if($animal->description)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-amber-800 mb-2">About {{ $animal->name }}</h3>
                                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $animal->description }}</p>
                            </div>
                        @endif

                        <!-- Medical History -->
                        @if($animal->medical_history)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-amber-800 mb-2">Medical History</h3>
                                <div class="text-gray-700 whitespace-pre-line">{{ $animal->medical_history }}</div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            @if($animal->adoption_status === 'available')
                                <button 
                                    wire:click="toggleInquiryForm('adoption')"
                                    class="w-full bg-amber-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-amber-700 transition-colors duration-200"
                                >
                                    Start Adoption Process
                                </button>
                            @endif
                            
                            <button 
                                wire:click="toggleInquiryForm('question')"
                                class="w-full border-2 border-amber-600 text-amber-600 px-6 py-3 rounded-lg font-semibold hover:bg-amber-50 transition-colors duration-200"
                            >
                                Ask a Question
                            </button>
                            
                            <div class="flex space-x-2">
                                <button 
                                    wire:click="shareOnFacebook"
                                    class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    Facebook
                                </button>
                                <button 
                                    wire:click="shareOnTwitter"
                                    class="flex-1 bg-blue-400 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition-colors duration-200 flex items-center justify-center"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                    Twitter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inquiry Form Modal -->
                @if($showInquiryForm)
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-screen overflow-y-auto">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-amber-800">
                                        {{ $inquiry_type === 'adoption' ? 'Adoption Inquiry' : 'Ask a Question' }} - {{ $animal->name }}
                                    </h3>
                                    <button 
                                        wire:click="toggleInquiryForm"
                                        class="text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <form wire:submit="submitInquiry" class="space-y-4">
                                    <div>
                                        <label for="inquiry_name" class="block text-sm font-medium text-amber-700 mb-1">Name *</label>
                                        <input 
                                            type="text" 
                                            id="inquiry_name"
                                            wire:model="inquiry_name"
                                            class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                            required
                                        >
                                        @error('inquiry_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="inquiry_email" class="block text-sm font-medium text-amber-700 mb-1">Email *</label>
                                        <input 
                                            type="email" 
                                            id="inquiry_email"
                                            wire:model="inquiry_email"
                                            class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                            required
                                        >
                                        @error('inquiry_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="inquiry_phone" class="block text-sm font-medium text-amber-700 mb-1">Phone</label>
                                        <input 
                                            type="tel" 
                                            id="inquiry_phone"
                                            wire:model="inquiry_phone"
                                            class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                        >
                                        @error('inquiry_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="inquiry_message" class="block text-sm font-medium text-amber-700 mb-1">
                                            {{ $inquiry_type === 'adoption' ? 'Tell us about yourself and why you\'d be a great match for ' . $animal->name : 'Your question' }} *
                                        </label>
                                        <textarea 
                                            id="inquiry_message"
                                            wire:model="inquiry_message"
                                            rows="4"
                                            class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                            placeholder="{{ $inquiry_type === 'adoption' ? 'Please tell us about your living situation, experience with pets, and why you think ' . $animal->name . ' would be a good fit for your family.' : 'What would you like to know about ' . $animal->name . '?' }}"
                                            required
                                        ></textarea>
                                        @error('inquiry_message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="flex space-x-3 pt-4">
                                        <button 
                                            type="submit"
                                            class="flex-1 bg-amber-600 text-white px-4 py-2 rounded-md hover:bg-amber-700 transition-colors duration-200"
                                        >
                                            Send {{ $inquiry_type === 'adoption' ? 'Inquiry' : 'Question' }}
                                        </button>
                                        <button 
                                            type="button"
                                            wire:click="toggleInquiryForm"
                                            class="flex-1 border border-amber-200 text-amber-600 px-4 py-2 rounded-md hover:bg-amber-50 transition-colors duration-200"
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
        </div>
    </div>
</div>