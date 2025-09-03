<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Story;
use Illuminate\Database\Seeder;

class FixImagePathsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Fix animal image paths
        $animals = Animal::all();
        foreach ($animals as $animal) {
            if ($animal->images) {
                $updatedImages = [];
                foreach ($animal->images as $imagePath) {
                    // Convert from 'animals-rescue/pet-images/filename.jpg' to 'animals/filename.jpg'
                    $filename = basename($imagePath);
                    $updatedImages[] = 'animals/' . $filename;
                }
                $animal->images = $updatedImages;
                $animal->save();
            }
        }

        // Fix story image paths
        $stories = Story::all();
        foreach ($stories as $story) {
            if ($story->featured_image) {
                // Convert from 'animals-rescue/pet-images/filename.jpg' to 'stories/filename.jpg'
                $filename = basename($story->featured_image);
                $story->featured_image = 'stories/' . $filename;
                $story->save();
            }
        }

        $this->command->info('Image paths have been updated successfully!');
    }
}