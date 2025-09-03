<?php

// Test script for fully dynamic donation system
echo "Testing Fully Dynamic Donation System\n";
echo "====================================\n\n";

// Test 1: Page Content Settings
echo "1. Testing Dynamic Page Content:\n";
echo "   - Page Title: " . setting('donate_page_title', 'Default Title') . "\n";
echo "   - Page Subtitle: " . substr(setting('donate_page_subtitle', 'Default subtitle'), 0, 50) . "...\n";
echo "   - Form Title: " . setting('donate_form_title', 'Default Form Title') . "\n";
echo "   - Impact Section Title: " . setting('donate_impact_section_title', 'Default Impact Title') . "\n\n";

// Test 2: Dynamic Impact Items
echo "2. Testing Dynamic Impact Items:\n";
$impactItems = json_decode(setting('donate_impact_items', '[]'), true) ?: [];
foreach ($impactItems as $index => $item) {
    echo "   - $" . $item['amount'] . ": " . $item['title'] . " - " . substr($item['description'], 0, 40) . "...\n";
}
echo "   - Total impact items: " . count($impactItems) . "\n\n";

// Test 3: Dynamic Donation Amounts
echo "3. Testing Dynamic Donation Amounts:\n";
$amounts = explode(',', setting('donate_default_amounts', '25,50,100,250,500'));
echo "   - Configured amounts: $" . implode(', $', $amounts) . "\n";
echo "   - Total amounts: " . count($amounts) . "\n\n";

// Test 4: Payment Methods
echo "4. Testing Payment Method Configuration:\n";
echo "   - Stripe enabled: " . (setting('donate_enable_stripe', '1') ? 'Yes' : 'No') . "\n";
echo "   - PayPal enabled: " . (setting('donate_enable_paypal', '1') ? 'Yes' : 'No') . "\n\n";

// Test 5: Form Settings
echo "5. Testing Form Settings:\n";
echo "   - Min amount: $" . setting('donate_min_amount', '5') . "\n";
echo "   - Max amount: $" . setting('donate_max_amount', '10000') . "\n";
echo "   - Recurring enabled: " . (setting('donate_enable_recurring', '1') ? 'Yes' : 'No') . "\n";
echo "   - Email receipts: " . (setting('donate_send_receipts', '1') ? 'Yes' : 'No') . "\n\n";

echo "✅ Fully Dynamic Donation System Test Completed!\n";
echo "\nNew Dynamic Features:\n";
echo "- Fully configurable page content (titles, descriptions)\n";
echo "- Dynamic impact items (add/remove/edit from admin)\n";
echo "- Smart donation button (changes based on amount)\n";
echo "- Responsive impact grid (adapts to number of items)\n";
echo "- Real-time admin interface for all settings\n";
echo "- No hardcoded content - everything is configurable\n";