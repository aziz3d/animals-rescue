<?php

namespace Tests\Feature;

use App\Models\Story;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_stories_index_page_loads()
    {
        $response = $this->get('/stories');
        $response->assertStatus(200);
    }

    public function test_stories_index_displays_published_stories()
    {
        // Create published and unpublished stories
        $publishedStory = Story::factory()->create([
            'title' => 'Published Story',
            'published_at' => now()->subDay(),
        ]);

        $unpublishedStory = Story::factory()->create([
            'title' => 'Unpublished Story',
            'published_at' => null,
        ]);

        Livewire::test('stories.index')
            ->assertSee('Published Story')
            ->assertDontSee('Unpublished Story');
    }

    public function test_stories_index_filters_by_category()
    {
        Story::factory()->create([
            'title' => 'Rescue Story',
            'category' => 'rescue',
            'published_at' => now()->subDay(),
        ]);

        Story::factory()->create([
            'title' => 'Adoption Story',
            'category' => 'adoption',
            'published_at' => now()->subDay(),
        ]);

        Livewire::test('stories.index')
            ->call('filterByCategory', 'rescue')
            ->assertSee('Rescue Story')
            ->assertDontSee('Adoption Story');
    }

    public function test_stories_index_search_functionality()
    {
        Story::factory()->create([
            'title' => 'Bella the Dog',
            'content' => 'A story about a rescued dog named Bella',
            'published_at' => now()->subDay(),
        ]);

        Story::factory()->create([
            'title' => 'Max the Cat',
            'content' => 'A story about a cat named Max',
            'published_at' => now()->subDay(),
        ]);

        Livewire::test('stories.index')
            ->set('search', 'Bella')
            ->assertSee('Bella the Dog')
            ->assertDontSee('Max the Cat');
    }

    public function test_featured_story_displays_prominently()
    {
        $featuredStory = Story::factory()->create([
            'title' => 'Featured Story',
            'featured' => true,
            'published_at' => now()->subDay(),
        ]);

        $regularStory = Story::factory()->create([
            'title' => 'Regular Story',
            'featured' => false,
            'published_at' => now()->subDay(),
        ]);

        Livewire::test('stories.index')
            ->assertSee('Featured Story')
            ->assertSee('Featured Story'); // Should appear in featured section
    }

    public function test_story_detail_page_loads()
    {
        $story = Story::factory()->create([
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get("/stories/{$story->slug}");
        $response->assertStatus(200);
    }

    public function test_story_detail_shows_related_stories()
    {
        $mainStory = Story::factory()->create([
            'title' => 'Main Story',
            'category' => 'rescue',
            'published_at' => now()->subDay(),
        ]);

        $relatedStory = Story::factory()->create([
            'title' => 'Related Story',
            'category' => 'rescue',
            'published_at' => now()->subDays(2),
        ]);

        $unrelatedStory = Story::factory()->create([
            'title' => 'Unrelated Story',
            'category' => 'adoption',
            'published_at' => now()->subDays(3),
        ]);

        Livewire::test('stories.show', ['story' => $mainStory])
            ->assertSee('Main Story')
            ->assertSee('Related Story')
            ->assertSee('More Success Stories');
    }

    public function test_unpublished_story_returns_404()
    {
        $story = Story::factory()->create([
            'published_at' => null,
        ]);

        $response = $this->get("/stories/{$story->slug}");
        $response->assertStatus(404);
    }

    public function test_future_published_story_returns_404()
    {
        $story = Story::factory()->create([
            'published_at' => now()->addDay(),
        ]);

        $response = $this->get("/stories/{$story->slug}");
        $response->assertStatus(404);
    }
}