<?php

use App\Models\Setting;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    public $payment_provider = '';
    public $payment_test_mode = false;
    public $payment_api_key = '';
    public $payment_webhook_secret = '';
    public $payment_currency = '';
    public $payment_fee_coverage = false;

    public function mount()
    {
        $this->payment_provider = Setting::get('payment_provider', 'stripe');
        $this->payment_test_mode = (bool) Setting::get('payment_test_mode', '1');
        $this->payment_api_key = Setting::get('payment_api_key', '');
        $this->payment_webhook_secret = Setting::get('payment_webhook_secret', '');
        $this->payment_currency = Setting::get('payment_currency', 'USD');
        $this->payment_fee_coverage = (bool) Setting::get('payment_fee_coverage', '1');
    }

    public function save()
    {
        $this->validate([
            'payment_provider' => 'required|in:stripe,paypal,square',
            'payment_currency' => 'required|in:USD,EUR,GBP,CAD',
            'payment_api_key' => 'nullable|string|max:255',
            'payment_webhook_secret' => 'nullable|string|max:255',
        ]);

        Setting::set('payment_provider', $this->payment_provider, 'select', 'payment');
        Setting::set('payment_test_mode', $this->payment_test_mode ? '1' : '0', 'boolean', 'payment');
        Setting::set('payment_api_key', $this->payment_api_key, 'password', 'payment');
        Setting::set('payment_webhook_secret', $this->payment_webhook_secret, 'password', 'payment');
        Setting::set('payment_currency', $this->payment_currency, 'select', 'payment');
        Setting::set('payment_fee_coverage', $this->payment_fee_coverage ? '1' : '0', 'boolean', 'payment');

        Setting::clearCache();
        
        $this->dispatch('saved');
        session()->flash('message', 'Payment settings saved successfully!');
    }
    
    public function toJSON()
    {
        return json_encode([
            'component' => 'admin.settings.payment',
            'id' => $this->getId()
        ]);
    }
}; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Payment Settings</h1>
            <p class="text-gray-600 dark:text-gray-400">Configure payment gateway and processing</p>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('message'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif
    
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Payment Gateway Configuration</h2>
        
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <flux:field>
                        <flux:label>Payment Provider</flux:label>
                        <flux:description>Select your payment processing provider</flux:description>
                        <select wire:model="payment_provider" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select payment provider</option>
                            <option value="stripe">Stripe</option>
                            <option value="paypal">PayPal</option>
                            <option value="square">Square</option>
                        </select>
                        @error('payment_provider')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Currency</flux:label>
                        <flux:description>Default currency for donations</flux:description>
                        <select wire:model="payment_currency" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select currency</option>
                            <option value="USD">USD - US Dollar</option>
                            <option value="EUR">EUR - Euro</option>
                            <option value="GBP">GBP - British Pound</option>
                            <option value="CAD">CAD - Canadian Dollar</option>
                        </select>
                        @error('payment_currency')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                </div>
            </div>

            <div>
                <flux:field>
                    <flux:label>API Key</flux:label>
                    <flux:description>Your payment provider's API key (keep secure)</flux:description>
                    <input type="password" wire:model="payment_api_key" placeholder="Enter API key" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('payment_api_key')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div>
                <flux:field>
                    <flux:label>Webhook Secret</flux:label>
                    <flux:description>Webhook secret for secure payment notifications</flux:description>
                    <input type="password" wire:model="payment_webhook_secret" placeholder="Enter webhook secret" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('payment_webhook_secret')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <flux:label>Test Mode</flux:label>
                    <flux:description>Enable test/sandbox mode for payments</flux:description>
                </div>
                <input type="checkbox" wire:model="payment_test_mode" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <flux:label>Processing Fee Coverage</flux:label>
                    <flux:description>Allow donors to cover processing fees</flux:description>
                </div>
                <input type="checkbox" wire:model="payment_fee_coverage" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-zinc-700">
                <flux:button type="submit" variant="primary">Save Settings</flux:button>
            </div>
        </form>
        </div>
    </div>
</div>