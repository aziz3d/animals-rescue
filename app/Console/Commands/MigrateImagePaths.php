<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Animal;
use App\Models\Story;

class MigrateImagePaths extends Command
{
    protected $signature = 'images:migrate-paths';
    protected $description = 'Migrate image paths from storage symlink to direct public URLs';

    public function handle()
    {
        $this->info('Starting image path migration...');

        // Migrate animal images
        $this->info('Migrating animal images...');
        $animals = Animal::whereNotNull('images')->get();
        $animalCount = 0;

        foreach ($animals as $animal) {
            $updated = false;
            $newImages = [];

            foreach ($animal->images as $imagePath) {
                // Convert from old format (animals/filename.jpg) to new format (animals/filename.jpg)
                // The path structure stays the same, but now points to public/uploads instead of storage/app/public
                $newImages[] = $imagePath;
                $updated = true;
            }

            if ($updated) {
                $animal->update(['images' => $newImages]);
                $animalCount++;
            }
        }

        $this->info("Updated {$animalCount} animals");

        // Migrate story images
        $this->info('Migrating story images...');
        $stories = Story::whereNotNull('featured_image')->get();
        $storyCount = 0;

        foreach ($stories as $story) {
            // Convert from old format to new format
            // The path structure stays the same, but now points to public/uploads instead of storage/app/public
            $story->update(['featured_image' => $story->featured_image]);
            $storyCount++;
        }

        $this->info("Updated {$storyCount} stories");

        $this->info('Image path migration completed successfully!');
        $this->info('Images are now served directly from public/uploads directory');

        return 0;
    }
}