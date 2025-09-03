<x-layouts.public title="Donate - Lovely Paws Rescue">
    <div class="bg-amber-50 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-amber-800 mb-4">
                    Support Our Mission
                </h1>
                <p class="text-lg text-amber-700 max-w-2xl mx-auto">
                    Your generous donation helps us rescue, rehabilitate, and rehome animals in need. 
                    Every contribution makes a difference in an animal's life.
                </p>
            </div>

            <!-- Impact Section -->
            <div class="bg-white rounded-lg shadow-md p-8 mb-8">
                <h2 class="text-2xl font-bold text-amber-800 mb-6 text-center">Your Impact</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="bg-amber-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-amber-600">$25</span>
                        </div>
                        <h3 class="font-semibold text-amber-800 mb-2">Feeds an Animal</h3>
                        <p class="text-gray-600 text-sm">Provides nutritious meals for one animal for a week</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-amber-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-amber-600">$75</span>
                        </div>
                        <h3 class="font-semibold text-amber-800 mb-2">Medical Care</h3>
                        <p class="text-gray-600 text-sm">Covers basic veterinary checkup and vaccinations</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-amber-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-amber-600">$150</span>
                        </div>
                        <h3 class="font-semibold text-amber-800 mb-2">Full Rescue</h3>
                        <p class="text-gray-600 text-sm">Complete care from rescue to adoption readiness</p>
                    </div>
                </div>
            </div>

            <!-- Donation Form -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-amber-800 mb-6">Make a Donation</h2>
                
                <form class="space-y-6" id="donationForm">
                    <!-- Donation Type -->
                    @if(setting('donate_enable_recurring', '1'))
                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-3">Donation Type</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="donation_type" value="one-time" checked 
                                       class="text-amber-600 focus:ring-amber-500">
                                <span class="ml-2 text-gray-700">One-time</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="donation_type" value="monthly" 
                                       class="text-amber-600 focus:ring-amber-500">
                                <span class="ml-2 text-gray-700">Monthly</span>
                            </label>
                        </div>
                    </div>
                    @endif

                    <!-- Donation Amount -->
                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-3">Donation Amount</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                            @php
                                $defaultAmounts = explode(',', setting('donate_default_amounts', '25,50,100,250,500'));
                            @endphp
                            @foreach(array_slice($defaultAmounts, 0, 4) as $amount)
                                <button type="button" class="amount-btn border-2 border-amber-200 text-amber-700 px-4 py-3 rounded-lg hover:border-amber-400 hover:bg-amber-50 transition-colors duration-200" 
                                        data-amount="{{ trim($amount) }}">
                                    ${{ trim($amount) }}
                                </button>
                            @endforeach
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-500 mr-2">$</span>
                            <input type="number" id="customAmount" 
                                   placeholder="Enter custom amount" 
                                   min="{{ setting('donate_min_amount', '5') }}"
                                   max="{{ setting('donate_max_amount', '10000') }}"
                                   class="flex-1 border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                        <input type="hidden" id="selectedAmount" name="amount" value="">
                    </div>

                    <!-- Donor Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-amber-700 mb-2">First Name</label>
                            <input type="text" name="first_name" required 
                                   class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-amber-700 mb-2">Last Name</label>
                            <input type="text" name="last_name" required 
                                   class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-2">Email Address</label>
                        <input type="email" name="email" required 
                               class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-3">Payment Method</label>
                        <div class="space-y-3">
                            <div class="border border-amber-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <input type="radio" name="payment_method" value="stripe" checked 
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
                            
                            <div class="border border-amber-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <input type="radio" name="payment_method" value="paypal" 
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
                    </div>

                    <!-- Additional Options -->
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="updates" class="text-amber-600 focus:ring-amber-500">
                            <span class="ml-2 text-gray-700">I would like to receive updates about the animals I help</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="anonymous" class="text-amber-600 focus:ring-amber-500">
                            <span class="ml-2 text-gray-700">Make this donation anonymous</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full bg-amber-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-amber-700 transition-colors duration-200"
                                id="donateButton">
                            Donate $<span id="donateAmount">0</span> Now
                        </button>
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            Your donation is secure and will be processed safely. You will receive a receipt via email.
                        </p>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Get DOM elements
            const amountButtons = document.querySelectorAll('.amount-btn');
            const customAmountInput = document.getElementById('customAmount');
            const donateAmountSpan = document.getElementById('donateAmount');
            const selectedAmountInput = document.getElementById('selectedAmount');
            
            // Initialize with no selected amount
            if (donateAmountSpan) {
                donateAmountSpan.textContent = '0';
            }
            
            if (selectedAmountInput) {
                selectedAmountInput.value = '';
            }
            
            // Handle amount button clicks
            if (amountButtons && amountButtons.length > 0) {
                amountButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Remove active class from all buttons
                        amountButtons.forEach(btn => {
                            btn.classList.remove('border-amber-500', 'bg-amber-50', 'font-semibold');
                            btn.classList.add('border-amber-200');
                        });
                        
                        // Add active class to clicked button
                        this.classList.remove('border-amber-200');
                        this.classList.add('border-amber-500', 'bg-amber-50', 'font-semibold');
                        
                        // Update selected amount
                        const amount = this.getAttribute('data-amount');
                        
                        if (donateAmountSpan) {
                            donateAmountSpan.textContent = amount;
                        }
                        
                        if (selectedAmountInput) {
                            selectedAmountInput.value = amount;
                        }
                        
                        // Clear custom amount input
                        if (customAmountInput) {
                            customAmountInput.value = '';
                        }
                    });
                });
            }
            
            // Handle custom amount input
            if (customAmountInput) {
                customAmountInput.addEventListener('input', function() {
                    const value = this.value;
                    
                    if (value && !isNaN(value) && parseFloat(value) > 0) {
                        // Remove active class from all buttons
                        if (amountButtons) {
                            amountButtons.forEach(btn => {
                                btn.classList.remove('border-amber-500', 'bg-amber-50', 'font-semibold');
                                btn.classList.add('border-amber-200');
                            });
                        }
                        
                        // Update selected amount
                        if (donateAmountSpan) {
                            donateAmountSpan.textContent = value;
                        }
                        
                        if (selectedAmountInput) {
                            selectedAmountInput.value = value;
                        }
                    }
                });
            }
        });
    </script>
</x-layouts.public>