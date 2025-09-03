<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'story_id',
        'user_id',
        'name',
        'email',
        'content',
        'approved',
        'ip_address',
        'parent_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'approved' => 'boolean',
    ];

    /**
     * Get the story that owns the comment.
     */
    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    /**
     * Get the user that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment.
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the child comments.
     */
    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id')->where('status', 'approved')->latest();
    }

    /**
     * Scope a query to only include approved comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include pending comments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include rejected comments.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to only include comments with a specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the commenter's name (either from user or guest name).
     */
    public function getCommenterNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }

        return $this->name ?: 'Anonymous';
    }

    /**
     * Get all descendants of this comment.
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Check if this comment is a reply (has a parent).
     */
    public function isReply()
    {
        return $this->parent_id !== null;
    }

    /**
     * Get the root comment (top-level comment).
     */
    public function root()
    {
        if ($this->parent_id === null) {
            return $this;
        }

        return $this->parent->root();
    }

    /**
     * Approve the comment.
     */
    public function approve()
    {
        $this->status = 'approved';
        return $this->save();
    }

    /**
     * Reject the comment.
     */
    public function reject()
    {
        $this->status = 'rejected';
        return $this->save();
    }

    /**
     * Set the comment as pending.
     */
    public function setPending()
    {
        $this->status = 'pending';
        return $this->save();
    }

    /**
     * Check if the comment is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the comment is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the comment is rejected.
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
