<?php

use App\Models\Story;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';
    public string $status = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleFeatured($storyId)
    {
        $story = Story::findOrFail($storyId);
        $story->update(['featured' => !$story->featured]);
        
        $this->dispatch('story-updated');
    }

    public function togglePublished($storyId)
    {
        $story = Story::findOrFail($storyId);
        $story->update([
            'published_at' => $story->published_at ? null : now()
        ]);
        
        $this->dispatch('story-updated');
    }

    public function deleteStory($storyId)
    {
        Story::findOrFail($storyId)->delete();
        
        $this->dispatch('story-deleted');
    }

    public function with()
    {
        $query = Story::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category) {
            $query->where('category', $this->category);
        }

        if ($this->status === 'published') {
            $query->whereNotNull('published_at');
        } elseif ($this->status === 'draft') {
            $query->whereNull('published_at');
        }

        $stories = $query->orderBy($this->sortBy, $this->sortDirection)
                        ->paginate(15);

        $stats = [
            'total' => Story::count(),
            'published' => Story::whereNotNull('published_at')->count(),
            'drafts' => Story::whereNull('published_at')->count(),
            'featured' => Story::where('featured', true)->count(),
        ];

        return compact('stories', 'stats');
    }
}; ?>

<div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Stories Management</h1>
                    <p class="text-gray-600 dark:text-gray-400">Manage rescue stories and blog posts</p>
                </div>
                <flux:button href="{{ route('admin.stories.create') }}" variant="primary">
                    <flux:icon.plus class="w-4 h-4 mr-2" />
                    Create New Story
                </flux:button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <flux:icon.document-text class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Stories</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                        <flux:icon.eye class="w-5 h-5 text-green-600 dark:text-green-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Published</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['published'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <flux:icon.document class="w-5 h-5 text-yellow-600 dark:text-yellow-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Drafts</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['drafts'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                        <flux:icon.star class="w-5 h-5 text-orange-600 dark:text-orange-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Featured</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['featured'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <flux:field>
                        <flux:label>Search Stories</flux:label>
                        <flux:input 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Search by title, excerpt, or content..."
                        />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Filter by Category</flux:label>
                        <flux:select wire:model.live="category">
                            <option value="">All Categories</option>
                            <option value="rescue">Rescue Stories</option>
                            <option value="adoption">Adoption Stories</option>
                            <option value="news">News & Updates</option>
                        </flux:select>
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Filter by Status</flux:label>
                        <flux:select wire:model.live="status">
                            <option value="">All Statuses</option>
                            <option value="published">Published</option>
                            <option value="draft">Drafts</option>
                        </flux:select>
                    </flux:field>
                </div>

                <div class="flex items-end">
                    <flux:button wire:click="$set('search', '')" variant="ghost" class="w-full">
                        <flux:icon.x-mark class="w-4 h-4 mr-2" />
                        Clear Filters
                    </flux:button>
                </div>
            </div>
        </div>

        <!-- Stories Table -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-zinc-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <button wire:click="sortBy('title')" class="flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                    Story
                                    @if($sortBy === 'title')
                                        <flux:icon.chevron-up class="w-4 h-4 ml-1 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" />
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Category
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <button wire:click="sortBy('published_at')" class="flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                    Published
                                    @if($sortBy === 'published_at')
                                        <flux:icon.chevron-up class="w-4 h-4 ml-1 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" />
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse($stories as $story)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($story->featured_image_url)
                                            <img 
                                                src="{{ $story->featured_image_url }}" 
                                                alt="{{ $story->title }}"
                                                class="w-12 h-12 object-cover rounded-lg mr-4"
                                            >
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 dark:bg-zinc-600 rounded-lg mr-4 flex items-center justify-center">
                                                <flux:icon.photo class="w-6 h-6 text-gray-400 dark:text-zinc-500" />
                                            </div>
                                        @endif
                                        <div>
                                            <div class="flex items-center">
                                                <div class="font-medium text-gray-900 dark:text-white">{{ $story->title }}</div>
                                                @if($story->featured)
                                                    <flux:icon.star class="w-4 h-4 text-orange-500 ml-2" />
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-xs">
                                                {{ Str::limit($story->excerpt, 60) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $categoryColor = match($story->category) {
                                            'rescue' => 'text-blue-600 bg-blue-100 dark:text-blue-400 dark:bg-blue-900',
                                            'adoption' => 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900',
                                            'news' => 'text-purple-600 bg-purple-100 dark:text-purple-400 dark:bg-purple-900',
                                            default => 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-900',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $categoryColor }}">
                                        {{ ucfirst($story->category) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($story->published_at)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900">
                                            Published
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full text-yellow-600 bg-yellow-100 dark:text-yellow-400 dark:bg-yellow-900">
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    @if($story->published_at)
                                        {{ $story->published_at->format('M j, Y') }}
                                        <div class="text-xs">{{ $story->published_at->format('g:i A') }}</div>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">Not published</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <flux:button 
                                            href="{{ route('admin.stories.show', $story) }}" 
                                            variant="ghost" 
                                            size="sm"
                                        >
                                            <flux:icon.eye class="w-4 h-4" />
                                        </flux:button>
                                        
                                        <flux:dropdown>
                                            <flux:button variant="ghost" size="sm">
                                                <flux:icon.ellipsis-horizontal class="w-4 h-4" />
                                            </flux:button>
                                            <flux:menu>
                                                <flux:menu.item href="{{ route('admin.stories.edit', $story) }}">
                                                    <flux:icon.pencil class="w-4 h-4 mr-2" />
                                                    Edit Story
                                                </flux:menu.item>
                                                <flux:menu.separator />
                                                <flux:menu.item wire:click="toggleFeatured({{ $story->id }})">
                                                    <flux:icon.star class="w-4 h-4 mr-2" />
                                                    {{ $story->featured ? 'Remove from Featured' : 'Mark as Featured' }}
                                                </flux:menu.item>
                                                <flux:menu.item wire:click="togglePublished({{ $story->id }})">
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
                                                    wire:click="deleteStory({{ $story->id }})" 
                                                    wire:confirm="Are you sure you want to delete '{{ $story->title }}'? This action cannot be undone."
                                                >
                                                    <flux:icon.trash class="w-4 h-4 mr-2" />
                                                    Delete Story
                                                </flux:menu.item>
                                            </flux:menu>
                                        </flux:dropdown>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <flux:icon.document-text class="w-12 h-12 mx-auto mb-4" />
                                        <h3 class="text-lg font-medium mb-2">No stories found</h3>
                                        <p class="text-sm mb-4">
                                            @if($search || $category || $status)
                                                Try adjusting your search criteria or filters.
                                            @else
                                                Get started by creating your first rescue story.
                                            @endif
                                        </p>
                                        <flux:button href="{{ route('admin.stories.create') }}" variant="primary">
                                            <flux:icon.plus class="w-4 h-4 mr-2" />
                                            Create First Story
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($stories->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
                    {{ $stories->links() }}
                </div>
            @endif
        </div>
</div>