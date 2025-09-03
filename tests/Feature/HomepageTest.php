<?php

namespace Tests\Feature;

use App\Models\Story;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_displays_featured_stories()
    {
        // Create a featured story
        $featuredStory = Story::factory()->create([
            'title' => 'Featured Success Story',
            'featured' => true,
            'published_at' => now()->subDay(),
        ]);

        // Create a regular story
        $regularStory = Story::factory()->create([
            'title' => 'Regular Story',
            'featured' => false,
            'published_at' => now()->subDays(2),
        ]);

        Livewire::test('homepage')
            ->assertSee('Featured Success Story')
            ->assertSee('Regular Story'); // Should also appear as latest story
    }

    public function test_homepage_shows_story_statistics()
    {
        // Create some stories
        Story::factory()->count(5)->create([
            'published_at' => now()->subDay(),
        ]);

        Livewire::test('homepage')
            ->assertSee('5'); // Should show in statistics
    }

    public function test_homepage_links_to_stories_page()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertSee('Read All Stories');
    }
}