<?php

use App\Models\Setting;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    use WithFileUploads;

    public $site_name = '';
    public $site_description = '';
    public $site_keywords = '';
    public $meta_title = '';
    public $meta_description = '';
    public $logo;
    public $favicon;
    public $current_logo = '';
    public $current_favicon = '';
    
    // Hero Section Settings
    public $hero_background_image;
    public $current_hero_background = '';
    public $hero_title = '';
    public $hero_subtitle = '';
    public $hero_cta1_text = '';
    public $hero_cta1_url = '';
    public $hero_cta2_text = '';
    public $hero_cta2_url = '';
    public $hero_cta3_text = '';
    public $hero_cta3_url = '';
    
    // Ready to Make a Difference Section Settings
    public $cta_title = '';
    public $cta_description = '';
    public $cta_button1_text = '';
    public $cta_button1_url = '';
    public $cta_button2_text = '';
    public $cta_button2_url = '';
    
    // Footer Settings
    public $footer_organization_name = '';
    public $footer_mission_statement = '';
    public $footer_address = '';
    public $footer_phone = '';
    public $footer_email = '';
    public $footer_facebook_url = '';
    public $footer_twitter_url = '';
    public $footer_instagram_url = '';
    public $footer_privacy_policy_url = '';
    public $footer_terms_of_service_url = '';
    public $footer_show_privacy_policy = true;
    public $footer_show_terms_of_service = true;
    public $footer_copyright_text = '';

    public function mount()
    {
        $this->site_name = Setting::get('site_name', 'Lovely Paws Rescue');
        $this->site_description = Setting::get('site_description', 'Helping animals find their forever homes');
        $this->site_keywords = Setting::get('site_keywords', 'animal rescue, pet adoption, dogs, cats, volunteers');
        $this->meta_title = Setting::get('meta_title', 'Lovely Paws Rescue - Animal Rescue & Adoption');
        $this->meta_description = Setting::get('meta_description', 'Find your perfect companion at Lovely Paws Rescue. We help animals find loving homes through our adoption program.');
        $this->current_logo = Setting::get('site_logo', '');
        $this->current_favicon = Setting::get('site_favicon', '');
        
        // Hero Section Settings
        $this->current_hero_background = Setting::get('hero_background_image', '');
        $this->hero_title = Setting::get('hero_title', 'Every Paw Deserves Love');
        $this->hero_subtitle = Setting::get('hero_subtitle', 'Join us in our mission to rescue, rehabilitate, and rehome animals in need. Together, we can make a difference, one paw at a time.');
        $this->hero_cta1_text = Setting::get('hero_cta1_text', 'Adopt a Pet');
        $this->hero_cta1_url = Setting::get('hero_cta1_url', '/animals');
        $this->hero_cta2_text = Setting::get('hero_cta2_text', 'Donate Now');
        $this->hero_cta2_url = Setting::get('hero_cta2_url', '/donate');
        $this->hero_cta3_text = Setting::get('hero_cta3_text', 'Volunteer');
        $this->hero_cta3_url = Setting::get('hero_cta3_url', '/volunteer');
        
        // Ready to Make a Difference Section Settings
        $this->cta_title = Setting::get('cta_title', 'Ready to Make a Difference?');
        $this->cta_description = Setting::get('cta_description', 'Whether you\'re looking to adopt, volunteer, or donate, every action helps us save more lives.');
        $this->cta_button1_text = Setting::get('cta_button1_text', 'Get in Touch');
        $this->cta_button1_url = Setting::get('cta_button1_url', '/contact');
        $this->cta_button2_text = Setting::get('cta_button2_text', 'Start Volunteering');
        $this->cta_button2_url = Setting::get('cta_button2_url', '/volunteer');
        
        // Footer Settings
        $this->footer_organization_name = Setting::get('footer_organization_name', 'Lovely Paws Rescue');
        $this->footer_mission_statement = Setting::get('footer_mission_statement', 'Dedicated to rescuing, rehabilitating, and rehoming animals in need. Every paw deserves love, care, and a forever home.');
        $this->footer_address = Setting::get('footer_address', '123 Rescue Lane, Pet City, PC 12345');
        $this->footer_phone = Setting::get('footer_phone', '(555) 123-PAWS');
        $this->footer_email = Setting::get('footer_email', 'info@lovelypawsrescue.org');
        $this->footer_facebook_url = Setting::get('footer_facebook_url', '#');
        $this->footer_twitter_url = Setting::get('footer_twitter_url', '#');
        $this->footer_instagram_url = Setting::get('footer_instagram_url', '#');
        $this->footer_privacy_policy_url = Setting::get('footer_privacy_policy_url', route('privacy-policy'));
        $this->footer_terms_of_service_url = Setting::get('footer_terms_of_service_url', route('terms-of-service'));
        $this->footer_show_privacy_policy = (bool) Setting::get('footer_show_privacy_policy', true);
        $this->footer_show_terms_of_service = (bool) Setting::get('footer_terms_of_service', true);
        $this->footer_copyright_text = Setting::get('footer_copyright_text', 'All rights reserved.');
    }

    public function save()
    {
        \Log::info('Save method called', ['user_id' => auth()->id()]);
        
        try {
            $this->validate([
                'site_name' => 'required|string|max:255',
                'site_description' => 'required|string|max:500',
                'site_keywords' => 'required|string|max:255',
                'meta_title' => 'required|string|max:255',
                'meta_description' => 'required|string|max:500',
                'logo' => 'nullable|image|max:2048',
                'favicon' => 'nullable|image|max:512',
                'hero_background_image' => 'nullable|image|max:5120',
                'hero_title' => 'required|string|max:255',
                'hero_subtitle' => 'required|string|max:1000',
                'hero_cta1_text' => 'required|string|max:100',
                'hero_cta1_url' => 'required|string|max:255',
                'hero_cta2_text' => 'required|string|max:100',
                'hero_cta2_url' => 'required|string|max:255',
                'hero_cta3_text' => 'required|string|max:100',
                'hero_cta3_url' => 'required|string|max:255',
                'cta_title' => 'required|string|max:255',
                'cta_description' => 'required|string|max:500',
                'cta_button1_text' => 'required|string|max:100',
                'cta_button1_url' => 'required|string|max:255',
                'cta_button2_text' => 'required|string|max:100',
                'cta_button2_url' => 'required|string|max:255',
                'footer_organization_name' => 'required|string|max:255',
                'footer_mission_statement' => 'required|string|max:500',
                'footer_address' => 'required|string|max:255',
                'footer_phone' => 'required|string|max:50',
                'footer_email' => 'required|email|max:255',
                'footer_facebook_url' => 'nullable|url|max:255',
                'footer_twitter_url' => 'nullable|url|max:255',
                'footer_instagram_url' => 'nullable|url|max:255',
                'footer_privacy_policy_url' => 'nullable|url|max:255',
                'footer_terms_of_service_url' => 'nullable|url|max:255',
                'footer_show_privacy_policy' => 'boolean',
                'footer_show_terms_of_service' => 'boolean',
                'footer_copyright_text' => 'required|string|max:255',
            ]);

            // Handle logo upload
            if ($this->logo) {
                // Delete old logo if exists
                if ($this->current_logo && Storage::disk('public')->exists($this->current_logo)) {
                    Storage::disk('public')->delete($this->current_logo);
                }
                
                $logoPath = $this->logo->store('settings', 'public');
                Setting::set('site_logo', $logoPath, 'image', 'general');
                $this->current_logo = $logoPath;
            }

            // Handle favicon upload
            if ($this->favicon) {
                // Delete old favicon if exists
                if ($this->current_favicon && Storage::disk('public')->exists($this->current_favicon)) {
                    Storage::disk('public')->delete($this->current_favicon);
                }
                
                $faviconPath = $this->favicon->store('settings', 'public');
                Setting::set('site_favicon', $faviconPath, 'image', 'general');
                $this->current_favicon = $faviconPath;
            }

            // Handle hero background image upload
            if ($this->hero_background_image) {
                // Delete old hero background if exists
                if ($this->current_hero_background && Storage::disk('public')->exists($this->current_hero_background)) {
                    Storage::disk('public')->delete($this->current_hero_background);
                }
                
                $heroBackgroundPath = $this->hero_background_image->store('settings', 'public');
                Setting::set('hero_background_image', $heroBackgroundPath, 'image', 'general');
                $this->current_hero_background = $heroBackgroundPath;
            }

            // Save other settings
            Setting::set('site_name', $this->site_name, 'text', 'general');
            Setting::set('site_description', $this->site_description, 'textarea', 'general');
            Setting::set('site_keywords', $this->site_keywords, 'text', 'general');
            Setting::set('meta_title', $this->meta_title, 'text', 'general');
            Setting::set('meta_description', $this->meta_description, 'textarea', 'general');
            
            // Save hero section settings
            Setting::set('hero_title', $this->hero_title, 'text', 'general');
            Setting::set('hero_subtitle', $this->hero_subtitle, 'textarea', 'general');
            Setting::set('hero_cta1_text', $this->hero_cta1_text, 'text', 'general');
            Setting::set('hero_cta1_url', $this->hero_cta1_url, 'text', 'general');
            Setting::set('hero_cta2_text', $this->hero_cta2_text, 'text', 'general');
            Setting::set('hero_cta2_url', $this->hero_cta2_url, 'text', 'general');
            Setting::set('hero_cta3_text', $this->hero_cta3_text, 'text', 'general');
            Setting::set('hero_cta3_url', $this->hero_cta3_url, 'text', 'general');
            
            // Save Ready to Make a Difference section settings
            Setting::set('cta_title', $this->cta_title, 'text', 'general');
            Setting::set('cta_description', $this->cta_description, 'textarea', 'general');
            Setting::set('cta_button1_text', $this->cta_button1_text, 'text', 'general');
            Setting::set('cta_button1_url', $this->cta_button1_url, 'text', 'general');
            Setting::set('cta_button2_text', $this->cta_button2_text, 'text', 'general');
            Setting::set('cta_button2_url', $this->cta_button2_url, 'text', 'general');
            
            // Save footer settings
            Setting::set('footer_organization_name', $this->footer_organization_name, 'text', 'general');
            Setting::set('footer_mission_statement', $this->footer_mission_statement, 'textarea', 'general');
            Setting::set('footer_address', $this->footer_address, 'text', 'general');
            Setting::set('footer_phone', $this->footer_phone, 'text', 'general');
            Setting::set('footer_email', $this->footer_email, 'text', 'general');
            Setting::set('footer_facebook_url', $this->footer_facebook_url, 'text', 'general');
            Setting::set('footer_twitter_url', $this->footer_twitter_url, 'text', 'general');
            Setting::set('footer_instagram_url', $this->footer_instagram_url, 'text', 'general');
            Setting::set('footer_privacy_policy_url', $this->footer_privacy_policy_url, 'text', 'general');
            Setting::set('footer_terms_of_service_url', $this->footer_terms_of_service_url, 'text', 'general');
            Setting::set('footer_show_privacy_policy', $this->footer_show_privacy_policy, 'boolean', 'general');
            Setting::set('footer_show_terms_of_service', $this->footer_show_terms_of_service, 'boolean', 'general');
            Setting::set('footer_copyright_text', $this->footer_copyright_text, 'text', 'general');

            // Clear cache
            Setting::clearCache();

            // Reset file inputs
            $this->reset(['logo', 'favicon', 'hero_background_image']);

            $this->dispatch('saved');
            
            // Add a session flash message for better feedback
            session()->flash('success', 'Settings saved successfully!');
            
            \Log::info('Settings saved successfully', ['user_id' => auth()->id()]);
        } catch (\Exception $e) {
            \Log::error('Error saving settings', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Flash an error message
            session()->flash('error', 'There was an error saving the settings. Please try again.');
        }
    }

    public function removeLogo()
    {
        if ($this->current_logo && Storage::disk('public')->exists($this->current_logo)) {
            Storage::disk('public')->delete($this->current_logo);
        }
        
        Setting::set('site_logo', '', 'image', 'general');
        $this->current_logo = '';
        Setting::clearCache();
    }

    public function removeFavicon()
    {
        if ($this->current_favicon && Storage::disk('public')->exists($this->current_favicon)) {
            Storage::disk('public')->delete($this->current_favicon);
        }
        
        Setting::set('site_favicon', '', 'image', 'general');
        $this->current_favicon = '';
        Setting::clearCache();
    }

    public function removeHeroBackground()
    {
        if ($this->current_hero_background && Storage::disk('public')->exists($this->current_hero_background)) {
            Storage::disk('public')->delete($this->current_hero_background);
        }
        
        Setting::set('hero_background_image', '', 'image', 'general');
        $this->current_hero_background = '';
        Setting::clearCache();
    }
}; ?>

<div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">General Settings</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage your site's basic information and branding</p>
            </div>
        </div>

        <div class="max-w-4xl">
            <form wire:submit.prevent="save" class="space-y-8">
                <!-- Site Information -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Site Information</h2>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <flux:input 
                            wire:model="site_name" 
                            label="Site Name" 
                            placeholder="Enter your site name"
                            required 
                        />
                        
                        <flux:textarea 
                            wire:model="site_description" 
                            label="Site Description" 
                            placeholder="Brief description of your site"
                            rows="3"
                            required 
                        />
                        
                        <flux:input 
                            wire:model="site_keywords" 
                            label="Site Keywords" 
                            placeholder="Comma-separated keywords for SEO"
                            required 
                        />
                    </div>
                </div>

                <!-- SEO Meta Data -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">SEO Meta Data</h2>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <flux:input 
                            wire:model="meta_title" 
                            label="Meta Title" 
                            placeholder="SEO title for search engines"
                            required 
                        />
                        
                        <flux:textarea 
                            wire:model="meta_description" 
                            label="Meta Description" 
                            placeholder="SEO description for search engines"
                            rows="3"
                            required 
                        />
                    </div>
                </div>

                <!-- Site Branding -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Site Branding</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo Upload -->
                        <div>
                            <flux:field>
                                <flux:label>Site Logo</flux:label>
                                <flux:description>Upload your main site logo (max 2MB)</flux:description>
                                
                                @if($current_logo)
                                    <div class="mt-2 mb-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                @php
                                                    // Use the setting_asset helper for consistent image URL generation
                                                    $logoImageUrl = setting_asset('site_logo', asset('uploads/' . $current_logo));
                                                    // Fallback to direct asset URL if needed
                                                    if (!$logoImageUrl || !filter_var($logoImageUrl, FILTER_VALIDATE_URL)) {
                                                        $logoImageUrl = asset('uploads/' . $current_logo);
                                                    }
                                                @endphp
                                                <img src="{{ $logoImageUrl }}" alt="Current Logo" class="h-16 w-auto border rounded bg-white"
                                                     onerror="this.src='{{ asset('uploads/' . $current_logo) }}'; this.onerror=null;">
                                                <p class="text-xs text-gray-500 mt-1">Current logo</p>
                                            </div>
                                            <button 
                                                wire:click="removeLogo" 
                                                type="button"
                                                class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded"
                                            >
                                                Remove Logo
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-2 mb-4">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No logo uploaded</p>
                                    </div>
                                @endif
                                
                                <input 
                                    type="file" 
                                    wire:model="logo" 
                                    accept="image/*"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400
                                           file:mr-4 file:py-2 file:px-4
                                           file:rounded-lg file:border-0
                                           file:text-sm file:font-semibold
                                           file:bg-blue-50 file:text-blue-700
                                           hover:file:bg-blue-100
                                           dark:file:bg-blue-900 dark:file:text-blue-300"
                                />
                                
                                @error('logo')
                                    <flux:error>{{ $message }}</flux:error>
                                @enderror
                            </flux:field>
                        </div>

                        <!-- Favicon Upload -->
                        <div>
                            <flux:field>
                                <flux:label>Favicon</flux:label>
                                <flux:description>Upload your site favicon (max 512KB)</flux:description>
                                
                                @if($current_favicon)
                                    <div class="mt-2 mb-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                @php
                                                    // Use the setting_asset helper for consistent image URL generation
                                                    $faviconImageUrl = setting_asset('site_favicon', asset('uploads/' . $current_favicon));
                                                    // Fallback to direct asset URL if needed
                                                    if (!$faviconImageUrl || !filter_var($faviconImageUrl, FILTER_VALIDATE_URL)) {
                                                        $faviconImageUrl = asset('uploads/' . $current_favicon);
                                                    }
                                                @endphp
                                                <img src="{{ $faviconImageUrl }}" alt="Current Favicon" class="h-8 w-8 border rounded bg-white"
                                                     onerror="this.src='{{ asset('uploads/' . $current_favicon) }}'; this.onerror=null;">
                                                <p class="text-xs text-gray-500 mt-1">Current favicon</p>
                                            </div>
                                            <button 
                                                wire:click="removeFavicon" 
                                                type="button"
                                                class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded"
                                            >
                                                Remove Favicon
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-2 mb-4">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No favicon uploaded</p>
                                    </div>
                                @endif
                                
                                <input 
                                    type="file" 
                                    wire:model="favicon" 
                                    accept="image/*"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400
                                           file:mr-4 file:py-2 file:px-4
                                           file:rounded-lg file:border-0
                                           file:text-sm file:font-semibold
                                           file:bg-blue-50 file:text-blue-700
                                           hover:file:bg-blue-100
                                           dark:file:bg-blue-900 dark:file:text-blue-300"
                                />
                                
                                @error('favicon')
                                    <flux:error>{{ $message }}</flux:error>
                                @enderror
                            </flux:field>
                        </div>
                    </div>
                </div>

                <!-- Hero Section Settings -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Homepage Hero Section</h2>
                    
                    <div class="space-y-6">
                        <!-- Hero Background Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hero Background Image</label>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Upload a background image for the hero section (max 5MB)</p>
                            
                            @if($current_hero_background)
                                <div class="mt-2 mb-4">
                                    <div class="relative">
                                        @php
                                            // Use the setting_asset helper for consistent image URL generation
                                            $heroImageUrl = setting_asset('hero_background_image', asset('uploads/' . $current_hero_background));
                                            // Fallback to direct asset URL if needed
                                            if (!$heroImageUrl || !filter_var($heroImageUrl, FILTER_VALIDATE_URL)) {
                                                $heroImageUrl = asset('uploads/' . $current_hero_background);
                                            }
                                        @endphp
                                        <img src="{{ $heroImageUrl }}" alt="Current Hero Background" class="h-32 w-full object-cover border rounded-lg bg-gray-100" 
                                             onerror="this.src='{{ asset('uploads/' . $current_hero_background) }}'; this.onerror=null;">
                                        <div class="absolute top-2 right-2">
                                            <button 
                                                wire:click="removeHeroBackground" 
                                                type="button"
                                                class="bg-red-600 hover:bg-red-700 text-white text-xs px-2 py-1 rounded shadow-lg"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Current hero background image</p>
                                </div>
                            @else
                                <div class="mt-2 mb-4">
                                    <div class="h-32 w-full bg-gradient-to-r from-amber-500 to-orange-500 border rounded-lg flex items-center justify-center">
                                        <p class="text-white text-sm">Default gradient background</p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">No custom background uploaded - using default gradient</p>
                                </div>
                            @endif
                            
                            <input 
                                type="file" 
                                wire:model="hero_background_image" 
                                accept="image/*"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400
                                       file:mr-4 file:py-2 file:px-4
                                       file:rounded-lg file:border-0
                                       file:text-sm file:font-semibold
                                       file:bg-blue-50 file:text-blue-700
                                       hover:file:bg-blue-100
                                       dark:file:bg-blue-900 dark:file:text-blue-300"
                            />
                            
                            @error('hero_background_image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hero Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hero Title</label>
                            <input 
                                type="text" 
                                wire:model="hero_title" 
                                placeholder="Every Paw Deserves Love"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            />
                            @error('hero_title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hero Subtitle -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hero Subtitle</label>
                            <textarea 
                                wire:model="hero_subtitle" 
                                rows="3"
                                placeholder="Join us in our mission to rescue, rehabilitate, and rehome animals in need..."
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            ></textarea>
                            @error('hero_subtitle')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CTA Buttons -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- CTA Button 1 -->
                            <div class="space-y-4 p-4 border border-gray-200 dark:border-zinc-700 rounded-lg">
                                <h4 class="font-medium text-gray-900 dark:text-white">Primary Button</h4>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Button Text</label>
                                    <input 
                                        type="text" 
                                        wire:model="hero_cta1_text" 
                                        placeholder="Adopt a Pet"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    @error('hero_cta1_text')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Button URL</label>
                                    <input 
                                        type="text" 
                                        wire:model="hero_cta1_url" 
                                        placeholder="/animals"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    @error('hero_cta1_url')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- CTA Button 2 -->
                            <div class="space-y-4 p-4 border border-gray-200 dark:border-zinc-700 rounded-lg">
                                <h4 class="font-medium text-gray-900 dark:text-white">Secondary Button</h4>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Button Text</label>
                                    <input 
                                        type="text" 
                                        wire:model="hero_cta2_text" 
                                        placeholder="Donate Now"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    @error('hero_cta2_text')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Button URL</label>
                                    <input 
                                        type="text" 
                                        wire:model="hero_cta2_url" 
                                        placeholder="/donate"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    @error('hero_cta2_url')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- CTA Button 3 -->
                            <div class="space-y-4 p-4 border border-gray-200 dark:border-zinc-700 rounded-lg">
                                <h4 class="font-medium text-gray-900 dark:text-white">Tertiary Button</h4>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Button Text</label>
                                    <input 
                                        type="text" 
                                        wire:model="hero_cta3_text" 
                                        placeholder="Volunteer"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    @error('hero_cta3_text')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Button URL</label>
                                    <input 
                                        type="text" 
                                        wire:model="hero_cta3_url" 
                                        placeholder="/volunteer"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    @error('hero_cta3_url')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ready to Make a Difference Section -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ready to Make a Difference Section</h2>
                    
                    <div class="space-y-6">
                        <!-- CTA Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CTA Title</label>
                            <input 
                                type="text" 
                                wire:model="cta_title" 
                                placeholder="Ready to Make a Difference?"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            />
                            @error('cta_title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CTA Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CTA Description</label>
                            <textarea 
                                wire:model="cta_description" 
                                rows="3"
                                placeholder="Whether you're looking to adopt, volunteer, or donate, every action helps us save more lives."
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            ></textarea>
                            @error('cta_description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CTA Buttons -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- CTA Button 1 -->
                            <div class="space-y-4 p-4 border border-gray-200 dark:border-zinc-700 rounded-lg">
                                <h4 class="font-medium text-gray-900 dark:text-white">Primary Button</h4>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Button Text</label>
                                    <input 
                                        type="text" 
                                        wire:model="cta_button1_text" 
                                        placeholder="Get in Touch"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    @error('cta_button1_text')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Button URL</label>
                                    <input 
                                        type="text" 
                                        wire:model="cta_button1_url" 
                                        placeholder="/contact"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    @error('cta_button1_url')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- CTA Button 2 -->
                            <div class="space-y-4 p-4 border border-gray-200 dark:border-zinc-700 rounded-lg">
                                <h4 class="font-medium text-gray-900 dark:text-white">Secondary Button</h4>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Button Text</label>
                                    <input 
                                        type="text" 
                                        wire:model="cta_button2_text" 
                                        placeholder="Start Volunteering"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    @error('cta_button2_text')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Button URL</label>
                                    <input 
                                        type="text" 
                                        wire:model="cta_button2_url" 
                                        placeholder="/volunteer"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    @error('cta_button2_url')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Settings -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Footer Settings</h2>
                    
                    <div class="space-y-6">
                        <!-- Organization Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Organization Name</label>
                                <input 
                                    type="text" 
                                    wire:model="footer_organization_name" 
                                    placeholder="Lovely Paws Rescue"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                                @error('footer_organization_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                                <input 
                                    type="email" 
                                    wire:model="footer_email" 
                                    placeholder="info@lovelypawsrescue.org"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                                @error('footer_email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Mission Statement -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mission Statement</label>
                            <textarea 
                                wire:model="footer_mission_statement" 
                                rows="3"
                                placeholder="Dedicated to rescuing, rehabilitating, and rehoming animals in need..."
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            ></textarea>
                            @error('footer_mission_statement')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
                                <input 
                                    type="text" 
                                    wire:model="footer_address" 
                                    placeholder="123 Rescue Lane, Pet City, PC 12345"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                                @error('footer_address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                                <input 
                                    type="text" 
                                    wire:model="footer_phone" 
                                    placeholder="(555) 123-PAWS"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                                @error('footer_phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        <div>
                            <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4">Social Media Links</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Facebook URL</label>
                                    <input 
                                        type="url" 
                                        wire:model="footer_facebook_url" 
                                        placeholder="https://facebook.com/yourpage"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    @error('footer_facebook_url')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Twitter URL</label>
                                    <input 
                                        type="url" 
                                        wire:model="footer_twitter_url" 
                                        placeholder="https://twitter.com/yourhandle"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    @error('footer_twitter_url')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Instagram URL</label>
                                    <input 
                                        type="url" 
                                        wire:model="footer_instagram_url" 
                                        placeholder="https://instagram.com/yourhandle"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    @error('footer_instagram_url')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Legal Links -->
                        <div>
                            <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4">Legal Links</h3>
                            
                            <!-- Show/Hide Options -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <flux:checkbox 
                                        wire:model="footer_show_privacy_policy" 
                                        label="Show Privacy Policy link in footer" 
                                    />
                                </div>
                                <div>
                                    <flux:checkbox 
                                        wire:model="footer_show_terms_of_service" 
                                        label="Show Terms of Service link in footer" 
                                    />
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Privacy Policy URL</label>
                                    <input 
                                        type="url" 
                                        wire:model="footer_privacy_policy_url" 
                                        placeholder="https://yoursite.com/privacy"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Leave blank to use the default privacy policy page</p>
                                    @error('footer_privacy_policy_url')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Terms of Service URL</label>
                                    <input 
                                        type="url" 
                                        wire:model="footer_terms_of_service_url" 
                                        placeholder="https://yoursite.com/terms"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Leave blank to use the default terms of service page</p>
                                    @error('footer_terms_of_service_url')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Copyright Text -->
                        <div>
                            <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4">Copyright</h3>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Copyright Text</label>
                                <input 
                                    type="text" 
                                    wire:model="footer_copyright_text" 
                                    placeholder="All rights reserved."
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">This text will appear after the organization name and year in the footer (e.g., "© 2025 Your Organization. All rights reserved.")</p>
                                @error('footer_copyright_text')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
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
                    <button type="submit" wire:loading.attr="disabled" wire:target="save,logo,favicon,hero_background_image" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50">
                        <svg wire:loading.remove class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <svg wire:loading class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove>Save Settings</span>
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
                        Settings saved successfully!
                    </div>
                </div>
            </div>