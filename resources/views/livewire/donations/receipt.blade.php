

<div class="bg-amber-50 py-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto bg-white border border-gray-200 rounded-lg shadow-sm">
    <!-- Receipt Header -->
    <div class="bg-amber-600 text-white p-6 rounded-t-lg">
        <div class="text-center">
            <h1 class="text-2xl font-bold">Lovely Paws Rescue</h1>
            <p class="text-amber-100">Donation Receipt</p>
        </div>
    </div>

    <!-- Receipt Content -->
    <div class="p-6">
        <!-- Receipt Info -->
        <div class="mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Receipt #{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</h2>
                    <p class="text-gray-600">{{ $donation->created_at->format('F j, Y') }}</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-amber-600">${{ number_format($donation->amount, 2) }}</div>
                    <div class="text-sm text-gray-500">{{ ucfirst(str_replace('-', ' ', $donation->type)) }}</div>
                </div>
            </div>
        </div>

        <!-- Donor Information -->
        <div class="mb-6">
            <h3 class="text-md font-semibold text-gray-800 mb-3">Donor Information</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Name</label>
                        <div class="text-gray-800">{{ $donation->donor_name }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <div class="text-gray-800">{{ $donation->donor_email }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donation Details -->
        <div class="mb-6">
            <h3 class="text-md font-semibold text-gray-800 mb-3">Donation Details</h3>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-gray-600">Donation Amount:</span>
                    <span class="font-medium">${{ number_format($donation->amount, 2) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-gray-600">Donation Type:</span>
                    <span class="font-medium">{{ ucfirst(str_replace('-', ' ', $donation->type)) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-gray-600">Payment Method:</span>
                    <span class="font-medium">{{ ucfirst($donation->payment_method) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-gray-600">Transaction Date:</span>
                    <span class="font-medium">{{ $donation->created_at->format('M j, Y g:i A') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-gray-600">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ ucfirst($donation->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Tax Information -->
        <div class="mb-6 bg-blue-50 rounded-lg p-4">
            <h3 class="text-md font-semibold text-blue-800 mb-2">Tax Information</h3>
            <p class="text-sm text-blue-700 mb-2">
                Lovely Paws Rescue is a registered 501(c)(3) nonprofit organization. 
                Your donation may be tax-deductible to the extent allowed by law.
            </p>
            <p class="text-xs text-blue-600">
                Tax ID: XX-XXXXXXX (Placeholder - would be actual EIN in real implementation)
            </p>
        </div>

        <!-- Thank You Message -->
        <div class="mb-6 text-center">
            <div class="bg-amber-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-amber-800 mb-2">Thank You!</h3>
                <p class="text-amber-700">
                    Your generous donation of ${{ number_format($donation->amount, 2) }} will help us continue our mission 
                    of rescuing and caring for animals in need. We are grateful for your support!
                </p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-3">
            <button wire:click="downloadPdf" 
                    class="flex-1 bg-amber-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-amber-700 transition-colors duration-200">
                Download PDF
            </button>
            <button wire:click="emailReceipt" 
                    class="flex-1 bg-gray-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-700 transition-colors duration-200">
                Email Receipt
            </button>
        </div>

        <!-- Footer -->
        <div class="mt-6 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
            <p>Lovely Paws Rescue | [Address] | [Phone] | [Email]</p>
            <p class="mt-1">Thank you for supporting animal rescue!</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('show-pdf-message', () => {
            alert('In a real implementation, a PDF receipt would be generated and downloaded.');
        });
        
        Livewire.on('show-email-message', () => {
            alert('In a real implementation, the receipt would be emailed to the donor.');
        });
    });
</script>
        </div>
    </div>
</div>