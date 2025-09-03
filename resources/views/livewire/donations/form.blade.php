<?php

use Livewire\Volt\Component;
use App\Models\Donation;
use Livewire\Attributes\Validate;

new class extends Component
{
    #[Validate('required|in:one-time,recurring')]
    public string $type = 'one-time';
    
    #[Validate('required|numeric|min:1|max:10000')]
    public string $amount = '';
    
    #[Validate('required|string|max:255')]
    public string $donor_name = '';
    
    #[Validate('required|email|max:255')]
    public string $donor_email = '';
    
    #[Validate('required|in:stripe,paypal')]
    public string $payment_method = 'stripe';
    
    public bool $receive_updates = false;
    public bool $anonymous = false;
    
    public array $preset_amounts = [25, 50, 100, 250];
    public string $selected_preset = '';

    public function selectAmount($amount)
    {
        $this->amount = (string) $amount;
        $this->selected_preset = (string) $amount;
    }

    public function updatedAmount()
    {
        $this->selected_preset = '';
    }

    public function submit()
    {
        $this->validate();

        // Create donation record
        $donation = Donation::create([
            'donor_name' => $this->anonymous ? 'Anonymous' : $this->donor_name,
            'donor_email' => $this->donor_email,
            'amount' => $this->amount,
            'type' => $this->type,
            'status' => 'pending',
            'payment_method' => $this->payment_method,
        ]);

        // In a real implementation, this would redirect to payment processor
        // For now, we'll simulate the process and redirect to confirmation
        session()->flash('donation_id', $donation->id);
        
        return $this->redirect(route('donate.confirmation'), navigate: true);
    }
}; ?>

<div class="bg-white rounded-lg shadow-md p-8">
    <h2 class="text-2xl font-bold text-amber-800 mb-6">Make a Donation</h2>
    
    <form wire:submit="submit" class="space-y-6">
        <!-- Donation Type -->
        <fieldset>
            <legend class="block text-sm font-medium text-amber-700 mb-3">Donation Type</legend>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4" role="radiogroup" aria-describedby="@error('type') type-error @enderror">
                <label class="flex items-center touch-target">
                    <input type="radio" 
                           wire:model.live="type" 
                           value="one-time" 
                           class="text-amber-600 focus-visible touch-target">
                    <span class="ml-2 text-gray-700">One-time</span>
                </label>
                <label class="flex items-center touch-target">
                    <input type="radio" 
                           wire:model.live="type" 
                           value="recurring" 
                           class="text-amber-600 focus-visible touch-target">
                    <span class="ml-2 text-gray-700">{{ $type === 'recurring' ? 'Monthly Recurring' : 'Monthly' }}</span>
                </label>
            </div>
            @error('type') 
                <p class="error-message" id="type-error" role="alert">{{ $message }}</p> 
            @enderror
        </fieldset>

        <!-- Donation Amount -->
        <div>
            <label for="donation-amount" class="block text-sm font-medium text-amber-700 mb-3">Donation Amount</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4" role="group" aria-label="Preset donation amounts">
                @foreach($preset_amounts as $preset)
                    <button type="button" 
                            wire:click="selectAmount({{ $preset }})"
                            class="border-2 {{ $selected_preset == $preset ? 'border-amber-500 bg-amber-50' : 'border-amber-200' }} text-amber-700 px-4 py-3 rounded-lg hover:border-amber-400 hover:bg-amber-50 transition-colors duration-200 focus-visible touch-target"
                            aria-pressed="{{ $selected_preset == $preset ? 'true' : 'false' }}">
                        ${{ $preset }}
                    </button>
                @endforeach
            </div>
            <div class="flex items-center">
                <label for="donation-amount" class="text-gray-500 mr-2">$</label>
                <input type="number" 
                       id="donation-amount"
                       wire:model.live="amount"
                       placeholder="Enter custom amount" 
                       min="1" 
                       max="10000"
                       step="0.01"
                       aria-describedby="@error('amount') amount-error @enderror amount-help"
                       class="flex-1 border border-amber-200 rounded-md px-3 py-2 focus-visible @error('amount') error-field @enderror">
            </div>
            <p id="amount-help" class="text-xs text-gray-500 mt-1">Minimum donation: $1, Maximum: $10,000</p>
            @error('amount') 
                <p class="error-message" id="amount-error" role="alert">{{ $message }}</p> 
            @enderror
        </div>

        <!-- Donor Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-amber-700 mb-2">Full Name</label>
                <input type="text" 
                       wire:model="donor_name"
                       required 
                       class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('donor_name') border-red-500 @enderror">
                @error('donor_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-amber-700 mb-2">Email Address</label>
                <input type="email" 
                       wire:model="donor_email"
                       required 
                       class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('donor_email') border-red-500 @enderror">
                @error('donor_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Payment Method -->
        <div>
            <label class="block text-sm font-medium text-amber-700 mb-3">Payment Method</label>
            <div class="space-y-3">
                <div class="border border-amber-200 rounded-lg p-4 {{ $payment_method === 'stripe' ? 'ring-2 ring-amber-500' : '' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="radio" 
                                   wire:model.live="payment_method" 
                                   value="stripe" 
                                   class="text-amber-600 focus:ring-amber-500">
                            <span class="ml-2 text-gray-700">Credit/Debit Card</span>
                        </div>
                        <div class="flex space-x-2">
                            <div class="w-8 h-5 bg-blue-600 rounded text-white text-xs flex items-center justify-center">VISA</div>
                            <div class="w-8 h-5 bg-red-600 rounded text-white text-xs flex items-center justify-center">MC</div>
                        </div>
                    </div>
                    <div class="mt-3 text-sm text-gray-600">
                        Secure payment processing via Stripe (Integration placeholder)
                    </div>
                </div>
                
                <div class="border border-amber-200 rounded-lg p-4 {{ $payment_method === 'paypal' ? 'ring-2 ring-amber-500' : '' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="radio" 
                                   wire:model.live="payment_method" 
                                   value="paypal" 
                                   class="text-amber-600 focus:ring-amber-500">
                            <span class="ml-2 text-gray-700">PayPal</span>
                        </div>
                        <div class="text-blue-600 font-bold text-sm">PayPal</div>
                    </div>
                    <div class="mt-3 text-sm text-gray-600">
                        Pay securely with your PayPal account (Integration placeholder)
                    </div>
                </div>
            </div>
            @error('payment_method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Additional Options -->
        <div class="space-y-3">
            <label class="flex items-center">
                <input type="checkbox" 
                       wire:model="receive_updates"
                       class="text-amber-600 focus:ring-amber-500">
                <span class="ml-2 text-gray-700">I would like to receive updates about the animals I help</span>
            </label>
            <label class="flex items-center">
                <input type="checkbox" 
                       wire:model="anonymous"
                       class="text-amber-600 focus:ring-amber-500">
                <span class="ml-2 text-gray-700">Make this donation anonymous</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" 
                    wire:loading.attr="disabled"
                    class="w-full bg-amber-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-amber-700 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove>
                    Complete Donation{{ $amount ? ' - $' . number_format($amount, 2) : '' }}
                </span>
                <span wire:loading>
                    Processing...
                </span>
            </button>
            <p class="text-xs text-gray-500 mt-2 text-center">
                Your donation is secure and will be processed safely. You will receive a receipt via email.
            </p>
        </div>
    </form>
</div>