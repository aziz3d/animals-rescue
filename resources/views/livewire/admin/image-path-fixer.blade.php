<div class="space-y-6">
    <!-- Storage Status Card -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            <flux:icon.photo class="w-5 h-5 inline mr-2" />
            Storage System Status
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Symlink Status -->
            <div class="p-4 rounded-lg {{ $storageStatus['symlink_working'] ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' }}">
                <div class="flex items-center">
                    @if($storageStatus['symlink_working'])
                        <flux:icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" />
                        <span class="text-green-800 dark:text-green-200 font-medium">Symlink Working</span>
                    @else
                        <flux:icon.x-circle class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" />
                        <span class="text-red-800 dark:text-red-200 font-medium">Symlink Broken</span>
                    @endif
                </div>
            </div>

            <!-- Animals with Images -->
            <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-800 dark:text-blue-200">{{ $storageStatus['animals_with_images'] }}</div>
                    <div class="text-sm text-blue-600 dark:text-blue-400">Animals with Images</div>
                </div>
            </div>

            <!-- Stories with Images -->
            <div class="p-4 rounded-lg bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800">
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-800 dark:text-purple-200">{{ $storageStatus['stories_with_images'] }}</div>
                    <div class="text-sm text-purple-600 dark:text-purple-400">Stories with Images</div>
                </div>
            </div>

            <!-- Storage Directories -->
            <div class="p-4 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
                <div class="text-center">
                    <div class="text-2xl font-bold text-amber-800 dark:text-amber-200">
                        {{ array_sum(array_column($storageStatus['storage_directories'], 'file_count')) }}
                    </div>
                    <div class="text-sm text-amber-600 dark:text-amber-400">Total Image Files</div>
                </div>
            </div>
        </div>

        <!-- Directory Details -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($storageStatus['storage_directories'] as $dir => $info)
                <div class="p-3 rounded-lg bg-gray-50 dark:bg-zinc-700">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-900 dark:text-white capitalize">{{ $dir }}</span>
                        <div class="flex items-center space-x-2">
                            @if($info['exists'])
                                <flux:icon.check-circle class="w-4 h-4 text-green-500" />
                            @else
                                <flux:icon.x-circle class="w-4 h-4 text-red-500" />
                            @endif
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $info['file_count'] }} files</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Fix Images Card -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            <flux:icon.wrench-screwdriver class="w-5 h-5 inline mr-2" />
            Image Path Repair Tool
        </h3>
        
        <div class="mb-6">
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                This tool will fix broken image paths and recreate the storage symlink if needed. It will:
            </p>
            <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1 mb-4">
                <li>Check and fix the storage symlink between public/storage and storage/app/public</li>
                <li>Ensure all required storage directories exist</li>
                <li>Scan all animals and stories for broken image paths</li>
                <li>Attempt to locate missing images in different storage locations</li>
                <li>Remove references to images that no longer exist</li>
                <li>Provide a detailed report of all changes made</li>
            </ul>
        </div>

        <!-- Options -->
        <div class="mb-6">
            <flux:field>
                <flux:checkbox wire:model="forceRecreate">
                    Force recreate storage symlink (recommended if symlink is broken)
                </flux:checkbox>
                <flux:description>
                    This will remove and recreate the storage symlink even if it already exists.
                </flux:description>
            </flux:field>
        </div>

        <!-- Action Button -->
        <div class="flex items-center space-x-4">
            <flux:button 
                wire:click="fixImagePaths" 
                variant="primary" 
                :disabled="$isRunning"
                class="flex items-center"
            >
                @if($isRunning)
                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Fixing Image Paths...
                @else
                    <flux:icon.wrench-screwdriver class="w-4 h-4 mr-2" />
                    Fix Image Paths
                @endif
            </flux:button>

            @if(!$storageStatus['symlink_working'])
                <div class="flex items-center text-amber-600 dark:text-amber-400">
                    <flux:icon.exclamation-triangle class="w-4 h-4 mr-2" />
                    <span class="text-sm">Storage symlink needs repair</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Results Card -->
    @if($showResults)
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                <flux:icon.document-text class="w-5 h-5 inline mr-2" />
                Repair Results
            </h3>

            @if(isset($results['success']) && $results['success'])
                <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <div class="flex items-center">
                        <flux:icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" />
                        <span class="text-green-800 dark:text-green-200 font-medium">Image path repair completed successfully!</span>
                    </div>
                </div>
            @else
                <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <div class="flex items-center">
                        <flux:icon.x-circle class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" />
                        <span class="text-red-800 dark:text-red-200 font-medium">Some issues were encountered during repair</span>
                    </div>
                </div>
            @endif

            <!-- Statistics -->
            @if(isset($results['animals_checked']))
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="text-xl font-bold text-blue-800 dark:text-blue-200">{{ $results['animals_checked'] }}</div>
                        <div class="text-sm text-blue-600 dark:text-blue-400">Animals Checked</div>
                    </div>
                    <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="text-xl font-bold text-green-800 dark:text-green-200">{{ $results['animals_fixed'] }}</div>
                        <div class="text-sm text-green-600 dark:text-green-400">Animals Fixed</div>
                    </div>
                    <div class="text-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <div class="text-xl font-bold text-purple-800 dark:text-purple-200">{{ $results['stories_checked'] }}</div>
                        <div class="text-sm text-purple-600 dark:text-purple-400">Stories Checked</div>
                    </div>
                    <div class="text-center p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                        <div class="text-xl font-bold text-orange-800 dark:text-orange-200">{{ $results['stories_fixed'] }}</div>
                        <div class="text-sm text-orange-600 dark:text-orange-400">Stories Fixed</div>
                    </div>
                </div>
            @endif

            <!-- Command Output -->
            @if(isset($results['output']) && $results['output'])
                <div class="mb-4">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Detailed Output:</h4>
                    <div class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm overflow-x-auto">
                        <pre>{{ $results['output'] }}</pre>
                    </div>
                </div>
            @endif

            <!-- Error Details -->
            @if(isset($results['error']))
                <div class="mb-4">
                    <h4 class="font-medium text-red-800 dark:text-red-200 mb-2">Error Details:</h4>
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4 rounded-lg">
                        <code class="text-red-800 dark:text-red-200">{{ $results['error'] }}</code>
                    </div>
                </div>
            @endif

            <!-- Refresh Status Button -->
            <div class="mt-4">
                <flux:button wire:click="$refresh" variant="outline" size="sm">
                    <flux:icon.arrow-path class="w-4 h-4 mr-2" />
                    Refresh Status
                </flux:button>
            </div>
        </div>
    @endif
</div>