<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Animal;
use Livewire\Livewire;

class AnimalListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_animals_index_page_loads_successfully()
    {
        // Create some test animals
        Animal::factory()->create([
            'name' => 'Test Dog',
            'species' => 'dog',
            'adoption_status' => 'available'
        ]);

        $response = $this->get('/animals');
        $response->assertStatus(200);
        $response->assertSee('Animals Looking for Homes');
    }

    public function test_animal_filtering_works()
    {
        // Create test animals with different species
        Animal::factory()->create([
            'name' => 'Test Dog',
            'species' => 'dog',
            'adoption_status' => 'available'
        ]);

        Animal::factory()->create([
            'name' => 'Test Cat',
            'species' => 'cat',
            'adoption_status' => 'available'
        ]);

        Livewire::test('animals.index')
            ->set('species', 'dog')
            ->assertSee('Test Dog')
            ->assertDontSee('Test Cat');
    }

    public function test_animal_search_works()
    {
        Animal::factory()->create([
            'name' => 'Buddy the Golden',
            'breed' => 'Golden Retriever',
            'adoption_status' => 'available'
        ]);

        Animal::factory()->create([
            'name' => 'Max the German',
            'breed' => 'German Shepherd',
            'adoption_status' => 'available'
        ]);

        Livewire::test('animals.index')
            ->set('search', 'Golden')
            ->assertSee('Buddy the Golden')
            ->assertDontSee('Max the German');
    }

    public function test_animal_detail_page_loads()
    {
        $animal = Animal::factory()->create([
            'name' => 'Test Animal',
            'description' => 'A wonderful pet',
            'adoption_status' => 'available'
        ]);

        $response = $this->get("/animals/{$animal->id}");
        $response->assertStatus(200);
        $response->assertSee('Test Animal');
        $response->assertSee('A wonderful pet');
    }

    public function test_view_mode_toggle_works()
    {
        Animal::factory()->create(['adoption_status' => 'available']);

        Livewire::test('animals.index')
            ->assertSet('view_mode', 'grid')
            ->call('toggleViewMode')
            ->assertSet('view_mode', 'list');
    }

    public function test_adoption_inquiry_form_validation()
    {
        $animal = Animal::factory()->create(['adoption_status' => 'available']);

        Livewire::test('animals.show', ['animal' => $animal])
            ->set('showInquiryForm', true)
            ->set('inquiry_name', '')
            ->set('inquiry_email', 'invalid-email')
            ->set('inquiry_message', '')
            ->call('submitInquiry')
            ->assertHasErrors(['inquiry_name', 'inquiry_email', 'inquiry_message']);
    }

    public function test_adoption_inquiry_form_submission()
    {
        $animal = Animal::factory()->create(['adoption_status' => 'available']);

        Livewire::test('animals.show', ['animal' => $animal])
            ->set('showInquiryForm', true)
            ->set('inquiry_name', 'John Doe')
            ->set('inquiry_email', 'john@example.com')
            ->set('inquiry_message', 'I would like to adopt this animal.')
            ->call('submitInquiry')
            ->assertHasNoErrors()
            ->assertSet('showInquiryForm', false);
    }
}