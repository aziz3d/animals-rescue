<x-layouts.public title="Privacy Policy">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8">
            <h1 class="text-3xl font-bold text-amber-800 mb-6">Privacy Policy</h1>
            
            <div class="prose prose-amber max-w-none">
                {!! setting('privacy_policy_content', '<p><em>Last updated: ' . date('F d, Y') . '</em></p><p>At Lovely Paws Rescue, we respect your privacy and are committed to protecting your personal information.</p>') !!}
            </div>
        </div>
    </div>
</x-layouts.public>