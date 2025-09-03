<?php

namespace App\Livewire\Stories;

use App\Models\Story;
use Livewire\Component;

class Show extends Component
{
    public Story $story;

    public function mount(Story $story)
    {
        // Ensure the story is published
        if (!$story->published_at || $story->published_at->isFuture()) {
            abort(404);
        }

        $this->story = $story;
    }

    public function render()
    {
        // Get related stories (same category, excluding current story)
        $relatedStories = Story::published()
            ->where('id', '!=', $this->story->id)
            ->where('category', $this->story->category)
            ->latest('published_at')
            ->limit(3)
            ->get();

        // If we don't have enough related stories from the same category,
        // fill with other recent stories
        if ($relatedStories->count() < 3) {
            $additionalStories = Story::published()
                ->where('id', '!=', $this->story->id)
                ->whereNotIn('id', $relatedStories->pluck('id'))
                ->latest('published_at')
                ->limit(3 - $relatedStories->count())
                ->get();

            $relatedStories = $relatedStories->concat($additionalStories);
        }

        return view('livewire.stories.show', [
            'relatedStories' => $relatedStories,
        ])->layout('components.layouts.public', ['title' => $this->story->title . ' - Lovely Paws Rescue']);
    }
}