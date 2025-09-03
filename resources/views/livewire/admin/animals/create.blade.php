<?php

use App\Models\Animal;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    use WithFileUploads;

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
    public array $images = [];
    public array $uploadedImages = [];

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
            'uploadedImages.*' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048', // 2MB max per image
        ];
    }

    public function save()
    {
        $this->validate();

        // Handle image uploads
        $imagePaths = [];
        foreach ($this->uploadedImages as $image) {
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
                        $imagePaths[] = $relativePath;
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

        // Only create the animal if we have successfully uploaded images or no images were provided
        try {
            $animal = Animal::create([
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
                'images' => $imagePaths,
            ]);

            \Log::info('Animal created successfully', [
                'animal_id' => $animal->id,
                'name' => $animal->name,
                'images_count' => count($imagePaths),
                'images' => $imagePaths
            ]);

            session()->flash('success', "Animal '{$animal->name}' has been created successfully!");

            return redirect()->route('admin.animals.index');
        } catch (\Exception $e) {
            \Log::error('Failed to create animal', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Failed to create animal: ' . $e->getMessage());
            return;
        }
    }

    public function removeUploadedImage($index)
    {
        unset($this->uploadedImages[$index]);
        $this->uploadedImages = array_values($this->uploadedImages);
    }
}; ?>

<div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add New Animal</h1>
                    <p class="text-gray-600 dark:text-gray-400">Create a new animal profile for adoption</p>
                </div>
            </div>
        </div>

        <!-- Back Link -->
        <div class="mb-6">
            <flux:button href="{{ route('admin.animals.index') }}" variant="ghost" size="sm">
                <flux:icon.arrow-left class="w-4 h-4 mr-2" />
                Back to Animals
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
                        
                        <div class="space-y-4">
                            <div>
                                <flux:field>
                                    <flux:label>Upload Images</flux:label>
                                    <flux:input type="file" wire:model="uploadedImages" multiple accept="image/*" />
                                    <flux:description>Upload multiple images. Maximum 2MB per image. First image will be the main photo.</flux:description>
                                    <flux:error name="uploadedImages.*" />
                                </flux:field>
                            </div>

                            <!-- Image Previews -->
                            @if($uploadedImages)
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach($uploadedImages as $index => $image)
                                        @if($image)
                                            <div class="relative">
                                                @if(is_object($image) && method_exists($image, 'temporaryUrl'))
                                                    @try
                                                        <img 
                                                            src="{{ $image->temporaryUrl() }}" 
                                                            alt="Preview" 
                                                            class="w-full aspect-square object-cover rounded-lg border border-gray-200 dark:border-zinc-700"
                                                        >
                                                    @catch(\Exception $e)
                                                        <div class="w-full aspect-square bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-zinc-700 flex items-center justify-center">
                                                            <p class="text-gray-500 text-sm">Image preview loading...</p>
                                                        </div>
                                                    @endtry
                                                @else
                                                    <div class="w-full aspect-square bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-zinc-700 flex items-center justify-center">
                                                        <p class="text-gray-500 text-sm">Image selected</p>
                                                    </div>
                                                @endif
                                                <button 
                                                    type="button"
                                                    wire:click="removeUploadedImage({{ $index }})"
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
                                        @endif
                                    @endforeach
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
                                Create Animal Profile
                            </flux:button>

                            <flux:button href="{{ route('admin.animals.index') }}" variant="ghost" class="w-full">
                                <flux:icon.x-mark class="w-4 h-4 mr-2" />
                                Cancel
                            </flux:button>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-4">
                        <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">Tips for Great Profiles</h3>
                        <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                            <li>• Use high-quality, well-lit photos</li>
                            <li>• Write engaging, honest descriptions</li>
                            <li>• Include personality traits and preferences</li>
                            <li>• Mention any special needs or requirements</li>
                            <li>• Update status promptly when adopted</li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
</div>