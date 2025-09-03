<?php

use App\Models\Setting;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    public $terms_of_service_content = '';

    public function mount()
    {
        $this->terms_of_service_content = Setting::get('terms_of_service_content', 
            "<h2>Acceptance of Terms</h2>
            <p>By accessing or using any part of our website, you agree to comply with and be bound by these Terms of Service.</p>
            
            <h2>User Conduct</h2>
            <p>You agree not to:</p>
            <ul>
                <li>Use our website for any unlawful purpose</li>
                <li>Harass, abuse, or harm any animals or individuals</li>
                <li>Post false or misleading information</li>
                <li>Attempt to gain unauthorized access to our systems</li>
                <li>Interfere with the proper functioning of our website</li>
            </ul>
            
            <h2>Donations</h2>
            <p>All donations are voluntary and non-refundable. We are a registered 501(c)(3) nonprofit organization, and donations are tax-deductible to the extent permitted by law.</p>
            
            <h2>Limitation of Liability</h2>
            <p>To the fullest extent permitted by law, we shall not be liable for any indirect, incidental, special, consequential, or punitive damages.</p>
            
            <h2>Changes to Terms</h2>
            <p>We reserve the right to modify these Terms of Service at any time. We will notify you of any changes by posting the new terms on this page.</p>");
    }

    public function save()
    {
        try {
            Setting::set('terms_of_service_content', $this->terms_of_service_content, 'textarea', 'legal');
            Setting::clearCache();

            $this->dispatch('saved');
            session()->flash('success', 'Terms of Service content saved successfully!');
        } catch (\Exception $e) {
            \Log::error('Error saving terms of service content', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'There was an error saving the terms of service content. Please try again.');
        }
    }
}; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Terms of Service Content</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage the content of your terms of service page</p>
        </div>
    </div>

    <div class="max-w-4xl">
        <form wire:submit.prevent="save" class="space-y-8">
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Terms of Service Content</h2>
                
                <div class="mb-4">
                    <flux:textarea 
                        wire:model="terms_of_service_content" 
                        label="Terms of Service Content" 
                        placeholder="Enter your terms of service content here..."
                        rows="15"
                        richtext
                    />
                </div>
                
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    <p>You can use HTML tags to format your content. The terms of service page will display this content.</p>
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
            Terms of Service content saved successfully!
        </div>
    </div>
</div>