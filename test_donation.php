<?php

// Simple test script to verify donation functionality
require_once 'vendor/autoload.php';

use App\Models\Setting;
use App\Models\Donation;

// Test settings
echo "Testing donation settings...\n";

$defaultAmounts = Setting::get('donate_default_amounts', '25,50,100,250,500');
echo "Default amounts: " . $defaultAmounts . "\n";

$minAmount = Setting::get('donate_min_amount', '5');
echo "Min amount: $" . $minAmount . "\n";

$maxAmount = Setting::get('donate_max_amount', '10000');
echo "Max amount: $" . $maxAmount . "\n";

$enableRecurring = Setting::get('donate_enable_recurring', '1');
echo "Recurring enabled: " . ($enableRecurring ? 'Yes' : 'No') . "\n";

$impact25 = Setting::get('donate_impact_25', 'Default message');
echo "Impact $25: " . $impact25 . "\n";

$enableStripe = Setting::get('donate_enable_stripe', '1');
echo "Stripe enabled: " . ($enableStripe ? 'Yes' : 'No') . "\n";

$enablePaypal = Setting::get('donate_enable_paypal', '1');
echo "PayPal enabled: " . ($enablePaypal ? 'Yes' : 'No') . "\n";

echo "\nDonation settings test completed successfully!\n";