<?php

namespace App\Livewire\Admin\Comments;

use App\Models\Comment;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $selectedComments = [];
    public $selectAll = false;

    protected $paginationTheme = 'tailwind';

    protected $listeners = ['commentUpdated' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            // Select all comments on the current page
            $this->selectedComments = $this->comments->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            // Deselect all comments
            $this->selectedComments = [];
        }
    }

    public function updatedSelectedComments($value)
    {
        // Check if all comments on the page are selected
        $allCommentIds = $this->comments->pluck('id')->map(fn($id) => (string) $id)->toArray();
        
        if (empty($allCommentIds)) {
            $this->selectAll = false;
        } else {
            // Check if all comments are selected
            $this->selectAll = count(array_intersect($allCommentIds, $this->selectedComments)) === count($allCommentIds);
        }
    }

    public function getCommentsProperty()
    {
        return Comment::with(['user', 'story'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('content', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($userQuery) {
                          $userQuery->where('name', 'like', '%' . $this->search . '%')
                                    ->orWhere('email', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('story', function ($storyQuery) {
                          $storyQuery->where('title', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortBy = $field;
    }

    public function approveComment($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->approve();
            session()->flash('message', 'Comment approved successfully.');
        }
    }

    public function rejectComment($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->reject();
            session()->flash('message', 'Comment rejected successfully.');
        }
    }

    public function deleteComment($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->delete();
            session()->flash('message', 'Comment deleted successfully.');
        }
    }

    public function approveSelected()
    {
        if (!empty($this->selectedComments)) {
            Comment::whereIn('id', $this->selectedComments)->update(['status' => 'approved']);
            $this->selectedComments = [];
            $this->selectAll = false;
            session()->flash('message', 'Selected comments approved successfully.');
        }
    }

    public function rejectSelected()
    {
        if (!empty($this->selectedComments)) {
            Comment::whereIn('id', $this->selectedComments)->update(['status' => 'rejected']);
            $this->selectedComments = [];
            $this->selectAll = false;
            session()->flash('message', 'Selected comments rejected successfully.');
        }
    }

    public function deleteSelected()
    {
        if (!empty($this->selectedComments)) {
            Comment::destroy($this->selectedComments);
            $this->selectedComments = [];
            $this->selectAll = false;
            session()->flash('message', 'Selected comments deleted successfully.');
        }
    }

    public function render()
    {
        return view('livewire.admin.comments.index')
            ->layout('components.layouts.app', [
                'title' => 'Comment Management - Lovely Paws Rescue'
            ]);
    }
}