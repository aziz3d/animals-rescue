

<div class="min-h-screen bg-amber-50 py-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($donation_found && $donation)
            <!-- Success Message -->
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="mb-6">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                
                <h1 class="text-3xl font-bold text-amber-800 mb-4">Thank You for Your Donation!</h1>
                <p class="text-lg text-gray-600 mb-8">
                    Your generous contribution of <strong>${{ number_format($donation->amount, 2) }}</strong> 
                    will make a real difference in the lives of rescued animals.
                </p>

                <!-- Donation Details -->
                <div class="bg-amber-50 rounded-lg p-6 mb-8 text-left">
                    <h2 class="text-xl font-semibold text-amber-800 mb-4">Donation Details</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Donation ID:</span>
                            <span class="font-medium">#{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Amount:</span>
                            <span class="font-medium">${{ number_format($donation->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Type:</span>
                            <span class="font-medium">{{ ucfirst(str_replace('-', ' ', $donation->type)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Method:</span>
                            <span class="font-medium">{{ ucfirst($donation->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date:</span>
                            <span class="font-medium">{{ $donation->created_at->format('M j, Y g:i A') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst($donation->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Receipt Actions -->
                <div class="space-y-4 mb-8">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('donate.receipt', $donation) }}" 
                           class="flex-1 bg-amber-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-amber-700 transition-colors duration-200 text-center">
                            View Full Receipt
                        </a>
                        <button wire:click="downloadReceipt" 
                                class="flex-1 bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition-colors duration-200">
                            Download PDF
                        </button>
                    </div>
                    <p class="text-sm text-gray-500">
                        A receipt has been sent to {{ $donation->donor_email }}
                    </p>
                </div>

                <!-- Impact Message -->
                <div class="bg-blue-50 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">Your Impact</h3>
                    <p class="text-blue-700">
                        @if($donation->amount >= 150)
                            Your donation can cover the complete care for one animal from rescue to adoption readiness!
                        @elseif($donation->amount >= 75)
                            Your donation can cover basic veterinary checkup and vaccinations for a rescued animal.
                        @elseif($donation->amount >= 25)
                            Your donation can provide nutritious meals for an animal for several weeks.
                        @else
                            Every dollar helps us provide better care for animals in need.
                        @endif
                    </p>
                </div>

                @if($donation->type === 'recurring')
                    <!-- Recurring Donation Info -->
                    <div class="bg-amber-100 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-amber-800 mb-2">Monthly Recurring Donation</h3>
                        <p class="text-amber-700 mb-3">
                            You've set up a monthly recurring donation of ${{ number_format($donation->amount, 2) }}. 
                            This will help us provide consistent care for our rescued animals.
                        </p>
                        <p class="text-sm text-amber-600">
                            You can manage or cancel your recurring donation at any time by contacting us.
                        </p>
                    </div>
                @endif

                <!-- Next Steps -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-amber-800">What's Next?</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('animals.index') }}" 
                           class="block p-4 border border-amber-200 rounded-lg hover:border-amber-400 hover:bg-amber-50 transition-colors duration-200">
                            <div class="text-center">
                                <div class="text-2xl mb-2">🐕</div>
                                <div class="font-medium text-amber-800">Meet Our Animals</div>
                                <div class="text-sm text-gray-600">See who you're helping</div>
                            </div>
                        </a>
                        <a href="{{ route('stories.index') }}" 
                           class="block p-4 border border-amber-200 rounded-lg hover:border-amber-400 hover:bg-amber-50 transition-colors duration-200">
                            <div class="text-center">
                                <div class="text-2xl mb-2">📖</div>
                                <div class="font-medium text-amber-800">Success Stories</div>
                                <div class="text-sm text-gray-600">Read about our impact</div>
                            </div>
                        </a>
                        <a href="{{ route('volunteer') }}" 
                           class="block p-4 border border-amber-200 rounded-lg hover:border-amber-400 hover:bg-amber-50 transition-colors duration-200">
                            <div class="text-center">
                                <div class="text-2xl mb-2">🤝</div>
                                <div class="font-medium text-amber-800">Volunteer</div>
                                <div class="text-sm text-gray-600">Donate your time too</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Error Message -->
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="mb-6">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                
                <h1 class="text-3xl font-bold text-red-800 mb-4">Donation Not Found</h1>
                <p class="text-lg text-gray-600 mb-8">
                    We couldn't find your donation information. This might happen if you navigated here directly 
                    or if there was an issue with the donation process.
                </p>

                <div class="space-y-4">
                    <a href="{{ route('donate') }}" 
                       class="inline-block bg-amber-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-amber-700 transition-colors duration-200">
                        Make a Donation
                    </a>
                    <div>
                        <a href="{{ route('contact') }}" class="text-amber-600 hover:text-amber-800">
                            Contact us if you need help
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('show-receipt-message', () => {
            alert('In a real implementation, a PDF receipt would be downloaded here.');
        });
    });
</script>