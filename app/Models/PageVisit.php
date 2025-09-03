<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'url',
        'page_type',
        'page_id',
        'ip_address',
        'user_agent',
        'referrer',
    ];

    /**
     * Scope a query to filter by page type.
     */
    public function scopePageType($query, $type)
    {
        return $query->where('page_type', $type);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    /**
     * Get visits for a specific page.
     */
    public function scopeForPage($query, $type, $id = null)
    {
        $query = $query->where('page_type', $type);
        
        if ($id !== null) {
            $query->where('page_id', $id);
        }
        
        return $query;
    }
}