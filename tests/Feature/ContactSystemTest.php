<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use App\Notifications\NewContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ContactSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_model_can_be_created()
    {
        $contact = Contact::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Adoption Inquiry',
            'message' => 'I am interested in adopting a pet.',
            'status' => 'new',
        ]);

        $this->assertDatabaseHas('contacts', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Adoption Inquiry',
            'status' => 'new',
        ]);

        $this->assertEquals('new', $contact->status);
    }

    public function test_contact_status_methods_work()
    {
        $contact = Contact::factory()->create(['status' => 'new']);

        $contact->markAsRead();
        $this->assertEquals('read', $contact->fresh()->status);

        $contact->markAsReplied();
        $this->assertEquals('replied', $contact->fresh()->status);
    }

    public function test_contact_scopes_work()
    {
        Contact::factory()->newStatus()->create();
        Contact::factory()->read()->create();
        Contact::factory()->replied()->create();

        $this->assertEquals(1, Contact::new()->count());
        $this->assertEquals(1, Contact::read()->count());
        $this->assertEquals(1, Contact::replied()->count());
    }

    public function test_notification_can_be_created()
    {
        $contact = Contact::factory()->create();
        $user = User::factory()->create();

        $notification = new NewContactSubmission($contact);
        
        $this->assertEquals($contact->id, $notification->contact->id);
    }

    public function test_contact_factory_works()
    {
        $contact = Contact::factory()->create();
        
        $this->assertNotNull($contact->name);
        $this->assertNotNull($contact->email);
        $this->assertNotNull($contact->subject);
        $this->assertNotNull($contact->message);
        $this->assertContains($contact->status, ['new', 'read', 'replied']);
    }

    public function test_contact_factory_states_work()
    {
        $newContact = Contact::factory()->newStatus()->create();
        $readContact = Contact::factory()->read()->create();
        $repliedContact = Contact::factory()->replied()->create();

        $this->assertEquals('new', $newContact->status);
        $this->assertEquals('read', $readContact->status);
        $this->assertEquals('replied', $repliedContact->status);
    }
}