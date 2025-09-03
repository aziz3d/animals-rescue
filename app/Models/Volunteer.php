<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'areas_of_interest',
        'availability',
        'experience',
        'status',
        'applied_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'areas_of_interest' => 'array',
        'availability' => 'array',
        'applied_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($volunteer) {
            if (empty($volunteer->applied_at)) {
                $volunteer->applied_at = now();
            }
        });
    }

    /**
     * Scope a query to only include pending volunteers.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved volunteers.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include active volunteers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
