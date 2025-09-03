<?php

namespace Tests\Feature;

use App\Models\Donation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class DonationTest extends TestCase
{
    use RefreshDatabase;

    public function test_donation_page_loads_successfully()
    {
        $response = $this->get(route('donate'));
        
        $response->assertStatus(200);
        $response->assertSee('Support Our Mission');
        $response->assertSee('Make a Donation');
    }

    public function test_donation_form_validation_works()
    {
        Volt::test('donations.form')
            ->set('amount', '')
            ->set('donor_name', '')
            ->set('donor_email', 'invalid-email')
            ->call('submit')
            ->assertHasErrors(['amount', 'donor_name', 'donor_email']);
    }

    public function test_donation_form_submission_creates_donation()
    {
        Volt::test('donations.form')
            ->set('amount', '50.00')
            ->set('donor_name', 'John Doe')
            ->set('donor_email', 'john@example.com')
            ->set('type', 'one-time')
            ->set('payment_method', 'stripe')
            ->call('submit')
            ->assertRedirect(route('donate.confirmation'));

        $this->assertDatabaseHas('donations', [
            'donor_name' => 'John Doe',
            'donor_email' => 'john@example.com',
            'amount' => '50.00',
            'type' => 'one-time',
            'payment_method' => 'stripe',
            'status' => 'pending'
        ]);
    }

    public function test_anonymous_donation_hides_donor_name()
    {
        Volt::test('donations.form')
            ->set('amount', '25.00')
            ->set('donor_name', 'Jane Doe')
            ->set('donor_email', 'jane@example.com')
            ->set('anonymous', true)
            ->call('submit');

        $this->assertDatabaseHas('donations', [
            'donor_name' => 'Anonymous',
            'donor_email' => 'jane@example.com',
            'amount' => '25.00'
        ]);
    }

    public function test_recurring_donation_type_is_saved()
    {
        Volt::test('donations.form')
            ->set('amount', '100.00')
            ->set('donor_name', 'Bob Smith')
            ->set('donor_email', 'bob@example.com')
            ->set('type', 'recurring')
            ->call('submit');

        $this->assertDatabaseHas('donations', [
            'type' => 'recurring'
        ]);
    }

    public function test_confirmation_page_displays_donation_details()
    {
        $donation = Donation::create([
            'donor_name' => 'Test Donor',
            'donor_email' => 'test@example.com',
            'amount' => 75.00,
            'type' => 'one-time',
            'status' => 'pending',
            'payment_method' => 'paypal'
        ]);

        $response = $this->withSession(['donation_id' => $donation->id])
                         ->get(route('donate.confirmation'));

        $response->assertStatus(200);
        $response->assertSee('Thank You for Your Donation!');
        $response->assertSee('$75.00');
        $response->assertSee('Test Donor');
    }

    public function test_receipt_page_displays_donation_receipt()
    {
        $donation = Donation::create([
            'donor_name' => 'Receipt Test',
            'donor_email' => 'receipt@example.com',
            'amount' => 150.00,
            'type' => 'recurring',
            'status' => 'completed',
            'payment_method' => 'stripe'
        ]);

        $response = $this->get(route('donate.receipt', $donation));

        $response->assertStatus(200);
        $response->assertSee('Donation Receipt');
        $response->assertSee('Receipt Test');
        $response->assertSee('$150.00');
        $response->assertSee('Recurring');
    }

    public function test_preset_amount_selection_works()
    {
        Volt::test('donations.form')
            ->call('selectAmount', 100)
            ->assertSet('amount', '100')
            ->assertSet('selected_preset', '100');
    }

    public function test_custom_amount_clears_preset_selection()
    {
        Volt::test('donations.form')
            ->set('selected_preset', '50')
            ->set('amount', '75')
            ->assertSet('selected_preset', '');
    }
}