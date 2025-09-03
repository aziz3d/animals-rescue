<?php

use App\Models\Contact;
use App\Models\User;
use App\Notifications\NewContactSubmission;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Notification;

new class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $subject = '';
    public string $message = '';
    public bool $subscribe = false;
    
    public bool $submitted = false;

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'subscribe' => 'boolean',
        ];
    }

    public function submit()
    {
        $this->validate();

        $contact = Contact::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
            'status' => 'new',
        ]);

        // Send notification to all users (since no role system is implemented)
        $users = User::all();

        // Send notification to users
        if ($users->isNotEmpty()) {
            Notification::send($users, new NewContactSubmission($contact));
        }

        // Reset form
        $this->reset(['name', 'email', 'subject', 'message', 'subscribe']);
        $this->submitted = true;
    }

    public function resetForm()
    {
        $this->submitted = false;
    }
}; ?>

<div>
    <div class="bg-amber-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-amber-800 mb-4">
                    Contact Us
                </h1>
                <p class="text-lg text-amber-700 max-w-2xl mx-auto">
                    Have questions about adoption, volunteering, or our rescue work? 
                    We'd love to hear from you and help in any way we can.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    @if($submitted)
                        <div class="text-center py-8">
                            <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-green-800 mb-4">Message Sent Successfully!</h2>
                            <p class="text-green-700 mb-6">
                                {{ setting('contact_auto_reply', 'Thank you for contacting us! We will respond to your message within 24 hours.') }}
                            </p>
                            <button wire:click="resetForm" 
                                    class="bg-amber-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-amber-700 transition-colors duration-200">
                                Send Another Message
                            </button>
                        </div>
                    @else
                        <h2 class="text-2xl font-bold text-amber-800 mb-6">Send Us a Message</h2>
                        
                        <form wire:submit="submit" class="space-y-6" novalidate>
                            <div>
                                <label for="contact-name" class="block text-sm font-medium text-amber-700 mb-2">
                                    Full Name <span class="text-red-500" aria-label="required">*</span>
                                </label>
                                <input type="text" 
                                       id="contact-name"
                                       wire:model="name" 
                                       required 
                                       autocomplete="name"
                                       aria-describedby="@error('name') name-error @enderror"
                                       class="w-full border border-amber-200 rounded-md px-3 py-2 focus-visible @error('name') error-field @enderror">
                                @error('name')
                                    <p class="error-message" id="name-error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contact-email" class="block text-sm font-medium text-amber-700 mb-2">
                                    Email Address <span class="text-red-500" aria-label="required">*</span>
                                </label>
                                <input type="email" 
                                       id="contact-email"
                                       wire:model="email" 
                                       required 
                                       autocomplete="email"
                                       aria-describedby="@error('email') email-error @enderror"
                                       class="w-full border border-amber-200 rounded-md px-3 py-2 focus-visible @error('email') error-field @enderror">
                                @error('email')
                                    <p class="error-message" id="email-error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contact-subject" class="block text-sm font-medium text-amber-700 mb-2">
                                    Subject <span class="text-red-500" aria-label="required">*</span>
                                </label>
                                <select id="contact-subject"
                                        wire:model="subject" 
                                        required 
                                        aria-describedby="@error('subject') subject-error @enderror"
                                        class="w-full border border-amber-200 rounded-md px-3 py-2 focus-visible @error('subject') error-field @enderror">
                                    <option value="">Please select a subject</option>
                                    <option value="Adoption Inquiry">Adoption Inquiry</option>
                                    <option value="Volunteer Information">Volunteer Information</option>
                                    <option value="Donation Questions">Donation Questions</option>
                                    <option value="Animal Surrender">Animal Surrender</option>
                                    <option value="General Information">General Information</option>
                                    <option value="Other">Other</option>
                                </select>
                                @error('subject')
                                    <p class="error-message" id="subject-error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contact-message" class="block text-sm font-medium text-amber-700 mb-2">
                                    Message <span class="text-red-500" aria-label="required">*</span>
                                </label>
                                <textarea id="contact-message"
                                          rows="6" 
                                          wire:model="message" 
                                          required 
                                          placeholder="Please provide details about your inquiry..."
                                          aria-describedby="@error('message') message-error @enderror message-help"
                                          class="w-full border border-amber-200 rounded-md px-3 py-2 focus-visible @error('message') error-field @enderror"></textarea>
                                <p id="message-help" class="text-xs text-gray-500 mt-1">Maximum 5000 characters</p>
                                @error('message')
                                    <p class="error-message" id="message-error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="flex items-start">
                                    <input type="checkbox" 
                                           id="contact-subscribe"
                                           wire:model="subscribe"
                                           class="text-amber-600 focus-visible mt-1 touch-target">
                                    <span class="ml-2 text-gray-700 text-sm">
                                        I would like to receive updates about Lovely Paws Rescue
                                    </span>
                                </label>
                            </div>

                            <div class="pt-4">
                                <button type="submit" 
                                        class="w-full bg-amber-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-amber-700 transition-colors duration-200 disabled:opacity-50 focus-visible touch-target"
                                        wire:loading.attr="disabled"
                                        aria-describedby="submit-help">
                                    <span wire:loading.remove>Send Message</span>
                                    <span wire:loading class="flex items-center justify-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Sending...
                                    </span>
                                </button>
                                <p id="submit-help" class="text-xs text-gray-500 mt-2 text-center">
                                    We typically respond within 24-48 hours during business days.
                                </p>
                            </div>
                        </form>
                    @endif
                </div>

                <!-- Contact Information -->
                <div class="space-y-8">
                    <!-- Organization Info -->
                    <div class="bg-white rounded-lg shadow-md p-8">
                        <h2 class="text-2xl font-bold text-amber-800 mb-6">Get in Touch</h2>
                        
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="bg-amber-100 rounded-full w-12 h-12 flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-amber-800 mb-1">Address</h3>
                                    <p class="text-gray-700">
                                        {!! nl2br(e(setting('contact_address', "123 Main Street\nCity, State 12345"))) !!}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="bg-amber-100 rounded-full w-12 h-12 flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-amber-800 mb-1">Phone</h3>
                                    <p class="text-gray-700">
                                        {{ setting('contact_phone', '(555) 123-4567') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="bg-amber-100 rounded-full w-12 h-12 flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-amber-800 mb-1">Email</h3>
                                    <p class="text-gray-700">
                                        {{ setting('contact_email', 'contact@lovelypawsrescue.org') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="bg-amber-100 rounded-full w-12 h-12 flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-amber-800 mb-1">Hours</h3>
                                    <p class="text-gray-700">
                                        {!! nl2br(e(setting('contact_hours', "Monday - Friday: 9:00 AM - 5:00 PM\nSaturday: 10:00 AM - 3:00 PM\nSunday: Closed"))) !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Google Maps -->
                    @if(setting('contact_map_enabled', '0') && setting('contact_map_latitude') && setting('contact_map_longitude'))
                        <div class="bg-white rounded-lg shadow-md p-8">
                            <h3 class="text-xl font-bold text-amber-800 mb-4">Find Us</h3>
                            <div class="h-64 rounded-lg overflow-hidden">
                                <iframe 
                                    src="https://www.openstreetmap.org/export/embed.html?bbox={{ setting('contact_map_longitude') - 0.01 }},{{ setting('contact_map_latitude') - 0.01 }},{{ setting('contact_map_longitude') + 0.01 }},{{ setting('contact_map_latitude') + 0.01 }}&layer=mapnik&marker={{ setting('contact_map_latitude') }},{{ setting('contact_map_longitude') }}"
                                    width="100%" 
                                    height="100%" 
                                    frameborder="0" 
                                    scrolling="no" 
                                    marginheight="0" 
                                    marginwidth="0"
                                    title="Location Map">
                                </iframe>
                            </div>
                            <p class="text-sm text-gray-600 mt-4">
                                We're easily accessible by public transportation and have plenty of parking available.
                            </p>
                            <div class="mt-4 flex justify-center">
                                <a href="https://www.google.com/maps?q={{ setting('contact_map_latitude') }},{{ setting('contact_map_longitude') }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    Open in Google Maps
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Emergency Contact -->
                    @if(setting('emergency_enabled', '1'))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                            <h3 class="text-lg font-bold text-red-800 mb-2">Emergency Animal Situations</h3>
                            <p class="text-red-700 mb-3">
                                {{ setting('emergency_text', "If you've found an injured animal or need immediate assistance, please call our emergency line:") }}
                            </p>
                            <p class="text-xl font-bold text-red-800">{{ setting('emergency_phone', '(555) 123-HELP') }}</p>
                            <p class="text-sm text-red-600 mt-2">
                                Available 24/7 for emergency situations
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- FAQ Section -->
            @if(setting('faq_enabled', '1'))
                @php
                    $faqs = json_decode(setting('contact_faqs', '[]'), true) ?? [];
                @endphp
                
                @if(count($faqs) > 0)
                    <div class="mt-16 bg-white rounded-lg shadow-md p-8">
                        <h2 class="text-2xl font-bold text-amber-800 mb-8 text-center">Frequently Asked Questions</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach($faqs as $faq)
                                @if(!empty($faq['question']) && !empty($faq['answer']))
                                    <div>
                                        <h3 class="font-semibold text-amber-800 mb-2">{{ $faq['question'] }}</h3>
                                        <p class="text-gray-700 text-sm mb-4">
                                            {{ $faq['answer'] }}
                                        </p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        
                        <div class="text-center mt-8">
                            <p class="text-gray-600">
                                Don't see your question answered here? 
                                <span class="text-amber-600 font-medium">Send us a message above</span> 
                                and we'll get back to you soon!
                            </p>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>