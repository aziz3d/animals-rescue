<?php

use App\Models\Setting;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    public $animals_default_status = '';
    public $animals_max_images = '';
    public $animals_enable_favorites = false;
    public $animals_auto_publish = false;
    public $animals_per_page = '';
    public $animals_homepage_count = '';
    public $animals_homepage_title = '';
    public $animals_homepage_description = '';

    public function mount()
    {
        $this->animals_default_status = Setting::get('animals_default_status', 'available');
        $this->animals_max_images = Setting::get('animals_max_images', '10');
        $this->animals_enable_favorites = (bool) Setting::get('animals_enable_favorites', '1');
        $this->animals_auto_publish = (bool) Setting::get('animals_auto_publish', '0');
        $this->animals_per_page = Setting::get('animals_per_page', '12');
        $this->animals_homepage_count = Setting::get('animals_homepage_count', '6');
        $this->animals_homepage_title = Setting::get('animals_homepage_title', 'Meet Our Featured Friends');
        $this->animals_homepage_description = Setting::get('animals_homepage_description', 'These amazing animals are looking for their forever homes. Could one of them be perfect for your family?');
    }

    public function save()
    {
        $this->validate([
            'animals_default_status' => 'required|in:available,pending,adopted',
            'animals_max_images' => 'required|integer|min:1|max:50',
            'animals_per_page' => 'required|integer|min:1|max:100',
            'animals_homepage_count' => 'required|integer|min:1|max:20',
            'animals_homepage_title' => 'required|string|max:255',
            'animals_homepage_description' => 'required|string|max:500',
        ]);

        Setting::set('animals_default_status', $this->animals_default_status, 'select', 'animals');
        Setting::set('animals_max_images', $this->animals_max_images, 'number', 'animals');
        Setting::set('animals_enable_favorites', $this->animals_enable_favorites ? '1' : '0', 'boolean', 'animals');
        Setting::set('animals_auto_publish', $this->animals_auto_publish ? '1' : '0', 'boolean', 'animals');
        Setting::set('animals_per_page', $this->animals_per_page, 'number', 'animals');
        Setting::set('animals_homepage_count', $this->animals_homepage_count, 'number', 'animals');
        Setting::set('animals_homepage_title', $this->animals_homepage_title, 'text', 'animals');
        Setting::set('animals_homepage_description', $this->animals_homepage_description, 'textarea', 'animals');

        Setting::clearCache();
        
        $this->dispatch('saved');
        session()->flash('message', 'Animals settings saved successfully!');
    }
    
    public function toJSON()
    {
        return json_encode([
            'component' => 'admin.settings.animals',
            'id' => $this->getId()
        ]);
    }
}; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Animals Settings</h1>
            <p class="text-gray-600 dark:text-gray-400">Configure animal management settings</p>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('message'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif
    
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Animal Configuration</h2>
        
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:field>
                    <flux:label>Default Animal Status</flux:label>
                    <flux:description>Default status for newly added animals</flux:description>
                    <select wire:model="animals_default_status" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select default status</option>
                        <option value="available">Available</option>
                        <option value="pending">Pending</option>
                        <option value="adopted">Adopted</option>
                    </select>
                    @error('animals_default_status')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div>
                <flux:field>
                    <flux:label>Maximum Images per Animal</flux:label>
                    <flux:description>Maximum number of images that can be uploaded per animal</flux:description>
                    <input type="number" wire:model="animals_max_images" placeholder="10" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('animals_max_images')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div>
                <flux:field>
                    <flux:label>Animals per Page</flux:label>
                    <flux:description>Number of animals to display per page on the public listing</flux:description>
                    <input type="number" wire:model="animals_per_page" placeholder="12" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('animals_per_page')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div>
                <flux:field>
                    <flux:label>Homepage Featured Animals</flux:label>
                    <flux:description>Number of featured animals to display on the homepage</flux:description>
                    <input type="number" wire:model="animals_homepage_count" placeholder="6" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('animals_homepage_count')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div>
                <flux:field>
                    <flux:label>Homepage Featured Section Title</flux:label>
                    <flux:description>Title for the featured animals section on homepage</flux:description>
                    <input type="text" wire:model="animals_homepage_title" placeholder="Meet Our Featured Friends" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('animals_homepage_title')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div>
                <flux:field>
                    <flux:label>Homepage Featured Section Description</flux:label>
                    <flux:description>Description for the featured animals section on homepage</flux:description>
                    <textarea wire:model="animals_homepage_description" placeholder="These amazing animals are looking for their forever homes. Could one of them be perfect for your family?" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
                    @error('animals_homepage_description')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <flux:label>Enable Animal Favorites</flux:label>
                    <flux:description>Allow users to favorite animals (future feature)</flux:description>
                </div>
                <input type="checkbox" wire:model="animals_enable_favorites" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <flux:label>Auto-publish New Animals</flux:label>
                    <flux:description>Automatically make new animals visible to public</flux:description>
                </div>
                <input type="checkbox" wire:model="animals_auto_publish" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-zinc-700">
                <flux:button type="submit" variant="primary">Save Settings</flux:button>
            </div>
        </form>
    </div>
</div>