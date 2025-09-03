<?php

namespace App\Livewire\Animals;

use App\Models\Animal;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $species = '';
    public $age = '';
    public $size = '';
    public $breed = '';
    public $adoption_status = '';
    public $view_mode = 'grid';
    public $per_page = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'species' => ['except' => ''],
        'age' => ['except' => ''],
        'size' => ['except' => ''],
        'breed' => ['except' => ''],
        'adoption_status' => ['except' => ''],
        'view_mode' => ['except' => 'grid'],
    ];

    public function mount()
    {
        $this->adoption_status = 'available';
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSpecies()
    {
        $this->resetPage();
    }

    public function updatedAge()
    {
        $this->resetPage();
    }

    public function updatedSize()
    {
        $this->resetPage();
    }

    public function updatedBreed()
    {
        $this->resetPage();
    }

    public function updatedAdoptionStatus()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->species = '';
        $this->age = '';
        $this->size = '';
        $this->breed = '';
        $this->adoption_status = 'available';
        $this->resetPage();
    }

    public function toggleViewMode()
    {
        $this->view_mode = $this->view_mode === 'grid' ? 'list' : 'grid';
    }

    public function render()
    {
        $query = Animal::query();

        // Apply filters
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('breed', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->species) {
            $query->where('species', $this->species);
        }

        if ($this->age) {
            switch ($this->age) {
                case 'puppy_kitten':
                    $query->where('age', '<=', 1);
                    break;
                case 'young':
                    $query->whereBetween('age', [2, 3]);
                    break;
                case 'adult':
                    $query->whereBetween('age', [4, 7]);
                    break;
                case 'senior':
                    $query->where('age', '>=', 8);
                    break;
            }
        }

        if ($this->size) {
            $query->where('size', $this->size);
        }

        if ($this->breed) {
            $query->where('breed', 'like', '%' . $this->breed . '%');
        }

        if ($this->adoption_status) {
            $query->where('adoption_status', $this->adoption_status);
        }

        $animals = $query->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($this->per_page);

        // Get unique values for filter dropdowns
        $species_options = Animal::distinct()->pluck('species')->filter()->sort();
        $breed_options = Animal::distinct()->pluck('breed')->filter()->sort();

        return view('livewire.animals.index', [
            'animals' => $animals,
            'species_options' => $species_options,
            'breed_options' => $breed_options,
        ])->layout('components.layouts.public', ['title' => 'Animals - Lovely Paws Rescue']);
    }
}
