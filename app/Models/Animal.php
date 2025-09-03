<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'species',
        'breed',
        'age',
        'gender',
        'size',
        'description',
        'medical_history',
        'adoption_status',
        'featured',
        'images',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'images' => 'array',
        'featured' => 'boolean',
    ];

    /**
     * Scope a query to only include available animals.
     */
    public function scopeAvailable($query)
    {
        return $query->where('adoption_status', 'available');
    }

    /**
     * Scope a query to only include featured animals.
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Get the first image URL or return null.
     */
    public function getFirstImageAttribute()
    {
        if ($this->images && count($this->images) > 0) {
            $imagePath = $this->images[0];
            
            // Handle different path formats
            $cleanPath = ltrim($imagePath, '/');
            if (!str_starts_with($cleanPath, 'animals/')) {
                $cleanPath = 'animals/' . $cleanPath;
            }
            
            // Check if file exists in uploads directory
            $fullPath = public_path('uploads/' . $cleanPath);
            if (file_exists($fullPath)) {
                // Add cache-busting parameter based on file modification time
                $timestamp = filemtime($fullPath);
                return asset('uploads/' . $cleanPath) . '?v=' . $timestamp;
            }
            
            // Fallback: try original path
            $originalPath = public_path('uploads/' . $imagePath);
            if (file_exists($originalPath)) {
                $timestamp = filemtime($originalPath);
                return asset('uploads/' . $imagePath) . '?v=' . $timestamp;
            }
            
            // Log missing images for debugging
            \Log::warning("Animal image not found", [
                'animal_id' => $this->id,
                'image_path' => $imagePath,
                'clean_path' => $cleanPath,
                'full_path' => $fullPath,
                'original_path' => $originalPath
            ]);
        }
        return null;
    }

    /**
     * Get all image URLs.
     */
    public function getImageUrlsAttribute()
    {
        if (!$this->images) {
            return [];
        }

        return collect($this->images)->map(function ($imagePath) {
            // Handle different path formats
            $cleanPath = ltrim($imagePath, '/');
            if (!str_starts_with($cleanPath, 'animals/')) {
                $cleanPath = 'animals/' . $cleanPath;
            }
            
            // Check if file exists in uploads directory
            $fullPath = public_path('uploads/' . $cleanPath);
            if (file_exists($fullPath)) {
                // Add cache-busting parameter based on file modification time
                $timestamp = filemtime($fullPath);
                return asset('uploads/' . $cleanPath) . '?v=' . $timestamp;
            }
            
            // Fallback: try original path
            $originalPath = public_path('uploads/' . $imagePath);
            if (file_exists($originalPath)) {
                $timestamp = filemtime($originalPath);
                return asset('uploads/' . $imagePath) . '?v=' . $timestamp;
            }
            
            // Log missing images for debugging
            \Log::warning("Animal image not found", [
                'animal_id' => $this->id,
                'image_path' => $imagePath,
                'clean_path' => $cleanPath,
                'full_path' => $fullPath,
                'original_path' => $originalPath
            ]);
            return null;
        })->filter()->values()->toArray();
    }
}
