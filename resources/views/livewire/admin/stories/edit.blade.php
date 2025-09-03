<?php

use App\Models\Story;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    use WithFileUploads;

    public Story $story;
    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public string $excerpt = '';
    public string $category = '';
    public bool $featured = false;
    public $featured_image;
    public string $current_featured_image = '';
    public $published_at = '';

    public function mount(Story $story)
    {
        $this->story = $story;
        $this->title = $story->title;
        $this->slug = $story->slug;
        $this->content = $story->content;
        $this->excerpt = $story->excerpt ?? '';
        $this->category = $story->category ?? '';
        $this->featured = $story->featured;
        $this->current_featured_image = $story->featured_image ?? '';
        $this->published_at = $story->published_at ? $story->published_at->format('Y-m-d\TH:i') : '';
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:stories,slug,' . $this->story->id,
            'content' => 'required|string|min:100',
            'excerpt' => 'required|string|max:500',
            'category' => 'required|in:rescue,adoption,news',
            'featured' => 'boolean',
            'featured_image' => 'nullable|image|max:2048',
            'published_at' => 'nullable|date',
        ];
    }

    public function save()
    {
        \Log::info('Edit story save method called', [
            'story_id' => $this->story->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => substr($this->content, 0, 50) . '...',
            'category' => $this->category,
            'featured' => $this->featured
        ]);
        
        try {
            $this->validate();
            \Log::info('Edit validation passed');
        } catch (\Exception $e) {
            \Log::error('Edit validation failed: ' . $e->getMessage());
            throw $e;
        }

        // Handle featured image upload
        $featuredImagePath = $this->current_featured_image;
        if ($this->featured_image) {
            try {
                // Delete old image if exists
                if ($this->current_featured_image) {
                    if (file_exists(public_path('storage/' . $this->current_featured_image))) {
                        unlink(public_path('storage/' . $this->current_featured_image));
                    } elseif (file_exists(public_path('uploads/' . $this->current_featured_image))) {
                        unlink(public_path('uploads/' . $this->current_featured_image));
                    }
                }
                
                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $this->featured_image->getClientOriginalExtension();
                $featuredImagePath = 'stories/' . $filename;
                
                // Ensure the stories directory exists
                $uploadsDir = public_path('uploads/stories');
                if (!file_exists($uploadsDir)) {
                    mkdir($uploadsDir, 0755, true);
                }
                
                // Store file using the public disk (which points to public/uploads)
                $storedPath = $this->featured_image->storeAs('stories', $filename, 'public');
                
                // Verify the file was actually stored
                $fullPath = public_path('uploads/' . $featuredImagePath);
                if (file_exists($fullPath)) {
                    \Log::info('Story image updated successfully', [
                        'path' => $featuredImagePath,
                        'full_path' => $fullPath,
                        'url' => asset('uploads/' . $featuredImagePath),
                        'file_size' => filesize($fullPath)
                    ]);
                } else {
                    \Log::error('Story image upload failed - file not found after storage', [
                        'expected_path' => $fullPath,
                        'stored_path' => $storedPath
                    ]);
                    session()->flash('error', 'Failed to upload image: File not saved properly');
                    return;
                }
            } catch (\Exception $e) {
                \Log::error('Story image upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                session()->flash('error', 'Failed to upload image: ' . $e->getMessage());
                return;
            }
        }

        // Update the story
        $this->story->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'category' => $this->category,
            'featured' => $this->featured,
            'featured_image' => $featuredImagePath,
            'published_at' => $this->published_at ? \Carbon\Carbon::parse($this->published_at) : null,
        ]);

        session()->flash('success', "Story '{$this->story->title}' has been updated successfully!");

        return redirect()->route('admin.stories.show', $this->story);
    }

    public function removeFeaturedImage()
    {
        if ($this->current_featured_image && file_exists(public_path('uploads/' . $this->current_featured_image))) {
            unlink(public_path('uploads/' . $this->current_featured_image));
        }
        
        $this->current_featured_image = '';
    }

    public function generateSlug()
    {
        $this->slug = \Illuminate\Support\Str::slug($this->title);
    }
}; ?>

<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Story</h1>
                <p class="text-gray-600 dark:text-gray-400">Update story information</p>
            </div>
        </div>
    </div>

    <!-- Back Link -->
    <div class="mb-6">
        <flux:button href="{{ route('admin.stories.show', $story) }}" variant="ghost" size="sm">
            <flux:icon.arrow-left class="w-4 h-4 mr-2" />
            Back to Story
        </flux:button>
    </div>

    <form wire:submit="save" onsubmit="console.log('Edit form submitted')">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <flux:field>
                                <flux:label>Title</flux:label>
                                <flux:input wire:model.live="title" placeholder="Enter story title" required />
                                <flux:error name="title" />
                            </flux:field>
                        </div>

                        <div>
                            <flux:field>
                                <flux:label>Slug</flux:label>
                                <div class="flex gap-2">
                                    <flux:input wire:model="slug" placeholder="story-url-slug" required class="flex-1" />
                                    <flux:button type="button" wire:click="generateSlug" variant="outline" size="sm">
                                        Generate
                                    </flux:button>
                                </div>
                                <flux:description>URL-friendly version of the title</flux:description>
                                <flux:error name="slug" />
                            </flux:field>
                        </div>

                        <div>
                            <flux:field>
                                <flux:label>Category</flux:label>
                                <flux:select wire:model="category" required>
                                    <option value="">Select Category</option>
                                    <option value="rescue">Rescue Story</option>
                                    <option value="adoption">Adoption Story</option>
                                    <option value="news">News & Updates</option>
                                </flux:select>
                                <flux:error name="category" />
                            </flux:field>
                        </div>

                        <div>
                            <flux:field>
                                <flux:label>Excerpt</flux:label>
                                <flux:textarea 
                                    wire:model="excerpt" 
                                    rows="3" 
                                    placeholder="Brief summary of the story..."
                                    required
                                />
                                <flux:description>Maximum 500 characters. This appears in story previews and social sharing.</flux:description>
                                <flux:error name="excerpt" />
                            </flux:field>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Story Content</h2>
                    
                    <div>
                        <flux:field>
                            <flux:label>Content</flux:label>
                            <flux:textarea 
                                wire:model="content" 
                                rows="12" 
                                placeholder="Write your story here..."
                                required
                            />
                            <flux:description>Minimum 100 characters. Use markdown for formatting.</flux:description>
                            <flux:error name="content" />
                        </flux:field>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Featured Image</h2>
                    
                    <div class="space-y-4">
                        @if($current_featured_image)
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Current Image</h3>
                                <div class="relative inline-block">
                                    @if(file_exists(public_path('uploads/' . $current_featured_image)))
                                        <img 
                                            src="{{ asset('uploads/' . $current_featured_image) }}" 
                                            alt="Current featured image" 
                                            class="w-48 h-32 object-cover rounded-lg border border-gray-200 dark:border-zinc-700"
                                        >
                                    @else
                                        <div class="w-48 h-32 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-zinc-700 flex items-center justify-center">
                                            <div class="text-center">
                                                <flux:icon.photo class="w-6 h-6 text-gray-400 mx-auto mb-1" />
                                                <p class="text-gray-500 text-xs">Image not found</p>
                                            </div>
                                        </div>
                                    @endif
                                    <button 
                                        type="button"
                                        wire:click="removeFeaturedImage"
                                        wire:confirm="Are you sure you want to remove this image?"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                                    >
                                        <flux:icon.x-mark class="w-3 h-3" />
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div>
                            <flux:field>
                                <flux:label>Upload New Image</flux:label>
                                <flux:input type="file" wire:model="featured_image" accept="image/*" />
                                <flux:description>Upload a new featured image. Maximum 2MB.</flux:description>
                                <flux:error name="featured_image" />
                            </flux:field>
                        </div>

                        @if($featured_image)
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">New Image Preview</h3>
                                @try
                                    <img 
                                        src="{{ $featured_image->temporaryUrl() }}" 
                                        alt="New image preview" 
                                        class="w-48 h-32 object-cover rounded-lg border border-gray-200 dark:border-zinc-700"
                                    >
                                @catch(\Exception $e)
                                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-red-600 text-sm">Unable to preview image. File will be uploaded when you save.</p>
                                    </div>
                                @endtry
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publishing -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Publishing</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <flux:field>
                                <flux:label>Publish Date & Time</flux:label>
                                <flux:input type="datetime-local" wire:model="published_at" />
                                <flux:description>Leave empty to save as draft</flux:description>
                                <flux:error name="published_at" />
                            </flux:field>
                        </div>

                        <div>
                            <flux:field>
                                <flux:checkbox wire:model="featured">Featured Story</flux:checkbox>
                                <flux:description>Featured stories appear prominently on the homepage.</flux:description>
                                <flux:error name="featured" />
                            </flux:field>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h2>
                    
                    <div class="space-y-3">
                        <flux:button type="submit" variant="primary" class="w-full" onclick="console.log('Edit button clicked')">
                            <flux:icon.check class="w-4 h-4 mr-2" />
                            Save Changes
                        </flux:button>

                        <flux:button href="{{ route('admin.stories.show', $story) }}" variant="ghost" class="w-full">
                            <flux:icon.x-mark class="w-4 h-4 mr-2" />
                            Cancel
                        </flux:button>
                    </div>
                </div>

                <!-- Story Info -->
                <div class="bg-gray-50 dark:bg-zinc-700 rounded-lg border border-gray-200 dark:border-zinc-600 p-4">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Story Information</h3>
                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <p>Created: {{ $story->created_at->format('M j, Y') }}</p>
                        <p>Last Updated: {{ $story->updated_at->format('M j, Y') }}</p>
                        @if($story->published_at)
                            <p>Published: {{ $story->published_at->format('M j, Y g:i A') }}</p>
                        @else
                            <p>Status: Draft</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>