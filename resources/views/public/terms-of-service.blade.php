<x-layouts.public title="Terms of Service">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8">
            <h1 class="text-3xl font-bold text-amber-800 mb-6">Terms of Service</h1>
            
            <div class="prose prose-amber max-w-none">
                {!! setting('terms_of_service_content', '<p><em>Last updated: ' . date('F d, Y') . '</em></p><p>Welcome to Lovely Paws Rescue. These Terms of Service govern your use of our website and services.</p>') !!}
            </div>
        </div>
    </div>
</x-layouts.public>