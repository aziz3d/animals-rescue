<?php

namespace App\Livewire\Stories;

use App\Models\Comment;
use App\Models\Story;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class Comments extends Component
{
    public Story $story;
    public $content = '';
    public $comments;
    public $replyingTo = null;
    public $replyContent = '';

    protected $rules = [
        'content' => 'required|string|max:1000',
        'replyContent' => 'required|string|max:1000',
    ];

    protected $messages = [
        'content.required' => 'Please enter your comment',
        'content.max' => 'Comment must be less than 1000 characters',
        'replyContent.required' => 'Please enter your reply',
        'replyContent.max' => 'Reply must be less than 1000 characters',
    ];

    public function mount(Story $story)
    {
        $this->story = $story;
        $this->loadComments();
    }

    public function loadComments()
    {
        // Load only top-level approved comments with their approved children
        $this->comments = $this->story->comments()
            ->with(['user', 'children' => function ($query) {
                $query->with('user');
            }])
            ->get();
    }

    public function save()
    {
        // Check if comments are enabled
        if (!setting('stories_enable_comments', false)) {
            session()->flash('error', 'Comments are currently disabled.');
            return;
        }

        // Require authentication
        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to comment.');
            return;
        }

        // Validate content
        $this->validateOnly('content');

        Comment::create([
            'story_id' => $this->story->id,
            'user_id' => Auth::id(),
            'content' => trim($this->content), // Trim whitespace
            'ip_address' => Request::ip(),
            'status' => 'pending', // Default to pending
        ]);

        // Reset form
        $this->content = '';

        // Reload comments
        $this->loadComments();

        // Show success message
        session()->flash('message', 'Comment submitted and is pending approval.');
    }

    public function reply($commentId)
    {
        // Require authentication
        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to reply.');
            return;
        }

        $this->replyingTo = $commentId;
        $this->replyContent = ''; // Clear any previous reply content
    }

    public function cancelReply()
    {
        $this->replyingTo = null;
        $this->replyContent = '';
    }

    public function saveReply()
    {
        // Check if comments are enabled
        if (!setting('stories_enable_comments', false)) {
            session()->flash('error', 'Comments are currently disabled.');
            return;
        }

        // Require authentication
        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to reply.');
            return;
        }

        // Validate reply content
        $this->validateOnly('replyContent');

        $parentComment = Comment::find($this->replyingTo);

        if (!$parentComment) {
            session()->flash('error', 'Parent comment not found.');
            return;
        }

        Comment::create([
            'story_id' => $this->story->id,
            'user_id' => Auth::id(),
            'content' => trim($this->replyContent),
            'parent_id' => $this->replyingTo,
            'ip_address' => Request::ip(),
            'status' => 'pending',
        ]);

        // Reset reply form
        $this->replyingTo = null;
        $this->replyContent = '';

        // Reload comments
        $this->loadComments();

        // Show success message
        session()->flash('message', 'Reply submitted and is pending approval.');
    }

    public function render()
    {
        return view('livewire.stories.comments');
    }
}
