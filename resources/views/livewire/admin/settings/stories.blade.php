<?php

use App\Models\Setting;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    public $stories_per_page = '';
    public $stories_enable_comments = false;
    public $stories_auto_publish = false;
    public $stories_featured_limit = '';
    public $stories_require_image = false;
    public $stories_homepage_title = '';
    public $stories_homepage_description = '';
    public $stories_homepage_count = '';

    public function mount()
    {
        $this->stories_per_page = Setting::get('stories_per_page', '12');
        $this->stories_enable_comments = (bool) Setting::get('stories_enable_comments', '0');
        $this->stories_auto_publish = (bool) Setting::get('stories_auto_publish', '0');
        $this->stories_featured_limit = Setting::get('stories_featured_limit', '5');
        $this->stories_require_image = (bool) Setting::get('stories_require_image', '1');
        $this->stories_homepage_title = Setting::get('stories_homepage_title', 'Success Stories');
        $this->stories_homepage_description = Setting::get('stories_homepage_description', 'Read heartwarming stories of rescue, recovery, and the joy of finding forever homes.');
        $this->stories_homepage_count = Setting::get('stories_homepage_count', '6');
    }

    public function save()
    {
        $this->validate([
            'stories_per_page' => 'required|integer|min:1|max:100',
            'stories_featured_limit' => 'required|integer|min:1|max:50',
            'stories_homepage_count' => 'required|integer|min:1|max:20',
            'stories_homepage_title' => 'required|string|max:255',
            'stories_homepage_description' => 'required|string|max:500',
        ]);

        Setting::set('stories_per_page', $this->stories_per_page, 'number', 'stories');
        Setting::set('stories_enable_comments', $this->stories_enable_comments ? '1' : '0', 'boolean', 'stories');
        Setting::set('stories_auto_publish', $this->stories_auto_publish ? '1' : '0', 'boolean', 'stories');
        Setting::set('stories_featured_limit', $this->stories_featured_limit, 'number', 'stories');
        Setting::set('stories_require_image', $this->stories_require_image ? '1' : '0', 'boolean', 'stories');
        Setting::set('stories_homepage_title', $this->stories_homepage_title, 'text', 'stories');
        Setting::set('stories_homepage_description', $this->stories_homepage_description, 'textarea', 'stories');
        Setting::set('stories_homepage_count', $this->stories_homepage_count, 'number', 'stories');

        Setting::clearCache();
        
        $this->dispatch('saved');
        session()->flash('message', 'Stories settings saved successfully!');
    }
    
    public function toJSON()
    {
        return json_encode([
            'component' => 'admin.settings.stories',
            'id' => $this->getId()
        ]);
    }
}; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Stories Settings</h1>
            <p class="text-gray-600 dark:text-gray-400">Configure story publication and display settings</p>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('message'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif
    
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Story Configuration</h2>
        
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:field>
                    <flux:label>Stories per Page</flux:label>
                    <flux:description>Number of stories to display per page on the public listing</flux:description>
                    <input type="number" wire:model="stories_per_page" placeholder="12" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('stories_per_page')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div>
                <flux:field>
                    <flux:label>Featured Stories Limit</flux:label>
                    <flux:description>Maximum number of stories that can be featured at once</flux:description>
                    <input type="number" wire:model="stories_featured_limit" placeholder="5" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('stories_featured_limit')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <flux:label>Enable Story Comments</flux:label>
                    <flux:description>Allow users to comment on stories</flux:description>
                </div>
                <input type="checkbox" wire:model="stories_enable_comments" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <flux:label>Auto-publish New Stories</flux:label>
                    <flux:description>Automatically publish new stories when created</flux:description>
                </div>
                <input type="checkbox" wire:model="stories_auto_publish" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <flux:label>Require Story Images</flux:label>
                    <flux:description>Require at least one image for each story</flux:description>
                </div>
                <input type="checkbox" wire:model="stories_require_image" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
            </div>

            <div class="pt-6 border-t border-gray-200 dark:border-zinc-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Homepage Stories Section</h3>
                
                <div>
                    <flux:field>
                        <flux:label>Homepage Stories Count</flux:label>
                        <flux:description>Number of stories to display on the homepage</flux:description>
                        <input type="number" wire:model="stories_homepage_count" placeholder="6" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('stories_homepage_count')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Homepage Stories Title</flux:label>
                        <flux:description>Title for the stories section on homepage</flux:description>
                        <input type="text" wire:model="stories_homepage_title" placeholder="Success Stories" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('stories_homepage_title')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Homepage Stories Description</flux:label>
                        <flux:description>Description for the stories section on homepage</flux:description>
                        <textarea wire:model="stories_homepage_description" placeholder="Read heartwarming stories of rescue, recovery, and the joy of finding forever homes." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
                        @error('stories_homepage_description')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-zinc-700">
                <flux:button type="submit" variant="primary">Save Settings</flux:button>
            </div>
        </form>
    </div>
</div>