<x-mail::message>
# Thank you for contacting Lovely Paws Rescue

Hello {{ $contact->name }},

Thank you for reaching out to us regarding "{{ $contact->subject }}". We appreciate your interest in our rescue work.

{{ $replyMessage }}

---

**Your Original Message:**
{{ $contact->message }}

---

If you have any additional questions, please don't hesitate to contact us.

<x-mail::button :url="route('contact')">
Contact Us Again
</x-mail::button>

Best regards,<br>
The Lovely Paws Rescue Team

**Contact Information:**
- Phone: (555) 123-PAWS
- Email: info@lovelypawsrescue.org
- Emergency: (555) 123-HELP

<x-mail::subcopy>
This email was sent in response to your inquiry submitted on {{ $contact->created_at->format('M j, Y \a\t g:i A') }}.
</x-mail::subcopy>
</x-mail::message>