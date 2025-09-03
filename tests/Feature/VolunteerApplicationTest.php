<?php

namespace Tests\Feature;

use App\Models\Volunteer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class VolunteerApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function volunteer_index_page_loads_successfully()
    {
        $response = $this->get('/volunteer');
        
        $response->assertStatus(200);
        $response->assertSee('Volunteer With Us');
        $response->assertSee('Apply to Volunteer');
    }

    /** @test */
    public function volunteer_application_page_loads_successfully()
    {
        $response = $this->get('/volunteer/apply');
        
        $response->assertStatus(200);
        $response->assertSee('Volunteer Application');
        $response->assertSee('Personal Information');
    }

    /** @test */
    public function can_navigate_through_multi_step_form()
    {
        Livewire::test('volunteer.application')
            ->assertSee('Personal Information')
            ->set('first_name', 'John')
            ->set('last_name', 'Doe')
            ->set('email', 'john@example.com')
            ->set('phone', '555-1234')
            ->call('nextStep')
            ->assertSee('Interests & Availability')
            ->set('areas_of_interest', ['animal-care'])
            ->set('availability_days', ['weekdays'])
            ->set('hours_per_week', '3-5')
            ->call('nextStep')
            ->assertSee('Experience & Agreement');
    }

    /** @test */
    public function can_submit_complete_volunteer_application()
    {
        $this->assertDatabaseCount('volunteers', 0);

        Livewire::test('volunteer.application')
            ->set('first_name', 'Jane')
            ->set('last_name', 'Smith')
            ->set('email', 'jane@example.com')
            ->set('phone', '555-5678')
            ->set('address', '123 Main St')
            ->set('city', 'Anytown')
            ->set('state', 'CA')
            ->set('zip_code', '12345')
            ->set('areas_of_interest', ['animal-care', 'adoption-support'])
            ->set('availability_days', ['weekends', 'evenings'])
            ->set('hours_per_week', '6-10')
            ->set('experience', 'I have volunteered at other shelters before.')
            ->set('motivation', 'I love animals and want to help.')
            ->set('commitment_agreement', true)
            ->set('background_check_agreement', true)
            ->call('submit')
            ->assertSee('Application Submitted Successfully!');

        $this->assertDatabaseCount('volunteers', 1);
        
        $volunteer = Volunteer::first();
        $this->assertEquals('Jane Smith', $volunteer->name);
        $this->assertEquals('jane@example.com', $volunteer->email);
        $this->assertEquals('555-5678', $volunteer->phone);
        $this->assertEquals(['animal-care', 'adoption-support'], $volunteer->areas_of_interest);
        $this->assertEquals('pending', $volunteer->status);
    }

    /** @test */
    public function form_validation_works_correctly()
    {
        Livewire::test('volunteer.application')
            ->call('nextStep')
            ->assertHasErrors(['first_name', 'last_name', 'email', 'phone']);

        Livewire::test('volunteer.application')
            ->set('first_name', 'John')
            ->set('last_name', 'Doe')
            ->set('email', 'john@example.com')
            ->set('phone', '555-1234')
            ->call('nextStep')
            ->call('nextStep')
            ->assertHasErrors(['areas_of_interest', 'availability_days', 'hours_per_week']);
    }

    /** @test */
    public function can_navigate_backwards_through_steps()
    {
        Livewire::test('volunteer.application')
            ->set('first_name', 'John')
            ->set('last_name', 'Doe')
            ->set('email', 'john@example.com')
            ->set('phone', '555-1234')
            ->call('nextStep')
            ->assertSee('Interests & Availability')
            ->call('previousStep')
            ->assertSee('Personal Information');
    }

    /** @test */
    public function required_agreements_are_enforced()
    {
        Livewire::test('volunteer.application')
            ->set('first_name', 'John')
            ->set('last_name', 'Doe')
            ->set('email', 'john@example.com')
            ->set('phone', '555-1234')
            ->set('areas_of_interest', ['animal-care'])
            ->set('availability_days', ['weekdays'])
            ->set('hours_per_week', '3-5')
            ->call('submit')
            ->assertHasErrors(['commitment_agreement', 'background_check_agreement']);
    }
}