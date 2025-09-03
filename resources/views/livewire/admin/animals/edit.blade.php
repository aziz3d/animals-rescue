<?php

use App\Models\Animal;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    use WithFileUploads;

    public Animal $animal;
    public string $name = '';
    public string $species = '';
    public string $breed = '';
    public int $age = 1;
    public string $gender = '';
    public string $size = '';
    public string $description = '';
    public string $medical_history = '';
    public string $adoption_status = 'available';
    public bool $featured = false;
    public array $existingImages = [];
    public array $newImages = [];

    public function mount(Animal $animal)
    {
        $this->animal = $animal;
        $this->name = $animal->name;
        $this->species = $animal->species;
        $this->breed = $animal->breed;
        $this->age = $animal->age;
        $this->gender = $animal->gender;
        $this->size = $animal->size;
        $this->description = $animal->description;
        $this->medical_history = $animal->medical_history ?? '';
        $this->adoption_status = $animal->adoption_status;
        $this->featured = $animal->featured;
        $this->existingImages = $animal->images ?? [];
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'species' => 'required|in:dog,cat,rabbit,bird,other',
            'breed' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:30',
            'gender' => 'required|in:male,female',
            'size' => 'required|in:small,medium,large',
            'description' => 'required|string|min:50',
            'medical_history' => 'nullable|string',
            'adoption_status' => 'required|in:available,pending,adopted',
            'featured' => 'boolean',
            'newImages.*' => 'nullable|image|max:2048',
        ];
    }

    public function save()
    {
        $this->validate();

        // Handle new image uploads
        $newImagePaths = [];
        foreach ($this->newImages as $image) {
            if ($image) {
                try {
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $relativePath = 'animals/' . $filename;
                    
                    // Ensure the animals directory exists
                    $uploadsDir = public_path('uploads/animals');
                    if (!file_exists($uploadsDir)) {
                        mkdir($uploadsDir, 0755, true);
                    }
                    
                    // Store file using the public disk (which points to public/uploads)
                    $storedPath = $image->storeAs('animals', $filename, 'public');
                    
                    // Verify the file was actually stored
                    $fullPath = public_path('uploads/' . $relativePath);
                    if (file_exists($fullPath)) {
                        $newImagePaths[] = $relativePath;
                        \Log::info('Animal image uploaded successfully', [
                            'path' => $relativePath,
                            'full_path' => $fullPath,
                            'url' => asset('uploads/' . $relativePath),
                            'file_size' => filesize($fullPath)
                        ]);
                    } else {
                        \Log::error('Animal image upload failed - file not found after storage', [
                            'expected_path' => $fullPath,
                            'stored_path' => $storedPath
                        ]);
                        session()->flash('error', 'Failed to upload image: File not saved properly');
                        return;
                    }
                } catch (\Exception $e) {
                    \Log::error('Animal image upload failed', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    session()->flash('error', 'Failed to upload image: ' . $e->getMessage());
                    return;
                }
            }
        }

        // Combine existing and new images
        $allImages = array_merge($this->existingImages, $newImagePaths);

        // Update the animal
        $this->animal->update([
            'name' => $this->name,
            'species' => $this->species,
            'breed' => $this->breed,
            'age' => $this->age,
            'gender' => $this->gender,
            'size' => $this->size,
            'description' => $this->description,
            'medical_history' => $this->medical_history,
            'adoption_status' => $this->adoption_status,
            'featured' => $this->featured,
            'images' => $allImages,
        ]);

        session()->flash('success', "Animal '{$this->animal->name}' has been updated successfully!");

        return redirect()->route('admin.animals.show', $this->animal);
    }

    public function removeExistingImage($index)
    {
        $imageToRemove = $this->existingImages[$index];
        
        // Delete from public uploads directory
        $fullPath = public_path('uploads/' . $imageToRemove);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        // Remove from array
        unset($this->existingImages[$index]);
        $this->existingImages = array_values($this->existingImages);
    }

    public function removeNewImage($index)
    {
        unset($this->newImages[$index]);
        $this->newImages = array_values($this->newImages);
    }
}; ?>

<div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit {{ $animal->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">Update animal profile information</p>
                </div>
            </div>
        </div>

        <!-- Back Link -->
        <div class="mb-6">
            <flux:button href="{{ route('admin.animals.show', $animal) }}" variant="ghost" size="sm">
                <flux:icon.arrow-left class="w-4 h-4 mr-2" />
                Back to Profile
            </flux:button>
        </div>

        <form wire:submit="save">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <flux:field>
                                    <flux:label>Animal Name</flux:label>
                                    <flux:input wire:model="name" placeholder="e.g., Buddy" required />
                                    <flux:error name="name" />
                                </flux:field>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:label>Species</flux:label>
                                    <flux:select wire:model="species" required>
                                        <option value="">Select Species</option>
                                        <option value="dog">Dog</option>
                                        <option value="cat">Cat</option>
                                        <option value="rabbit">Rabbit</option>
                                        <option value="bird">Bird</option>
                                        <option value="other">Other</option>
                                    </flux:select>
                                    <flux:error name="species" />
                                </flux:field>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:label>Breed</flux:label>
                                    <flux:input wire:model="breed" placeholder="e.g., Golden Retriever" required />
                                    <flux:error name="breed" />
                                </flux:field>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:label>Age (years)</flux:label>
                                    <flux:input type="number" wire:model="age" min="0" max="30" required />
                                    <flux:error name="age" />
                                </flux:field>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:label>Gender</flux:label>
                                    <flux:select wire:model="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </flux:select>
                                    <flux:error name="gender" />
                                </flux:field>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:label>Size</flux:label>
                                    <flux:select wire:model="size" required>
                                        <option value="">Select Size</option>
                                        <option value="small">Small</option>
                                        <option value="medium">Medium</option>
                                        <option value="large">Large</option>
                                    </flux:select>
                                    <flux:error name="size" />
                                </flux:field>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Description & History</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <flux:field>
                                    <flux:label>Description</flux:label>
                                    <flux:textarea 
                                        wire:model="description" 
                                        rows="4" 
                                        placeholder="Tell us about this animal's personality, behavior, and what makes them special..."
                                        required
                                    />
                                    <flux:description>Minimum 50 characters. This will be displayed on the public animal profile.</flux:description>
                                    <flux:error name="description" />
                                </flux:field>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:label>Medical History</flux:label>
                                    <flux:textarea 
                                        wire:model="medical_history" 
                                        rows="3" 
                                        placeholder="Any medical conditions, treatments, or special care requirements..."
                                    />
                                    <flux:description>Optional. Include any relevant medical information for potential adopters.</flux:description>
                                    <flux:error name="medical_history" />
                                </flux:field>
                            </div>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Photos</h2>
                        
                        <div class="space-y-6">
                            <!-- Existing Images -->
                            @if($existingImages)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Current Photos</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach($existingImages as $index => $image)
                                            <div class="relative">
                                                @php
                                                    $imageUrl = null;
                                                    $cleanPath = ltrim($image, '/');
                                                    if (!str_starts_with($cleanPath, 'animals/')) {
                                                        $cleanPath = 'animals/' . $cleanPath;
                                                    }
                                                    
                                                    if (file_exists(public_path('uploads/' . $cleanPath))) {
                                                        $imageUrl = asset('uploads/' . $cleanPath);
                                                    } elseif (file_exists(public_path('uploads/' . $image))) {
                                                        $imageUrl = asset('uploads/' . $image);
                                                    }
                                                @endphp
                                                @if($imageUrl)
                                                    <img 
                                                        src="{{ $imageUrl }}" 
                                                        alt="Current photo" 
                                                        class="w-full aspect-square object-cover rounded-lg border border-gray-200 dark:border-zinc-700"
                                                    >
                                                @else
                                                    <div class="w-full aspect-square bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-zinc-700 flex items-center justify-center">
                                                        <div class="text-center">
                                                            <flux:icon.photo class="w-6 h-6 text-gray-400 mx-auto mb-1" />
                                                            <p class="text-gray-500 text-xs">Image not found</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                <button 
                                                    type="button"
                                                    wire:click="removeExistingImage({{ $index }})"
                                                    wire:confirm="Are you sure you want to delete this image? This action cannot be undone."
                                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                                                >
                                                    <flux:icon.x-mark class="w-3 h-3" />
                                                </button>
                                                @if($index === 0)
                                                    <div class="absolute bottom-2 left-2">
                                                        <span class="px-2 py-1 text-xs font-medium bg-blue-500 text-white rounded">
                                                            Main Photo
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- New Images -->
                            <div>
                                <flux:field>
                                    <flux:label>Add New Images</flux:label>
                                    <flux:input type="file" wire:model="newImages" multiple accept="image/*" />
                                    <flux:description>Upload additional images. Maximum 2MB per image.</flux:description>
                                    <flux:error name="newImages.*" />
                                </flux:field>
                            </div>

                            <!-- New Image Previews -->
                            @if($newImages)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">New Photos to Add</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach($newImages as $index => $image)
                                            @if($image)
                                                <div class="relative">
                                                    @try
                                                        <img 
                                                            src="{{ $image->temporaryUrl() }}" 
                                                            alt="New photo preview" 
                                                            class="w-full aspect-square object-cover rounded-lg border border-gray-200 dark:border-zinc-700"
                                                        >
                                                    @catch(\Exception $e)
                                                        <div class="w-full aspect-square bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-zinc-700 flex items-center justify-center">
                                                            <p class="text-gray-500 text-sm">Image will be uploaded when saved</p>
                                                        </div>
                                                    @endtry
                                                    <button 
                                                        type="button"
                                                        wire:click="removeNewImage({{ $index }})"
                                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                                                    >
                                                        <flux:icon.x-mark class="w-3 h-3" />
                                                    </button>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status & Settings -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status & Settings</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <flux:field>
                                    <flux:label>Adoption Status</flux:label>
                                    <flux:select wire:model="adoption_status" required>
                                        <option value="available">Available for Adoption</option>
                                        <option value="pending">Adoption Pending</option>
                                        <option value="adopted">Already Adopted</option>
                                    </flux:select>
                                    <flux:error name="adoption_status" />
                                </flux:field>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:checkbox wire:model="featured">Featured Animal</flux:checkbox>
                                    <flux:description>Featured animals appear prominently on the homepage and listings.</flux:description>
                                    <flux:error name="featured" />
                                </flux:field>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h2>
                        
                        <div class="space-y-3">
                            <flux:button type="submit" variant="primary" class="w-full">
                                <flux:icon.check class="w-4 h-4 mr-2" />
                                Save Changes
                            </flux:button>

                            <flux:button href="{{ route('admin.animals.show', $animal) }}" variant="ghost" class="w-full">
                                <flux:icon.x-mark class="w-4 h-4 mr-2" />
                                Cancel
                            </flux:button>
                        </div>
                    </div>

                    <!-- Profile Info -->
                    <div class="bg-gray-50 dark:bg-zinc-700 rounded-lg border border-gray-200 dark:border-zinc-600 p-4">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Profile Information</h3>
                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <p>Created: {{ $animal->created_at->format('M j, Y') }}</p>
                            <p>Last Updated: {{ $animal->updated_at->format('M j, Y') }}</p>
                            <p>Current Photos: {{ count($existingImages) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
</div>