<?php

use App\Models\Story;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $slug = '';
    public string $excerpt = '';
    public string $content = '';
    public string $category = 'rescue';
    public bool $featured = false;
    public bool $published = false;
    public $featured_image;

    public function updatedTitle()
    {
        $this->slug = Str::slug($this->title);
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:stories,slug',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string|min:100',
            'category' => 'required|in:rescue,adoption,news',
            'featured' => 'boolean',
            'published' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        ];
    }

    public function save()
    {
        \Log::info('Create story save method called', [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => substr($this->content, 0, 50) . '...',
            'category' => $this->category,
            'featured' => $this->featured,
            'published' => $this->published
        ]);
        
        try {
            $this->validate();
            \Log::info('Validation passed');
        } catch (\Exception $e) {
            \Log::error('Validation failed: ' . $e->getMessage());
            throw $e;
        }

        // Handle image upload
        $imagePath = null;
        if ($this->featured_image) {
            try {
                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $this->featured_image->getClientOriginalExtension();
                $imagePath = 'stories/' . $filename;
                
                // Store file using the public disk (which points to storage/app/public)
                $storedPath = $this->featured_image->storeAs('stories', $filename, 'public');
                
                // Verify the file was actually stored
                if (\Storage::disk('public')->exists('stories/' . $filename)) {
                    \Log::info('Story image uploaded successfully', [
                        'path' => $imagePath,
                        'stored_path' => $storedPath,
                        'url' => \Storage::disk('public')->url($imagePath),
                        'file_size' => \Storage::disk('public')->size('stories/' . $filename)
                    ]);
                } else {
                    \Log::error('Story image upload failed - file not found after storage', [
                        'expected_path' => 'stories/' . $filename,
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

        // Create the story
        try {
            $story = Story::create([
                'title' => $this->title,
                'slug' => $this->slug,
                'excerpt' => $this->excerpt,
                'content' => $this->content,
                'category' => $this->category,
                'featured' => $this->featured,
                'featured_image' => $imagePath,
                'published_at' => $this->published ? now() : null,
            ]);

            \Log::info('Story created successfully', [
                'story_id' => $story->id,
                'title' => $story->title,
                'featured_image' => $imagePath
            ]);

            session()->flash('success', "Story '{$story->title}' has been created successfully!");

            return redirect()->route('admin.stories.index');
        } catch (\Exception $e) {
            \Log::error('Failed to create story', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Failed to create story: ' . $e->getMessage());
            return;
        }
    }
}; ?>

<div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Story</h1>
                    <p class="text-gray-600 dark:text-gray-400">Share a rescue story or update with the community</p>
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

        <form wire:submit="save" onsubmit="console.log('Create form submitted')">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Story Details</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <flux:field>
                                    <flux:label>Story Title</flux:label>
                                    <flux:input wire:model.live="title" placeholder="e.g., Buddy's Amazing Recovery Journey" required />
                                    <flux:error name="title" />
                                </flux:field>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:label>URL Slug</flux:label>
                                    <flux:input wire:model="slug" placeholder="buddy-amazing-recovery-journey" required />
                                    <flux:description>This will be used in the story URL. Auto-generated from title.</flux:description>
                                    <flux:error name="slug" />
                                </flux:field>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:label>Story Excerpt</flux:label>
                                    <flux:textarea 
                                        wire:model="excerpt" 
                                        rows="3" 
                                        placeholder="A brief summary of the story that will appear in listings..."
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
                                <flux:label>Full Story</flux:label>
                                <flux:textarea 
                                    wire:model="content" 
                                    rows="12" 
                                    placeholder="Tell the full story here. Share the details of the rescue, recovery, adoption, or update..."
                                    required
                                />
                                <flux:description>Minimum 100 characters. Use line breaks to separate paragraphs.</flux:description>
                                <flux:error name="content" />
                            </flux:field>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Featured Image</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <flux:field>
                                    <flux:label>Upload Featured Image</flux:label>
                                    <flux:input type="file" wire:model="featured_image" accept="image/*" />
                                    <flux:description>Optional. Maximum 2MB. This image will appear at the top of the story.</flux:description>
                                    <flux:error name="featured_image" />
                                </flux:field>
                            </div>

                            <!-- Image Preview -->
                            @if($featured_image)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview</label>
                                    @try
                                        <img 
                                            src="{{ $featured_image->temporaryUrl() }}" 
                                            alt="Featured image preview" 
                                            class="w-full max-w-md h-48 object-cover rounded-lg border border-gray-200 dark:border-zinc-700"
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
                    <!-- Publishing Options -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Publishing Options</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <flux:field>
                                    <flux:label>Category</flux:label>
                                    <flux:select wire:model="category" required>
                                        <option value="rescue">Rescue Story</option>
                                        <option value="adoption">Adoption Story</option>
                                        <option value="news">News & Updates</option>
                                    </flux:select>
                                    <flux:error name="category" />
                                </flux:field>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:checkbox wire:model="featured">Featured Story</flux:checkbox>
                                    <flux:description>Featured stories appear prominently on the homepage and stories page.</flux:description>
                                    <flux:error name="featured" />
                                </flux:field>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:checkbox wire:model="published">Publish Immediately</flux:checkbox>
                                    <flux:description>Uncheck to save as draft. You can publish later from the stories list.</flux:description>
                                    <flux:error name="published" />
                                </flux:field>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h2>
                        
                        <div class="space-y-3">
                            <flux:button type="submit" variant="primary" class="w-full" onclick="console.log('Create button clicked')">
                                <flux:icon.check class="w-4 h-4 mr-2" />
                                {{ $published ? 'Create & Publish Story' : 'Save as Draft' }}
                            </flux:button>

                            <flux:button href="{{ route('admin.stories.index') }}" variant="ghost" class="w-full">
                                <flux:icon.x-mark class="w-4 h-4 mr-2" />
                                Cancel
                            </flux:button>
                        </div>
                    </div>

                    <!-- Writing Tips -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-4">
                        <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">Writing Tips</h3>
                        <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                            <li>• Start with an engaging opening</li>
                            <li>• Include specific details and emotions</li>
                            <li>• Use a clear, conversational tone</li>
                            <li>• Add a compelling call-to-action</li>
                            <li>• Proofread before publishing</li>
                        </ul>
                    </div>

                    <!-- Category Guidelines -->
                    <div class="bg-gray-50 dark:bg-zinc-700 rounded-lg border border-gray-200 dark:border-zinc-600 p-4">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Category Guidelines</h3>
                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                            <div>
                                <strong>Rescue:</strong> Stories about animals being rescued and their recovery
                            </div>
                            <div>
                                <strong>Adoption:</strong> Success stories of animals finding their forever homes
                            </div>
                            <div>
                                <strong>News:</strong> Updates about the organization, events, and announcements
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
</div>