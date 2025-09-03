<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Story extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'category',
        'featured',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($story) {
            if (empty($story->slug)) {
                $story->slug = Str::slug($story->title);
            }
        });

        static::updating(function ($story) {
            if ($story->isDirty('title') && empty($story->slug)) {
                $story->slug = Str::slug($story->title);
            }
        });
    }

    /**
     * Scope a query to only include published stories.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include featured stories.
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the featured image URL with proper storage path.
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            // Handle different path formats
            $cleanPath = ltrim($this->featured_image, '/');
            if (!str_starts_with($cleanPath, 'stories/')) {
                $cleanPath = 'stories/' . $cleanPath;
            }

            // Check if file exists in the storage public directory first
            $storagePath = 'public/' . $cleanPath;
            if (\Storage::exists($storagePath)) {
                return \Storage::url($cleanPath);
            }

            // Check if file exists in uploads directory (legacy support)
            $fullPath = public_path('uploads/' . $cleanPath);
            if (file_exists($fullPath)) {
                // Add cache usting parameter based on file modification time
                $timestamp = filemtime($fullPath);
                return asset('uploads/' . $cleanPath) . '?v=' . $timestamp;
            }

            // Fallback: try original path
            $originalPath = public_path('uploads/' . $this->featured_image);
            if (file_exists($originalPath)) {
                $timestamp = filemtime($originalPath);
                return asset('uploads/' . $this->featured_image) . '?v=' . $timestamp;
            }

            // Log missing images for debugging
            \Log::warning("Story image not found", [
                'story_id' => $this->id,
                'image_path' => $this->featured_image,
                'clean_path' => $cleanPath,
                'storage_path' => $storagePath,
                'full_path' => $fullPath,
                'original_path' => $originalPath
            ]);
        }
        return null;
    }

    /**
     * Get the top-level approved comments for the story.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->approved()->whereNull('parent_id')->latest();
    }

    /**
     * Get all approved comments (including nested) for the story.
     */
    public function allComments()
    {
        return $this->hasMany(Comment::class)->approved()->latest();
    }

    /**
     * Get the approved comments count for the story.
     */
    public function approvedCommentsCount()
    {
        return $this->allComments()->count();
    }
}
