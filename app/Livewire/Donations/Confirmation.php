<?php

namespace App\Livewire\Donations;

use App\Models\Donation;
use Livewire\Component;

class Confirmation extends Component
{
    public ?Donation $donation = null;
    public bool $donation_found = false;

    public function mount()
    {
        $donation_id = session('donation_id');
        
        if ($donation_id) {
            $this->donation = Donation::find($donation_id);
            $this->donation_found = $this->donation !== null;
            
            // Clear the session data
            session()->forget('donation_id');
            
            // In a real implementation, you might update the donation status here
            // after receiving confirmation from the payment processor
            if ($this->donation) {
                $this->donation->update(['status' => 'completed']);
            }
        }
    }

    public function downloadReceipt()
    {
        if (!$this->donation) {
            return;
        }

        // In a real implementation, this would generate a PDF receipt
        // For now, we'll just show a message
        $this->dispatch('show-receipt-message');
    }

    public function render()
    {
        return view('livewire.donations.confirmation')
            ->layout('components.layouts.public', [
                'title' => 'Donation Confirmation - Lovely Paws Rescue'
            ]);
    }
}