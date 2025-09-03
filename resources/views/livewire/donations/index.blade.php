

<div class="bg-amber-50 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-amber-800 mb-4">
                    {{ setting('donate_page_title', 'Support Our Mission') }}
                </h1>
                <p class="text-lg text-amber-700 max-w-2xl mx-auto">
                    {{ setting('donate_page_subtitle', 'Your generous donation helps us rescue, rehabilitate, and rehome animals in need. Every contribution makes a difference in an animal\'s life.') }}
                </p>
            </div>

            <!-- Impact Section -->
            @php
                $impactItems = json_decode(setting('donate_impact_items', '[]'), true) ?: [
                    ['amount' => 25, 'title' => 'Feeds an Animal', 'description' => 'Provides nutritious meals for one animal for a week'],
                    ['amount' => 75, 'title' => 'Medical Care', 'description' => 'Covers basic veterinary checkup and vaccinations'],
                    ['amount' => 150, 'title' => 'Full Rescue', 'description' => 'Complete care from rescue to adoption readiness']
                ];
            @endphp
            
            @if(!empty($impactItems))
            <div class="bg-white rounded-lg shadow-md p-8 mb-8">
                <h2 class="text-2xl font-bold text-amber-800 mb-6 text-center">{{ setting('donate_impact_section_title', 'Your Impact') }}</h2>
                <div class="grid grid-cols-1 {{ count($impactItems) == 1 ? 'md:grid-cols-1' : (count($impactItems) == 2 ? 'md:grid-cols-2' : 'md:grid-cols-3') }} gap-6">
                    @foreach($impactItems as $item)
                    <div class="text-center">
                        <div class="bg-amber-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-amber-600">${{ $item['amount'] }}</span>
                        </div>
                        <h3 class="font-semibold text-amber-800 mb-2">{{ $item['title'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $item['description'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Donation Form Component -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-amber-800 mb-6">{{ setting('donate_form_title', 'Make a Donation') }}</h2>
                
                <form wire:submit="submit" class="space-y-6">
                    <!-- Donation Type -->
                    @if($enableRecurring)
                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-3">Donation Type</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" wire:model="type" value="one-time" class="text-amber-600 focus:ring-amber-500">
                                <span class="ml-2 text-gray-700">One-time</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" wire:model="type" value="recurring" class="text-amber-600 focus:ring-amber-500">
                                <span class="ml-2 text-gray-700">Monthly recurring</span>
                            </label>
                        </div>
                    </div>
                    @endif

                    <!-- Amount Selection -->
                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-3">Donation Amount</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                            @foreach(array_slice($defaultAmounts, 0, 4) as $amount)
                            <button type="button" 
                                    wire:click="selectAmount({{ $amount }})"
                                    class="px-4 py-2 border rounded-md text-center transition-colors duration-200 
                                           {{ $selected_amount == $amount ? 'bg-amber-600 text-white border-amber-600' : 'border-amber-200 text-amber-700 hover:bg-amber-50' }}">
                                ${{ $amount }}
                            </button>
                            @endforeach
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-600">$</span>
                            <input type="number" 
                                   wire:model.live="custom_amount"
                                   placeholder="Enter custom amount" 
                                   min="{{ $minAmount }}"
                                   max="{{ $maxAmount }}"
                                   step="0.01"
                                   class="flex-1 border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Donor Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-amber-700 mb-2">Full Name</label>
                            <input type="text" 
                                   wire:model="donor_name"
                                   placeholder="Enter your full name"
                                   class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            @error('donor_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-amber-700 mb-2">Email Address</label>
                            <input type="email" 
                                   wire:model="donor_email"
                                   placeholder="Enter your email"
                                   class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            @error('donor_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-3">Payment Method</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if(setting('donate_enable_stripe', '1'))
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer transition-colors duration-200 
                                          {{ $payment_method === 'stripe' ? 'border-amber-500 bg-amber-50' : 'border-amber-200 hover:bg-amber-50' }}">
                                <input type="radio" wire:model.live="payment_method" value="stripe" class="text-amber-600 focus:ring-amber-500">
                                <div class="ml-3">
                                    <div class="font-medium text-gray-900">Credit/Debit Card</div>
                                    <div class="text-sm text-gray-500">Visa, Mastercard, American Express</div>
                                </div>
                            </label>
                            @endif
                            @if(setting('donate_enable_paypal', '1'))
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer transition-colors duration-200
                                          {{ $payment_method === 'paypal' ? 'border-amber-500 bg-amber-50' : 'border-amber-200 hover:bg-amber-50' }}">
                                <input type="radio" wire:model.live="payment_method" value="paypal" class="text-amber-600 focus:ring-amber-500">
                                <div class="ml-3">
                                    <div class="font-medium text-gray-900">PayPal</div>
                                    <div class="text-sm text-gray-500">Pay with your PayPal account</div>
                                </div>
                            </label>
                            @endif
                        </div>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Forms -->
                    @if($show_payment_form)
                        @if($payment_method === 'stripe')
                            <!-- Credit Card Form -->
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                    </svg>
                                    Credit Card Information
                                </h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Cardholder Name</label>
                                        <input type="text" 
                                               wire:model="card_name"
                                               placeholder="Full name as shown on card"
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                        @error('card_name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                                        <input type="text" 
                                               wire:model="card_number"
                                               placeholder="1234 5678 9012 3456"
                                               maxlength="19"
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                        @error('card_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                                            <input type="text" 
                                                   wire:model="card_expiry"
                                                   placeholder="MM/YY"
                                                   maxlength="5"
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                            @error('card_expiry')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">CVC</label>
                                            <input type="text" 
                                                   wire:model="card_cvc"
                                                   placeholder="123"
                                                   maxlength="4"
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                            @error('card_cvc')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Your payment information is encrypted and secure
                                </div>
                            </div>
                        @elseif($payment_method === 'paypal')
                            <!-- PayPal Form -->
                            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.26-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81.394.45.67.96.824 1.507z"/>
                                    </svg>
                                    PayPal Payment
                                </h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">PayPal Email Address</label>
                                        <input type="email" 
                                               wire:model="paypal_email"
                                               placeholder="your-email@example.com"
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('paypal_email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mt-4 p-4 bg-blue-100 rounded-md">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="text-sm text-blue-800">
                                            <p class="font-medium">You'll be redirected to PayPal to complete your donation</p>
                                            <p class="mt-1">Make sure you have access to the PayPal account associated with this email address.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full py-3 px-6 rounded-lg font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-colors duration-200
                                       {{ $amount && $amount > 0 ? 'bg-amber-600 text-white hover:bg-amber-700' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
                                @if(!$amount || $amount <= 0) disabled @endif>
                            @if($amount && $amount > 0)
                                @if($type === 'recurring')
                                    Donate ${{ number_format($amount, 0) }} Monthly
                                @else
                                    Donate ${{ number_format($amount, 0) }} Now
                                @endif
                            @else
                                Enter Amount to Continue
                            @endif
                        </button>
                    </div>

                    <!-- Security Notice -->
                    <div class="text-center text-sm text-gray-500">
                        <p>🔒 Your donation is secure and encrypted. We never store your payment information.</p>
                    </div>
                </form>
            </div>

            <!-- Other Ways to Help -->
            <div class="mt-12 bg-amber-100 rounded-lg p-8">
                <h2 class="text-2xl font-bold text-amber-800 mb-6 text-center">Other Ways to Help</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-amber-800 mb-2">Volunteer</h3>
                        <p class="text-gray-700 text-sm mb-3">Donate your time and skills to help animals directly</p>
                        <a href="{{ route('volunteer') }}" class="text-amber-600 hover:text-amber-800 font-medium">Learn More →</a>
                    </div>
                    <div class="text-center">
                        <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-amber-800 mb-2">Adopt</h3>
                        <p class="text-gray-700 text-sm mb-3">Give a rescued animal a loving forever home</p>
                        <a href="{{ route('animals.index') }}" class="text-amber-600 hover:text-amber-800 font-medium">View Animals →</a>
                    </div>
                    <div class="text-center">
                        <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-amber-800 mb-2">Share</h3>
                        <p class="text-gray-700 text-sm mb-3">Spread the word about our mission and available animals</p>
                        <a href="#" class="text-amber-600 hover:text-amber-800 font-medium">Share Now →</a>
                    </div>
                </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:init', () => {
    // Format card number input
    document.addEventListener('input', function(e) {
        if (e.target.matches('[wire\\:model="card_number"]')) {
            let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            if (formattedValue !== e.target.value) {
                e.target.value = formattedValue;
                e.target.dispatchEvent(new Event('input'));
            }
        }
        
        // Format expiry date input
        if (e.target.matches('[wire\\:model="card_expiry"]')) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            if (value !== e.target.value) {
                e.target.value = value;
                e.target.dispatchEvent(new Event('input'));
            }
        }
        
        // Format CVC input (numbers only)
        if (e.target.matches('[wire\\:model="card_cvc"]')) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            if (value !== e.target.value) {
                e.target.value = value;
                e.target.dispatchEvent(new Event('input'));
            }
        }
    });
});
</script>