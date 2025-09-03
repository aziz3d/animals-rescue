<x-layouts.public title="Page Not Found">
    <div class="min-h-screen flex items-center justify-center bg-amber-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center">
                    <div class="w-24 h-24 bg-amber-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-16 h-16 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.562M15 8a3 3 0 10-6 0m6 0a3 3 0 11-6 0m6 0v6a3 3 0 01-3 3H9a3 3 0 01-3-3v-6"></path>
                        </svg>
                    </div>
                </div>
                
                <h1 class="text-4xl font-extrabold text-amber-800 sm:text-5xl">
                    Page Not Found
                </h1>
                
                <p class="mt-4 text-lg text-amber-700">
                    Sorry, we couldn't find the page you're looking for.
                </p>
                
                <div class="mt-8 bg-amber-100 rounded-lg p-6 text-left">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-amber-800">Error 404</h3>
                            <div class="mt-2 text-sm text-amber-700">
                                <p>
                                    The page you're looking for might have been moved, deleted, or the URL might be incorrect.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Back to Home
                    </a>
                    
                    <a href="{{ route('contact') }}" 
                       class="inline-flex items-center px-6 py-3 border border-amber-300 text-base font-medium rounded-md shadow-sm text-amber-800 bg-white hover:bg-amber-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contact Us
                    </a>
                </div>
                
                <div class="mt-8">
                    <p class="text-sm text-amber-600">
                        Need help? <a href="{{ route('contact') }}" class="font-medium text-amber-800 hover:text-amber-900">Get in touch with our team</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>