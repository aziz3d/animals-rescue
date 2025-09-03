<?php

namespace App\Livewire\Donations;

use App\Models\Donation;
use Livewire\Component;

class Index extends Component
{
    public $donor_name = '';
    public $donor_email = '';
    public $amount = '';
    public $custom_amount = '';
    public $type = 'one-time';
    public $payment_method = 'stripe';
    public $selected_amount = '';
    
    // Credit Card Fields
    public $card_number = '';
    public $card_expiry = '';
    public $card_cvc = '';
    public $card_name = '';
    
    // PayPal Fields
    public $paypal_email = '';
    
    // Show payment forms
    public $show_payment_form = false;

    protected $rules = [
        'donor_name' => 'required|string|max:255',
        'donor_email' => 'required|email|max:255',
        'amount' => 'required|numeric|min:5|max:10000',
        'type' => 'required|in:one-time,recurring',
        'payment_method' => 'required|in:stripe,paypal',
    ];

    protected function getPaymentRules()
    {
        $rules = [];
        
        if ($this->payment_method === 'stripe') {
            $rules = [
                'card_number' => 'required|string|min:13|max:19',
                'card_expiry' => 'required|string|regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/',
                'card_cvc' => 'required|string|min:3|max:4',
                'card_name' => 'required|string|max:255',
            ];
        } elseif ($this->payment_method === 'paypal') {
            $rules = [
                'paypal_email' => 'required|email|max:255',
            ];
        }
        
        return array_merge($this->rules, $rules);
    }

    public function mount()
    {
        // Set default values from settings
        $this->rules['amount'] = 'required|numeric|min:' . setting('donate_min_amount', '5') . '|max:' . setting('donate_max_amount', '10000');
        
        // Set default payment method based on enabled methods
        $enableStripe = setting('donate_enable_stripe', '1');
        $enablePaypal = setting('donate_enable_paypal', '1');
        
        if ($enableStripe) {
            $this->payment_method = 'stripe';
            $this->show_payment_form = true;
        } elseif ($enablePaypal) {
            $this->payment_method = 'paypal';
            $this->show_payment_form = true;
        }
    }

    public function selectAmount($amount)
    {
        $this->selected_amount = $amount;
        $this->amount = $amount;
        $this->custom_amount = '';
    }

    public function updatedAmount()
    {
        // This will trigger when amount changes to update the button text
    }

    public function updatedCustomAmount()
    {
        if ($this->custom_amount) {
            $this->amount = $this->custom_amount;
            $this->selected_amount = '';
        }
    }

    public function updatedPaymentMethod()
    {
        $this->show_payment_form = !empty($this->payment_method);
        $this->resetPaymentFields();
    }

    private function resetPaymentFields()
    {
        $this->card_number = '';
        $this->card_expiry = '';
        $this->card_cvc = '';
        $this->card_name = '';
        $this->paypal_email = '';
    }

    public function submit()
    {
        // Validate enabled payment methods
        $enabledMethods = [];
        if (setting('donate_enable_stripe', '1')) {
            $enabledMethods[] = 'stripe';
        }
        if (setting('donate_enable_paypal', '1')) {
            $enabledMethods[] = 'paypal';
        }
        
        $this->rules['payment_method'] = 'required|in:' . implode(',', $enabledMethods);
        
        // Get payment-specific validation rules
        $rules = $this->getPaymentRules();
        
        $this->validate($rules);

        // In a real implementation, you would process the payment here
        // For now, we'll just create the donation record
        
        $donation = Donation::create([
            'donor_name' => $this->donor_name,
            'donor_email' => $this->donor_email,
            'amount' => $this->amount,
            'type' => $this->type,
            'payment_method' => $this->payment_method,
            'status' => 'pending',
            'transaction_id' => 'TXN_' . strtoupper(uniqid()), // Mock transaction ID
        ]);

        // Store donation ID in session for confirmation page
        session(['donation_id' => $donation->id]);

        // Redirect to confirmation page
        return redirect()->route('donate.confirmation');
    }

    public function render()
    {
        $defaultAmounts = explode(',', setting('donate_default_amounts', '25,50,100,250,500'));
        $enableRecurring = setting('donate_enable_recurring', '1');
        $minAmount = setting('donate_min_amount', '5');
        $maxAmount = setting('donate_max_amount', '10000');

        return view('livewire.donations.index', [
            'defaultAmounts' => $defaultAmounts,
            'enableRecurring' => $enableRecurring,
            'minAmount' => $minAmount,
            'maxAmount' => $maxAmount,
        ])->layout('components.layouts.public', [
            'title' => 'Donate - Support Our Mission - Lovely Paws Rescue'
        ]);
    }
}