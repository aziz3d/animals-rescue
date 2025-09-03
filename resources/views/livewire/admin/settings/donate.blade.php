<?php

use App\Models\Setting;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    public $donate_default_amounts = '';
    public $donate_min_amount = '';
    public $donate_max_amount = '';
    public $donate_enable_recurring = false;
    public $donate_thank_you_message = '';
    public $donate_send_receipts = false;
    public $donate_impact_25 = '';
    public $donate_impact_75 = '';
    public $donate_impact_150 = '';
    public $donate_enable_paypal = false;
    public $donate_enable_stripe = true;
    public $amounts = [];
    public $new_amount = '';
    
    // Page Content Settings
    public $donate_page_title = '';
    public $donate_page_subtitle = '';
    public $donate_form_title = '';
    public $donate_impact_section_title = '';
    
    // Dynamic Impact Items
    public $impact_items = [];
    public $new_impact_amount = '';
    public $new_impact_title = '';
    public $new_impact_description = '';

    public function mount()
    {
        $this->donate_default_amounts = Setting::get('donate_default_amounts', '25,50,100,250,500');
        $this->amounts = array_filter(explode(',', $this->donate_default_amounts));
        $this->donate_min_amount = Setting::get('donate_min_amount', '5');
        $this->donate_max_amount = Setting::get('donate_max_amount', '10000');
        $this->donate_enable_recurring = (bool) Setting::get('donate_enable_recurring', '1');
        $this->donate_thank_you_message = Setting::get('donate_thank_you_message', 'Thank you for your generous donation! Your support helps us rescue, rehabilitate, and rehome animals in need.');
        $this->donate_send_receipts = (bool) Setting::get('donate_send_receipts', '1');
        
        // Legacy impact messages (for backward compatibility)
        $this->donate_impact_25 = Setting::get('donate_impact_25', 'Provides nutritious meals for one animal for a week');
        $this->donate_impact_75 = Setting::get('donate_impact_75', 'Covers basic veterinary checkup and vaccinations');
        $this->donate_impact_150 = Setting::get('donate_impact_150', 'Complete care from rescue to adoption readiness');
        
        $this->donate_enable_paypal = (bool) Setting::get('donate_enable_paypal', '1');
        $this->donate_enable_stripe = (bool) Setting::get('donate_enable_stripe', '1');
        
        // Page Content Settings
        $this->donate_page_title = Setting::get('donate_page_title', 'Support Our Mission');
        $this->donate_page_subtitle = Setting::get('donate_page_subtitle', 'Your generous donation helps us rescue, rehabilitate, and rehome animals in need. Every contribution makes a difference in an animal\'s life.');
        $this->donate_form_title = Setting::get('donate_form_title', 'Make a Donation');
        $this->donate_impact_section_title = Setting::get('donate_impact_section_title', 'Your Impact');
        
        // Dynamic Impact Items
        $impactItemsJson = Setting::get('donate_impact_items', '[]');
        $this->impact_items = json_decode($impactItemsJson, true) ?: [
            ['amount' => 25, 'title' => 'Feeds an Animal', 'description' => 'Provides nutritious meals for one animal for a week'],
            ['amount' => 75, 'title' => 'Medical Care', 'description' => 'Covers basic veterinary checkup and vaccinations'],
            ['amount' => 150, 'title' => 'Full Rescue', 'description' => 'Complete care from rescue to adoption readiness']
        ];
    }

    public function addAmount()
    {
        if ($this->new_amount && is_numeric($this->new_amount) && $this->new_amount > 0) {
            $amount = (int) $this->new_amount;
            if (!in_array($amount, $this->amounts)) {
                $this->amounts[] = $amount;
                sort($this->amounts);
                $this->updateAmountsString();
            }
            $this->new_amount = '';
        }
    }

    public function removeAmount($index)
    {
        if (isset($this->amounts[$index])) {
            unset($this->amounts[$index]);
            $this->amounts = array_values($this->amounts);
            $this->updateAmountsString();
        }
    }

    private function updateAmountsString()
    {
        $this->donate_default_amounts = implode(',', $this->amounts);
    }

    public function addImpactItem()
    {
        if ($this->new_impact_amount && $this->new_impact_title && $this->new_impact_description) {
            $this->impact_items[] = [
                'amount' => (int) $this->new_impact_amount,
                'title' => $this->new_impact_title,
                'description' => $this->new_impact_description
            ];
            
            // Sort by amount
            usort($this->impact_items, function($a, $b) {
                return $a['amount'] <=> $b['amount'];
            });
            
            $this->new_impact_amount = '';
            $this->new_impact_title = '';
            $this->new_impact_description = '';
        }
    }

    public function removeImpactItem($index)
    {
        if (isset($this->impact_items[$index])) {
            unset($this->impact_items[$index]);
            $this->impact_items = array_values($this->impact_items);
        }
    }

    public function save()
    {
        $this->validate([
            'donate_default_amounts' => 'required|string|max:255',
            'donate_min_amount' => 'required|numeric|min:1|max:1000',
            'donate_max_amount' => 'required|numeric|min:10|max:100000',
            'donate_thank_you_message' => 'required|string|max:1000',
            'donate_page_title' => 'required|string|max:255',
            'donate_page_subtitle' => 'required|string|max:500',
            'donate_form_title' => 'required|string|max:255',
            'donate_impact_section_title' => 'required|string|max:255',
        ]);

        Setting::set('donate_default_amounts', $this->donate_default_amounts, 'text', 'donate');
        Setting::set('donate_min_amount', $this->donate_min_amount, 'number', 'donate');
        Setting::set('donate_max_amount', $this->donate_max_amount, 'number', 'donate');
        Setting::set('donate_enable_recurring', $this->donate_enable_recurring ? '1' : '0', 'boolean', 'donate');
        Setting::set('donate_thank_you_message', $this->donate_thank_you_message, 'textarea', 'donate');
        Setting::set('donate_send_receipts', $this->donate_send_receipts ? '1' : '0', 'boolean', 'donate');
        Setting::set('donate_enable_paypal', $this->donate_enable_paypal ? '1' : '0', 'boolean', 'donate');
        Setting::set('donate_enable_stripe', $this->donate_enable_stripe ? '1' : '0', 'boolean', 'donate');
        
        // Page Content Settings
        Setting::set('donate_page_title', $this->donate_page_title, 'text', 'donate');
        Setting::set('donate_page_subtitle', $this->donate_page_subtitle, 'textarea', 'donate');
        Setting::set('donate_form_title', $this->donate_form_title, 'text', 'donate');
        Setting::set('donate_impact_section_title', $this->donate_impact_section_title, 'text', 'donate');
        
        // Dynamic Impact Items
        Setting::set('donate_impact_items', json_encode($this->impact_items), 'json', 'donate');

        Setting::clearCache();
        
        $this->dispatch('saved');
        session()->flash('message', 'Donate settings saved successfully!');
    }
    
    public function toJSON()
    {
        return json_encode([
            'component' => 'admin.settings.donate',
            'id' => $this->getId()
        ]);
    }
}; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Donate Settings</h1>
            <p class="text-gray-600 dark:text-gray-400">Configure donation settings and messaging</p>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('message'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif
    
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Donation Configuration</h2>
        
        <form wire:submit="save" class="space-y-6">
            <!-- Page Content Section -->
            <div class="mb-8 pb-6 border-b border-gray-200 dark:border-zinc-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Page Content</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Customize the main content of the donation page</p>
                
                <div class="space-y-6">
                    <div>
                        <flux:field>
                            <flux:label>Page Title</flux:label>
                            <flux:description>Main heading displayed at the top of the donation page</flux:description>
                            <input wire:model="donate_page_title" placeholder="Support Our Mission" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @error('donate_page_title')
                                <flux:error>{{ $message }}</flux:error>
                            @enderror
                        </flux:field>
                    </div>

                    <div>
                        <flux:field>
                            <flux:label>Page Subtitle</flux:label>
                            <flux:description>Descriptive text shown below the main title</flux:description>
                            <textarea wire:model="donate_page_subtitle" rows="3" placeholder="Your generous donation helps us rescue, rehabilitate, and rehome animals in need..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            @error('donate_page_subtitle')
                                <flux:error>{{ $message }}</flux:error>
                            @enderror
                        </flux:field>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <flux:field>
                                <flux:label>Impact Section Title</flux:label>
                                <flux:description>Title for the impact section</flux:description>
                                <input wire:model="donate_impact_section_title" placeholder="Your Impact" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                @error('donate_impact_section_title')
                                    <flux:error>{{ $message }}</flux:error>
                                @enderror
                            </flux:field>
                        </div>

                        <div>
                            <flux:field>
                                <flux:label>Donation Form Title</flux:label>
                                <flux:description>Title for the donation form section</flux:description>
                                <input wire:model="donate_form_title" placeholder="Make a Donation" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                @error('donate_form_title')
                                    <flux:error>{{ $message }}</flux:error>
                                @enderror
                            </flux:field>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <flux:field>
                    <flux:label>Default Donation Amounts</flux:label>
                    <flux:description>Manage the suggested donation amounts shown to users</flux:description>
                    
                    <!-- Current Amounts -->
                    <div class="mb-4">
                        <div class="flex flex-wrap gap-2 mb-3">
                            @foreach($amounts as $index => $amount)
                                <div class="flex items-center bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-sm">
                                    <span>${{ $amount }}</span>
                                    <button type="button" 
                                            wire:click="removeAmount({{ $index }})"
                                            class="ml-2 text-amber-600 hover:text-amber-800 focus:outline-none">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            @if(empty($amounts))
                                <p class="text-gray-500 text-sm">No amounts configured</p>
                            @endif
                        </div>
                        
                        <!-- Add New Amount -->
                        <div class="flex items-center space-x-2">
                            <div class="flex-1">
                                <input type="number" 
                                       wire:model="new_amount"
                                       wire:keydown.enter="addAmount"
                                       placeholder="Enter amount (e.g., 25)"
                                       min="1"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                            <button type="button" 
                                    wire:click="addAmount"
                                    class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                Add
                            </button>
                        </div>
                    </div>
                    
                    <!-- Hidden field for form submission -->
                    <input type="hidden" wire:model="donate_default_amounts" />
                    @error('donate_default_amounts')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div>
                <flux:field>
                    <flux:label>Thank You Message</flux:label>
                    <flux:description>Message displayed after successful donation</flux:description>
                    <textarea wire:model="donate_thank_you_message" rows="4" placeholder="Thank you for your generous donation..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    @error('donate_thank_you_message')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <flux:label>Enable Recurring Donations</flux:label>
                    <flux:description>Allow monthly recurring donations (future feature)</flux:description>
                </div>
                <input type="checkbox" wire:model="donate_enable_recurring" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <flux:label>Send Email Receipts</flux:label>
                    <flux:description>Automatically send donation receipts via email</flux:description>
                </div>
                <input type="checkbox" wire:model="donate_send_receipts" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
            </div>

            <!-- Dynamic Impact Items Section -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-zinc-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Impact Items</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Manage the impact items shown on the donation page</p>
                
                <!-- Current Impact Items -->
                <div class="mb-6">
                    <div class="space-y-3 mb-4">
                        @foreach($impact_items as $index => $item)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-700 rounded-lg border border-gray-200 dark:border-zinc-600">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-sm font-medium">
                                        ${{ $item['amount'] }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $item['title'] }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $item['description'] }}</div>
                                    </div>
                                </div>
                                <button type="button" 
                                        wire:click="removeImpactItem({{ $index }})"
                                        class="text-red-600 hover:text-red-800 focus:outline-none">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 102 0v3a1 1 0 11-2 0V9zm4 0a1 1 0 10-2 0v3a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                        @if(empty($impact_items))
                            <p class="text-gray-500 text-sm text-center py-4">No impact items configured</p>
                        @endif
                    </div>
                    
                    <!-- Add New Impact Item -->
                    <div class="bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-600 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Add New Impact Item</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount ($)</label>
                                <input type="number" 
                                       wire:model="new_impact_amount"
                                       placeholder="25"
                                       min="1"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                                <input type="text" 
                                       wire:model="new_impact_title"
                                       placeholder="Feeds an Animal"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                <input type="text" 
                                       wire:model="new_impact_description"
                                       placeholder="Provides nutritious meals..."
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="button" 
                                    wire:click="addImpactItem"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Add Impact Item
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods Section -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-zinc-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Methods</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Enable or disable payment methods for donations</p>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <flux:label>Enable Stripe (Credit/Debit Cards)</flux:label>
                            <flux:description>Accept credit and debit card payments via Stripe</flux:description>
                        </div>
                        <input type="checkbox" wire:model="donate_enable_stripe" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <flux:label>Enable PayPal</flux:label>
                            <flux:description>Accept payments via PayPal</flux:description>
                        </div>
                        <input type="checkbox" wire:model="donate_enable_paypal" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-zinc-700">
                <flux:button type="submit" variant="primary">Save Settings</flux:button>
            </div>
        </form>
    </div>
</div>