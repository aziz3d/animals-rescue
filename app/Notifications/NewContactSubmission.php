<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactSubmission extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Contact $contact
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Contact Form Submission - ' . $this->contact->subject)
            ->greeting('New Contact Message Received')
            ->line('A new contact form submission has been received on the Lovely Paws Rescue website.')
            ->line('')
            ->line('**Contact Details:**')
            ->line('Name: ' . $this->contact->name)
            ->line('Email: ' . $this->contact->email)
            ->line('Subject: ' . $this->contact->subject)
            ->line('')
            ->line('**Message:**')
            ->line($this->contact->message)
            ->line('')
            ->action('View in Admin Panel', route('admin.contacts.show', $this->contact))
            ->line('Please respond to this inquiry as soon as possible.')
            ->salutation('Best regards, Lovely Paws Rescue System');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'contact_id' => $this->contact->id,
            'contact_name' => $this->contact->name,
            'contact_email' => $this->contact->email,
            'contact_subject' => $this->contact->subject,
            'submitted_at' => $this->contact->created_at,
        ];
    }
}