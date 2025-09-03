<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Animal;
use App\Models\Story;

class FixImagePaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:fix {--force : Force recreation of symlink}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix storage symlink and repair broken image paths';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Starting image path repair process...');
        
        $results = [
            'symlink_fixed' => false,
            'animals_checked' => 0,
            'animals_fixed' => 0,
            'stories_checked' => 0,
            'stories_fixed' => 0,
            'missing_images' => [],
            'errors' => []
        ];

        try {
            // Step 1: Fix storage symlink
            $this->info('📁 Checking storage symlink...');
            $results['symlink_fixed'] = $this->fixStorageSymlink();

            // Step 2: Ensure storage directories exist
            $this->info('📂 Ensuring storage directories exist...');
            $this->ensureStorageDirectories();

            // Step 3: Fix animal image paths
            $this->info('🐕 Checking animal image paths...');
            $animalResults = $this->fixAnimalImagePaths();
            $results['animals_checked'] = $animalResults['checked'];
            $results['animals_fixed'] = $animalResults['fixed'];
            $results['missing_images'] = array_merge($results['missing_images'], $animalResults['missing']);

            // Step 4: Fix story image paths
            $this->info('📖 Checking story image paths...');
            $storyResults = $this->fixStoryImagePaths();
            $results['stories_checked'] = $storyResults['checked'];
            $results['stories_fixed'] = $storyResults['fixed'];
            $results['missing_images'] = array_merge($results['missing_images'], $storyResults['missing']);

            // Step 5: Report results
            $this->displayResults($results);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Error during image path repair: ' . $e->getMessage());
            $results['errors'][] = $e->getMessage();
            return Command::FAILURE;
        }
    }

    /**
     * Fix the storage symlink
     */
    private function fixStorageSymlink(): bool
    {
        $linkPath = public_path('storage');
        $targetPath = storage_path('app/public');

        try {
            // Remove existing symlink/directory if it exists
            if (File::exists($linkPath)) {
                if ($this->option('force')) {
                    $this->info('🗑️  Removing existing storage link...');
                    if (is_link($linkPath)) {
                        unlink($linkPath);
                    } else {
                        File::deleteDirectory($linkPath);
                    }
                } else {
                    $this->warn('⚠️  Storage link already exists. Use --force to recreate.');
                    return false;
                }
            }

            // Create the symlink
            if (PHP_OS_FAMILY === 'Windows') {
                // Use junction for Windows
                $command = 'mklink /J "' . $linkPath . '" "' . $targetPath . '"';
                exec($command, $output, $returnCode);
                
                if ($returnCode !== 0) {
                    throw new \Exception('Failed to create Windows junction: ' . implode("\n", $output));
                }
            } else {
                // Use symlink for Unix-like systems
                symlink($targetPath, $linkPath);
            }

            $this->info('✅ Storage symlink created successfully');
            return true;

        } catch (\Exception $e) {
            $this->error('❌ Failed to create storage symlink: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ensure storage directories exist
     */
    private function ensureStorageDirectories(): void
    {
        $directories = ['animals', 'stories', 'settings'];
        
        foreach ($directories as $dir) {
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
                $this->info("📁 Created directory: storage/app/public/{$dir}");
            }
        }
    }

    /**
     * Fix animal image paths
     */
    private function fixAnimalImagePaths(): array
    {
        $animals = Animal::whereNotNull('images')->get();
        $checked = 0;
        $fixed = 0;
        $missing = [];

        foreach ($animals as $animal) {
            $checked++;
            $originalImages = $animal->images ?? [];
            $validImages = [];
            $hasChanges = false;

            foreach ($originalImages as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    $validImages[] = $imagePath;
                } else {
                    // Try to find the image in different locations
                    $fixedPath = $this->findImageInStorage($imagePath, 'animals');
                    if ($fixedPath) {
                        $validImages[] = $fixedPath;
                        $hasChanges = true;
                        $this->info("🔧 Fixed path for {$animal->name}: {$imagePath} → {$fixedPath}");
                    } else {
                        $missing[] = [
                            'type' => 'animal',
                            'id' => $animal->id,
                            'name' => $animal->name,
                            'path' => $imagePath
                        ];
                    }
                }
            }

            if ($hasChanges || count($validImages) !== count($originalImages)) {
                $animal->update(['images' => $validImages]);
                $fixed++;
            }
        }

        return compact('checked', 'fixed', 'missing');
    }

    /**
     * Fix story image paths
     */
    private function fixStoryImagePaths(): array
    {
        $stories = Story::whereNotNull('featured_image')->get();
        $checked = 0;
        $fixed = 0;
        $missing = [];

        foreach ($stories as $story) {
            $checked++;
            $originalPath = $story->featured_image;

            if (!Storage::disk('public')->exists($originalPath)) {
                // Try to find the image in different locations
                $fixedPath = $this->findImageInStorage($originalPath, 'stories');
                if ($fixedPath) {
                    $story->update(['featured_image' => $fixedPath]);
                    $fixed++;
                    $this->info("🔧 Fixed path for story '{$story->title}': {$originalPath} → {$fixedPath}");
                } else {
                    $missing[] = [
                        'type' => 'story',
                        'id' => $story->id,
                        'title' => $story->title,
                        'path' => $originalPath
                    ];
                }
            }
        }

        return compact('checked', 'fixed', 'missing');
    }

    /**
     * Try to find an image in storage by checking different possible paths
     */
    private function findImageInStorage(string $originalPath, string $type): ?string
    {
        $filename = basename($originalPath);
        
        // Possible locations to check
        $possiblePaths = [
            "{$type}/{$filename}",
            $filename,
            "public/{$type}/{$filename}",
            "app/public/{$type}/{$filename}",
        ];

        foreach ($possiblePaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Display the results of the repair process
     */
    private function displayResults(array $results): void
    {
        $this->newLine();
        $this->info('📊 Image Path Repair Results:');
        $this->info('================================');
        
        if ($results['symlink_fixed']) {
            $this->info('✅ Storage symlink: Fixed');
        } else {
            $this->warn('⚠️  Storage symlink: Not changed');
        }

        $this->info("🐕 Animals checked: {$results['animals_checked']}");
        $this->info("🔧 Animals fixed: {$results['animals_fixed']}");
        
        $this->info("📖 Stories checked: {$results['stories_checked']}");
        $this->info("🔧 Stories fixed: {$results['stories_fixed']}");

        if (!empty($results['missing_images'])) {
            $this->newLine();
            $this->warn('⚠️  Missing Images Found:');
            foreach ($results['missing_images'] as $missing) {
                if ($missing['type'] === 'animal') {
                    $this->warn("   Animal '{$missing['name']}' (ID: {$missing['id']}): {$missing['path']}");
                } else {
                    $this->warn("   Story '{$missing['title']}' (ID: {$missing['id']}): {$missing['path']}");
                }
            }
        }

        if (!empty($results['errors'])) {
            $this->newLine();
            $this->error('❌ Errors encountered:');
            foreach ($results['errors'] as $error) {
                $this->error("   {$error}");
            }
        }

        $this->newLine();
        $this->info('🎉 Image path repair process completed!');
    }
}
