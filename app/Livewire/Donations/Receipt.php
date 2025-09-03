<?php

namespace App\Livewire\Donations;

use App\Models\Donation;
use Livewire\Component;

class Receipt extends Component
{
    public Donation $donation;

    public function mount(Donation $donation)
    {
        $this->donation = $donation;
    }

    public function downloadPdf()
    {
        // In a real implementation, this would generate and download a PDF
        $this->dispatch('show-pdf-message');
    }

    public function emailReceipt()
    {
        // In a real implementation, this would send an email receipt
        $this->dispatch('show-email-message');
    }

    public function render()
    {
        return view('livewire.donations.receipt')
            ->layout('components.layouts.public', [
                'title' => 'Donation Receipt #' . str_pad($this->donation->id, 6, '0', STR_PAD_LEFT) . ' - Lovely Paws Rescue'
            ]);
    }
}