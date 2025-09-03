<?php

namespace App\Livewire\Stories;

use App\Models\Story;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $selectedCategory = 'all';
    public $search = '';

    protected $queryString = [
        'selectedCategory' => ['except' => 'all'],
        'search' => ['except' => ''],
    ];

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function filterByCategory($category)
    {
        $this->selectedCategory = $category;
    }

    public function render()
    {
        // Get featured story
        $featuredStory = Story::published()
            ->featured()
            ->latest('published_at')
            ->first();

        // Build query for stories
        $query = Story::published()
            ->latest('published_at');

        // Apply category filter
        if ($this->selectedCategory !== 'all') {
            $query->category($this->selectedCategory);
        }

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $this->search . '%');
            });
        }

        // Exclude featured story from main listing if it exists
        if ($featuredStory) {
            $query->where('id', '!=', $featuredStory->id);
        }

        $stories = $query->paginate(9);

        // Get available categories
        $categories = Story::published()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return view('livewire.stories.index', [
            'featuredStory' => $featuredStory,
            'stories' => $stories,
            'categories' => $categories,
        ])->layout('components.layouts.public', ['title' => 'Success Stories - Lovely Paws Rescue']);
    }
}