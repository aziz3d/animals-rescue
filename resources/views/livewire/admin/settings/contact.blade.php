<?php

use App\Models\Setting;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public $contact_organization_name = '';
    public $contact_phone = '';
    public $contact_email = '';
    public $contact_address = '';
    public $contact_hours = '';
    public $contact_auto_reply = '';
    public $contact_enable_form = false;
    public $contact_require_phone = false;
    
    // Google Maps coordinates
    public $contact_map_latitude = '';
    public $contact_map_longitude = '';
    public $contact_map_enabled = false;
    
    // Emergency contact
    public $emergency_phone = '';
    public $emergency_text = '';
    public $emergency_enabled = false;
    
    // FAQ settings
    public $faq_enabled = false;
    public $faqs = [];

    public function mount()
    {
        $this->contact_organization_name = Setting::get('contact_organization_name', 'Lovely Paws Rescue');
        $this->contact_phone = Setting::get('contact_phone', '(555) 123-4567');
        $this->contact_email = Setting::get('contact_email', 'contact@lovelypawsrescue.org');
        $this->contact_address = Setting::get('contact_address', "123 Main Street\nCity, State 12345");
        $this->contact_hours = Setting::get('contact_hours', "Monday - Friday: 9:00 AM - 5:00 PM\nSaturday: 10:00 AM - 3:00 PM\nSunday: Closed");
        $this->contact_auto_reply = Setting::get('contact_auto_reply', 'Thank you for contacting us! We will respond to your message within 24 hours.');
        $this->contact_enable_form = (bool) Setting::get('contact_enable_form', '1');
        $this->contact_require_phone = (bool) Setting::get('contact_require_phone', '0');
        
        // Google Maps coordinates
        $this->contact_map_latitude = Setting::get('contact_map_latitude', '');
        $this->contact_map_longitude = Setting::get('contact_map_longitude', '');
        $this->contact_map_enabled = (bool) Setting::get('contact_map_enabled', '0');
        
        // Emergency contact
        $this->emergency_phone = Setting::get('emergency_phone', '(555) 123-HELP');
        $this->emergency_text = Setting::get('emergency_text', "If you've found an injured animal or need immediate assistance, please call our emergency line:");
        $this->emergency_enabled = (bool) Setting::get('emergency_enabled', '1');
        
        // FAQ settings
        $this->faq_enabled = (bool) Setting::get('faq_enabled', '1');
        $this->faqs = json_decode(Setting::get('contact_faqs', json_encode([
            ['question' => 'How do I adopt an animal?', 'answer' => 'Visit our animals page, find a pet you\'re interested in, and fill out an adoption application. We\'ll schedule a meet-and-greet to ensure it\'s a good match.'],
            ['question' => 'Can I volunteer if I have no experience?', 'answer' => 'Absolutely! We provide training for all volunteers. Your enthusiasm and willingness to help are more important than previous experience.'],
            ['question' => 'Do you accept animal surrenders?', 'answer' => 'We accept surrenders on a case-by-case basis depending on our capacity. Please contact us to discuss your situation and available options.'],
            ['question' => 'Are donations tax-deductible?', 'answer' => 'Yes! Lovely Paws Rescue is a registered 501(c)(3) nonprofit organization. You\'ll receive a receipt for tax purposes with every donation.']
        ])), true) ?? [];
    }

    public function save()
    {
        $this->validate([
            'contact_organization_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:50',
            'contact_email' => 'required|email|max:255',
            'contact_address' => 'required|string|max:500',
            'contact_hours' => 'required|string|max:500',
            'contact_auto_reply' => 'required|string|max:1000',
            'contact_map_latitude' => 'nullable|numeric|between:-90,90',
            'contact_map_longitude' => 'nullable|numeric|between:-180,180',
            'emergency_phone' => 'nullable|string|max:50',
            'emergency_text' => 'nullable|string|max:500',
            'faqs.*.question' => 'required|string|max:255',
            'faqs.*.answer' => 'required|string|max:1000',
        ]);

        Setting::set('contact_organization_name', $this->contact_organization_name, 'text', 'contact');
        Setting::set('contact_phone', $this->contact_phone, 'text', 'contact');
        Setting::set('contact_email', $this->contact_email, 'email', 'contact');
        Setting::set('contact_address', $this->contact_address, 'textarea', 'contact');
        Setting::set('contact_hours', $this->contact_hours, 'textarea', 'contact');
        Setting::set('contact_auto_reply', $this->contact_auto_reply, 'textarea', 'contact');
        Setting::set('contact_enable_form', $this->contact_enable_form ? '1' : '0', 'boolean', 'contact');
        Setting::set('contact_require_phone', $this->contact_require_phone ? '1' : '0', 'boolean', 'contact');
        
        // Google Maps settings
        Setting::set('contact_map_latitude', $this->contact_map_latitude, 'text', 'contact');
        Setting::set('contact_map_longitude', $this->contact_map_longitude, 'text', 'contact');
        Setting::set('contact_map_enabled', $this->contact_map_enabled ? '1' : '0', 'boolean', 'contact');
        
        // Emergency contact settings
        Setting::set('emergency_phone', $this->emergency_phone, 'text', 'contact');
        Setting::set('emergency_text', $this->emergency_text, 'textarea', 'contact');
        Setting::set('emergency_enabled', $this->emergency_enabled ? '1' : '0', 'boolean', 'contact');
        
        // FAQ settings
        Setting::set('faq_enabled', $this->faq_enabled ? '1' : '0', 'boolean', 'contact');
        Setting::set('contact_faqs', json_encode($this->faqs), 'json', 'contact');

        Setting::clearCache();
        
        $this->dispatch('saved');
        session()->flash('message', 'Contact settings saved successfully!');
    }

    public function addFaq()
    {
        $this->faqs[] = ['question' => '', 'answer' => ''];
    }

    public function removeFaq($index)
    {
        unset($this->faqs[$index]);
        $this->faqs = array_values($this->faqs);
    }

    public function toJSON()
    {
        return json_encode([
            'component' => 'admin.settings.contact',
            'id' => $this->getId(),
        ]);
    }
}; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Contact Page Settings</h1>
            <p class="text-gray-600 dark:text-gray-400">Configure organization contact information</p>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('message'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Contact Information</h2>

        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <flux:field>
                        <flux:label>Organization Name</flux:label>
                        <flux:description>Your organization's official name</flux:description>
                        <input wire:model="contact_organization_name" placeholder="Animal Rescue Organization" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('contact_organization_name')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Phone Number</flux:label>
                        <flux:description>Main contact phone number</flux:description>
                        <input wire:model="contact_phone" placeholder="(555) 123-4567" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('contact_phone')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                </div>
            </div>

            <div>
                <flux:field>
                    <flux:label>Email Address</flux:label>
                    <flux:description>Main contact email address</flux:description>
                    <input type="email" wire:model="contact_email" placeholder="contact@rescue.org" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('contact_email')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div>
                <flux:field>
                    <flux:label>Physical Address</flux:label>
                    <flux:description>Your organization's physical address</flux:description>
                    <textarea wire:model="contact_address" rows="3" placeholder="123 Main Street&#10;City, State 12345" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    @error('contact_address')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div>
                <flux:field>
                    <flux:label>Business Hours</flux:label>
                    <flux:description>Your organization's operating hours</flux:description>
                    <textarea wire:model="contact_hours" rows="4" placeholder="Monday - Friday: 9:00 AM - 5:00 PM&#10;Saturday: 10:00 AM - 3:00 PM&#10;Sunday: Closed" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    @error('contact_hours')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div>
                <flux:field>
                    <flux:label>Auto-reply Message</flux:label>
                    <flux:description>Automatic response message for contact form submissions</flux:description>
                    <textarea wire:model="contact_auto_reply" rows="4" placeholder="Thank you for contacting us. We will respond within 24 hours." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    @error('contact_auto_reply')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <flux:label>Enable Contact Form</flux:label>
                    <flux:description>Show contact form on contact page</flux:description>
                </div>
                <input type="checkbox" wire:model="contact_enable_form" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <flux:label>Require Phone Number</flux:label>
                    <flux:description>Make phone number required in contact form</flux:description>
                </div>
                <input type="checkbox" wire:model="contact_require_phone" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
            </div>

            <!-- Google Maps Section -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-zinc-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Google Maps Integration</h3>
                
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <flux:label>Enable Map Display</flux:label>
                        <flux:description>Show interactive map on contact page</flux:description>
                    </div>
                    <input type="checkbox" wire:model="contact_map_enabled" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <flux:field>
                            <flux:label>Latitude</flux:label>
                            <flux:description>GPS latitude coordinate (-90 to 90)</flux:description>
                            <input type="number" step="any" wire:model="contact_map_latitude" placeholder="40.7128" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @error('contact_map_latitude')
                                <flux:error>{{ $message }}</flux:error>
                            @enderror
                        </flux:field>
                    </div>

                    <div>
                        <flux:field>
                            <flux:label>Longitude</flux:label>
                            <flux:description>GPS longitude coordinate (-180 to 180)</flux:description>
                            <input type="number" step="any" wire:model="contact_map_longitude" placeholder="-74.0060" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @error('contact_map_longitude')
                                <flux:error>{{ $message }}</flux:error>
                            @enderror
                        </flux:field>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>Tip:</strong> You can find your coordinates by searching your address on Google Maps, right-clicking on your location, and selecting the coordinates that appear.
                    </p>
                </div>
            </div>

            <!-- Emergency Contact Section -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-zinc-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Emergency Contact</h3>
                
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <flux:label>Enable Emergency Section</flux:label>
                        <flux:description>Show emergency contact information on contact page</flux:description>
                    </div>
                    <input type="checkbox" wire:model="emergency_enabled" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                </div>

                <div class="space-y-4">
                    <div>
                        <flux:field>
                            <flux:label>Emergency Phone Number</flux:label>
                            <flux:description>24/7 emergency contact number</flux:description>
                            <input wire:model="emergency_phone" placeholder="(555) 123-HELP" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @error('emergency_phone')
                                <flux:error>{{ $message }}</flux:error>
                            @enderror
                        </flux:field>
                    </div>

                    <div>
                        <flux:field>
                            <flux:label>Emergency Description</flux:label>
                            <flux:description>Text explaining when to use the emergency contact</flux:description>
                            <textarea wire:model="emergency_text" rows="3" placeholder="If you've found an injured animal or need immediate assistance, please call our emergency line:" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            @error('emergency_text')
                                <flux:error>{{ $message }}</flux:error>
                            @enderror
                        </flux:field>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-zinc-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Frequently Asked Questions</h3>
                
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <flux:label>Enable FAQ Section</flux:label>
                        <flux:description>Show FAQ section on contact page</flux:description>
                    </div>
                    <input type="checkbox" wire:model="faq_enabled" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                </div>

                <div class="space-y-4">
                    @foreach($faqs as $index => $faq)
                        <div class="p-4 border border-gray-200 dark:border-zinc-600 rounded-lg">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="font-medium text-gray-900 dark:text-white">FAQ #{{ $index + 1 }}</h4>
                                <button type="button" wire:click="removeFaq({{ $index }})" class="text-red-600 hover:text-red-800 text-sm">
                                    Remove
                                </button>
                            </div>
                            
                            <div class="space-y-3">
                                <div>
                                    <flux:field>
                                        <flux:label>Question</flux:label>
                                        <input wire:model="faqs.{{ $index }}.question" placeholder="Enter the question..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                        @error("faqs.{$index}.question")
                                            <flux:error>{{ $message }}</flux:error>
                                        @enderror
                                    </flux:field>
                                </div>
                                
                                <div>
                                    <flux:field>
                                        <flux:label>Answer</flux:label>
                                        <textarea wire:model="faqs.{{ $index }}.answer" rows="3" placeholder="Enter the answer..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                        @error("faqs.{$index}.answer")
                                            <flux:error>{{ $message }}</flux:error>
                                        @enderror
                                    </flux:field>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <button type="button" wire:click="addFaq" class="w-full py-2 px-4 border-2 border-dashed border-gray-300 dark:border-zinc-600 rounded-lg text-gray-600 dark:text-gray-400 hover:border-gray-400 hover:text-gray-700 transition-colors">
                        + Add New FAQ
                    </button>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-zinc-700">
                <flux:button type="submit" variant="primary">Save Settings</flux:button>
            </div>
        </form>
        </div>
    </div>
</div>
