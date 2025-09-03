<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Animal;
use App\Models\Story;

class ImagePathFixer extends Component
{
    public bool $isRunning = false;
    public array $results = [];
    public bool $showResults = false;
    public bool $forceRecreate = false;

    public function fixImagePaths()
    {
        $this->isRunning = true;
        $this->showResults = false;
        $this->results = [];

        try {
            // Run the artisan command and capture output
            $exitCode = Artisan::call('images:fix', [
                '--force' => $this->forceRecreate
            ]);

            $output = Artisan::output();

            $this->results = $this->parseCommandOutput($output);
            $this->results['success'] = $exitCode === 0;
            $this->results['output'] = $output;

            if ($exitCode === 0) {
                session()->flash('success', 'Image paths have been successfully repaired!');
            } else {
                session()->flash('error', 'There were some issues during the repair process. Check the details below.');
            }
        } catch (\Exception $e) {
            $this->results = [
                'success' => false,
                'error' => $e->getMessage(),
                'output' => ''
            ];
            session()->flash('error', 'Failed to run image path repair: ' . $e->getMessage());
        }

        $this->isRunning = false;
        $this->showResults = true;
    }

    public function checkStorageStatus()
    {
        return [
            'uploads_directory_exists' => File::exists(public_path('uploads')),
            'uploads_working' => $this->isUploadsWorking(),
            'animals_with_images' => Animal::whereNotNull('images')->count(),
            'stories_with_images' => Story::whereNotNull('featured_image')->count(),
            'upload_directories' => $this->getUploadDirectoryStatus()
        ];
    }

    private function isUploadsWorking(): bool
    {
        try {
            return File::exists(public_path('uploads/animals')) &&
                File::isDirectory(public_path('uploads/animals'));
        } catch (\Exception $e) {
            return false;
        }
    }

    private function getUploadDirectoryStatus(): array
    {
        $directories = ['animals', 'stories'];
        $status = [];

        foreach ($directories as $dir) {
            $fullPath = public_path('uploads/' . $dir);
            $status[$dir] = [
                'exists' => File::exists($fullPath),
                'file_count' => $this->countFilesInPublicDirectory($dir)
            ];
        }

        return $status;
    }

    private function countFilesInPublicDirectory(string $directory): int
    {
        try {
            $fullPath = public_path('uploads/' . $directory);
            if (!File::exists($fullPath)) {
                return 0;
            }
            $files = File::files($fullPath);
            return count($files);
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function parseCommandOutput(string $output): array
    {
        $results = [
            'animals_checked' => 0,
            'animals_fixed' => 0,
            'stories_checked' => 0,
            'stories_fixed' => 0,
            'symlink_fixed' => false,
            'missing_images' => []
        ];

        if (preg_match('/Animals checked: (\d+)/', $output, $matches)) {
            $results['animals_checked'] = (int)$matches[1];
        }
        if (preg_match('/Animals fixed: (\d+)/', $output, $matches)) {
            $results['animals_fixed'] = (int)$matches[1];
        }
        if (preg_match('/Stories checked: (\d+)/', $output, $matches)) {
            $results['stories_checked'] = (int)$matches[1];
        }
        if (preg_match('/Stories fixed: (\d+)/', $output, $matches)) {
            $results['stories_fixed'] = (int)$matches[1];
        }
        if (strpos($output, 'Storage symlink created successfully') !== false) {
            $results['symlink_fixed'] = true;
        }

        return $results;
    }

    public function render()
    {
        $storageStatus = $this->checkStorageStatus();

        return view('livewire.admin.image-path-fixer', [
            'storageStatus' => $storageStatus
        ]);
    }
}
