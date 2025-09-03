<?php

use App\Models\Story;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    public Story $story;

    public function mount(Story $story)
    {
        $this->story = $story;
    }

    public function toggleFeatured()
    {
        $this->story->update(['featured' => !$this->story->featured]);
        session()->flash('success', 'Featured status updated successfully!');
    }

    public function togglePublished()
    {
        $this->story->update([
            'published_at' => $this->story->published_at ? null : now()
        ]);
        session()->flash('success', 'Publication status updated successfully!');
    }

    public function deleteStory()
    {
        $this->story->delete();
        return redirect()->route('admin.stories.index')
                        ->with('success', 'Story deleted successfully.');
    }

    public function getCategoryColorProperty()
    {
        return match($this->story->category) {
            'rescue' => 'text-blue-600 bg-blue-100 dark:text-blue-400 dark:bg-blue-900',
            'adoption' => 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900',
            'news' => 'text-purple-600 bg-purple-100 dark:text-purple-400 dark:bg-purple-900',
            default => 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-900',
        };
    }
}; ?>

<div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $story->title }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ ucfirst($story->category) }} Story
                        @if($story->published_at)
                            • Published {{ $story->published_at->format('M j, Y') }}
                        @else
                            • Draft
                        @endif
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <flux:button href="{{ route('admin.stories.edit', $story) }}" variant="primary" size="sm">
                        <flux:icon.pencil class="w-4 h-4 mr-2" />
                        Edit Story
                    </flux:button>
                    <flux:dropdown>
                        <flux:button variant="ghost" size="sm">
                            <flux:icon.ellipsis-horizontal class="w-4 h-4" />
                        </flux:button>
                        <flux:menu>
                            <flux:menu.item wire:click="toggleFeatured">
                                <flux:icon.star class="w-4 h-4 mr-2" />
                                {{ $story->featured ? 'Remove from Featured' : 'Mark as Featured' }}
                            </flux:menu.item>
                            <flux:menu.item wire:click="togglePublished">
                                @if($story->published_at)
                                    <flux:icon.eye-slash class="w-4 h-4 mr-2" />
                                    Unpublish Story
                                @else
                                    <flux:icon.eye class="w-4 h-4 mr-2" />
                                    Publish Story
                                @endif
                            </flux:menu.item>
                            <flux:menu.separator />
                            <flux:menu.item 
                                wire:click="deleteStory" 
                                wire:confirm="Are you sure you want to delete '{{ $story->title }}'? This action cannot be undone."
                            >
                                <flux:icon.trash class="w-4 h-4 mr-2" />
                                Delete Story
                            </flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>
        </div>

        <!-- Back Link -->
        <div class="mb-6">
            <flux:button href="{{ route('admin.stories.index') }}" variant="ghost" size="sm">
                <flux:icon.arrow-left class="w-4 h-4 mr-2" />
                Back to Stories
            </flux:button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Featured Image -->
                @if($story->featured_image)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden">
                        @if($story->featured_image_url)
                            <img 
                                src="{{ $story->featured_image_url }}" 
                                alt="{{ $story->title }}"
                                class="w-full h-64 object-cover"
                            >
                        @else
                            <div class="w-full h-64 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <div class="text-center">
                                    <flux:icon.photo class="w-12 h-12 text-gray-400 mx-auto mb-2" />
                                    <p class="text-gray-500 text-sm">No featured image</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Story Header -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $this->categoryColor }}">
                            {{ ucfirst($story->category) }}
                        </span>
                        @if($story->featured)
                            <span class="px-3 py-1 text-sm font-medium bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-400 rounded-full">
                                Featured
                            </span>
                        @endif
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $story->title }}</h2>
                    
                    <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">{{ $story->excerpt }}</p>
                </div>

                <!-- Story Content -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Full Story</h3>
                    <div class="prose dark:prose-invert max-w-none">
                        <div class="text-gray-900 dark:text-white whitespace-pre-wrap leading-relaxed">{{ $story->content }}</div>
                    </div>
                </div>

                <!-- Public Story Link -->
                @if($story->published_at)
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100">Public Story</h3>
                                <p class="text-sm text-blue-800 dark:text-blue-200">View how this story appears to visitors</p>
                            </div>
                            <flux:button href="{{ route('stories.show', $story) }}" variant="outline" size="sm" target="_blank">
                                <flux:icon.arrow-top-right-on-square class="w-4 h-4 mr-2" />
                                View Public Story
                            </flux:button>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800 p-4">
                        <div class="flex items-center">
                            <flux:icon.exclamation-triangle class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-3" />
                            <div>
                                <h3 class="text-sm font-medium text-yellow-900 dark:text-yellow-100">Draft Story</h3>
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">This story is not yet published and won't be visible to visitors.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Story Information -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Story Information</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Title</label>
                            <p class="text-gray-900 dark:text-white">{{ $story->title }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">URL Slug</label>
                            <p class="text-gray-900 dark:text-white font-mono text-sm">{{ $story->slug }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Category</label>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $this->categoryColor }}">
                                {{ ucfirst($story->category) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Status</label>
                            @if($story->published_at)
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400 rounded-full">
                                    Published
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-400 rounded-full">
                                    Draft
                                </span>
                            @endif
                        </div>

                        @if($story->featured)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Featured</label>
                                <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-400 rounded-full">
                                    Featured Story
                                </span>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Created</label>
                            <p class="text-gray-900 dark:text-white">{{ $story->created_at->format('M j, Y') }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $story->created_at->format('g:i A') }}</p>
                        </div>

                        @if($story->published_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Published</label>
                                <p class="text-gray-900 dark:text-white">{{ $story->published_at->format('M j, Y') }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $story->published_at->format('g:i A') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                    
                    <div class="space-y-3">
                        <flux:button href="{{ route('admin.stories.edit', $story) }}" variant="outline" class="w-full justify-start">
                            <flux:icon.pencil class="w-4 h-4 mr-2" />
                            Edit Story
                        </flux:button>

                        <flux:button wire:click="toggleFeatured" variant="outline" class="w-full justify-start">
                            <flux:icon.star class="w-4 h-4 mr-2" />
                            {{ $story->featured ? 'Remove from Featured' : 'Mark as Featured' }}
                        </flux:button>

                        <flux:button wire:click="togglePublished" variant="outline" class="w-full justify-start">
                            @if($story->published_at)
                                <flux:icon.eye-slash class="w-4 h-4 mr-2" />
                                Unpublish Story
                            @else
                                <flux:icon.eye class="w-4 h-4 mr-2" />
                                Publish Story
                            @endif
                        </flux:button>

                        @if($story->published_at)
                            <flux:button href="{{ route('stories.show', $story) }}" variant="outline" class="w-full justify-start" target="_blank">
                                <flux:icon.arrow-top-right-on-square class="w-4 h-4 mr-2" />
                                View Public Story
                            </flux:button>
                        @endif

                        <hr class="border-gray-200 dark:border-zinc-700">

                        <flux:button 
                            wire:click="deleteStory" 
                            wire:confirm="Are you sure you want to delete '{{ $story->title }}'? This action cannot be undone."
                            variant="danger" 
                            class="w-full justify-start"
                        >
                            <flux:icon.trash class="w-4 h-4 mr-2" />
                            Delete Story
                        </flux:button>
                    </div>
                </div>

                <!-- Story Statistics -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Story Statistics</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Word Count</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ str_word_count($story->content) }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Character Count</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ strlen($story->content) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Excerpt Length</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ strlen($story->excerpt) }}/500</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Has Featured Image</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $story->featured_image ? 'Yes' : 'No' }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Last Updated</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $story->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>