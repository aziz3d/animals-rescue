<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Story>
 */
class StoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(6, true);
        
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->paragraphs(5, true),
            'excerpt' => $this->faker->paragraph(),
            'featured_image' => $this->faker->optional(0.8)->randomElement([
                '/animals-rescue/story1.jpg',
                '/animals-rescue/story2.jpg',
                '/animals-rescue/story3.jpg',
                '/animals-rescue/story4.jpg',
            ]),
            'category' => $this->faker->randomElement(['rescue', 'adoption', 'news']),
            'featured' => $this->faker->boolean(15), // 15% chance of being featured
            'published_at' => $this->faker->optional(0.9)->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the story is published.
     */
    public function published()
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    /**
     * Indicate that the story is featured.
     */
    public function featured()
    {
        return $this->state(fn (array $attributes) => [
            'featured' => true,
        ]);
    }

    /**
     * Indicate that the story is a rescue story.
     */
    public function rescue()
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'rescue',
        ]);
    }

    /**
     * Indicate that the story is an adoption story.
     */
    public function adoption()
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'adoption',
        ]);
    }
}
