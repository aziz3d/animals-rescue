<?php

use App\Models\Setting;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    public $privacy_policy_content = '';

    public function mount()
    {
        $this->privacy_policy_content = Setting::get('privacy_policy_content', 
            "<h2>Information We Collect</h2>
            <p>We may collect personal information that you provide to us directly, including:</p>
            <ul>
                <li>Name and contact details (email address, phone number, mailing address)</li>
                <li>Payment information when making donations</li>
                <li>Information provided when adopting a pet or volunteering</li>
                <li>Photographs and stories shared for promotional purposes</li>
            </ul>
            
            <h2>How We Use Your Information</h2>
            <p>We use the information we collect for various purposes:</p>
            <ul>
                <li>To process donations and adoption applications</li>
                <li>To communicate with you about our services and events</li>
                <li>To improve our website and user experience</li>
                <li>To send newsletters and promotional materials (with your consent)</li>
                <li>To comply with legal obligations</li>
            </ul>
            
            <h2>Data Security</h2>
            <p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction.</p>
            
            <h2>Your Rights</h2>
            <p>You have the right to access, correct, or delete your personal data. Contact us if you wish to exercise these rights.</p>");
    }

    public function save()
    {
        try {
            Setting::set('privacy_policy_content', $this->privacy_policy_content, 'textarea', 'legal');
            Setting::clearCache();

            $this->dispatch('saved');
            session()->flash('success', 'Privacy Policy content saved successfully!');
        } catch (\Exception $e) {
            \Log::error('Error saving privacy policy content', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'There was an error saving the privacy policy content. Please try again.');
        }
    }
}; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Privacy Policy Content</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage the content of your privacy policy page</p>
        </div>
    </div>

    <div class="max-w-4xl">
        <form wire:submit.prevent="save" class="space-y-8">
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Privacy Policy Content</h2>
                
                <div class="mb-4">
                    <flux:textarea 
                        wire:model="privacy_policy_content" 
                        label="Privacy Policy Content" 
                        placeholder="Enter your privacy policy content here..."
                        rows="15"
                        richtext
                    />
                </div>
                
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    <p>You can use HTML tags to format your content. The privacy policy page will display this content.</p>
                </div>
            </div>

            <!-- Session Error Message -->
            @if (session()->has('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <h3 class="text-red-800 font-medium mb-2">Error</h3>
                    <p class="text-red-700 text-sm">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <h3 class="text-red-800 font-medium mb-2">Please fix the following errors:</h3>
                    <ul class="text-red-700 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50">
                    <svg wire:loading.remove class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <svg wire:loading class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove>Save Content</span>
                    <span wire:loading>Saving...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif
    
    <div x-data="{ show: false }" 
         x-init="
            $wire.on('saved', () => {
                show = true;
                setTimeout(() => show = false, 3000);
            })
         "
         x-show="show"
         x-transition
         class="fixed top-4 right-4 z-50">
        <div class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
            Privacy Policy content saved successfully!
        </div>
    </div>
</div>