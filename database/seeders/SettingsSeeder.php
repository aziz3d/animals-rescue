<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Lovely Paws Rescue',
                'type' => 'text',
                'group' => 'general',
            ],
            [
                'key' => 'site_description',
                'value' => 'Helping animals find their forever homes through compassionate rescue and adoption services.',
                'type' => 'textarea',
                'group' => 'general',
            ],
            [
                'key' => 'site_keywords',
                'value' => 'animal rescue, pet adoption, dogs, cats, volunteers, animal shelter, pet care',
                'type' => 'text',
                'group' => 'general',
            ],
            [
                'key' => 'meta_title',
                'value' => 'Lovely Paws Rescue - Animal Rescue & Adoption Center',
                'type' => 'text',
                'group' => 'general',
            ],
            [
                'key' => 'meta_description',
                'value' => 'Find your perfect companion at Lovely Paws Rescue. We help animals find loving homes through our comprehensive adoption program and volunteer services.',
                'type' => 'textarea',
                'group' => 'general',
            ],
            [
                'key' => 'site_logo',
                'value' => '',
                'type' => 'image',
                'group' => 'general',
            ],
            [
                'key' => 'site_favicon',
                'value' => '',
                'type' => 'image',
                'group' => 'general',
            ],

            // Animals Settings
            [
                'key' => 'animals_default_status',
                'value' => 'available',
                'type' => 'select',
                'group' => 'animals',
            ],
            [
                'key' => 'animals_max_images',
                'value' => '10',
                'type' => 'number',
                'group' => 'animals',
            ],
            [
                'key' => 'animals_enable_favorites',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'animals',
            ],
            [
                'key' => 'animals_auto_publish',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'animals',
            ],
            [
                'key' => 'animals_per_page',
                'value' => '12',
                'type' => 'number',
                'group' => 'animals',
            ],

            // Stories Settings
            [
                'key' => 'stories_per_page',
                'value' => '12',
                'type' => 'number',
                'group' => 'stories',
            ],
            [
                'key' => 'stories_enable_comments',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'stories',
            ],
            [
                'key' => 'stories_auto_publish',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'stories',
            ],
            [
                'key' => 'stories_featured_limit',
                'value' => '5',
                'type' => 'number',
                'group' => 'stories',
            ],
            [
                'key' => 'stories_require_image',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'stories',
            ],

            // Volunteers Settings
            [
                'key' => 'volunteers_auto_approve',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'volunteers',
            ],
            [
                'key' => 'volunteers_min_age',
                'value' => '18',
                'type' => 'number',
                'group' => 'volunteers',
            ],
            [
                'key' => 'volunteers_background_check',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'volunteers',
            ],
            [
                'key' => 'volunteers_email_notifications',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'volunteers',
            ],
            [
                'key' => 'volunteers_application_expiry',
                'value' => '30',
                'type' => 'number',
                'group' => 'volunteers',
            ],

            // Donate Settings
            [
                'key' => 'donate_default_amounts',
                'value' => '25,50,100,250,500',
                'type' => 'text',
                'group' => 'donate',
            ],
            [
                'key' => 'donate_min_amount',
                'value' => '5',
                'type' => 'number',
                'group' => 'donate',
            ],
            [
                'key' => 'donate_max_amount',
                'value' => '10000',
                'type' => 'number',
                'group' => 'donate',
            ],
            [
                'key' => 'donate_enable_recurring',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'donate',
            ],
            [
                'key' => 'donate_thank_you_message',
                'value' => 'Thank you for your generous donation! Your support helps us rescue, rehabilitate, and rehome animals in need.',
                'type' => 'textarea',
                'group' => 'donate',
            ],
            [
                'key' => 'donate_send_receipts',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'donate',
            ],
            [
                'key' => 'donate_impact_25',
                'value' => 'Provides nutritious meals for one animal for a week',
                'type' => 'text',
                'group' => 'donate',
            ],
            [
                'key' => 'donate_impact_75',
                'value' => 'Covers basic veterinary checkup and vaccinations',
                'type' => 'text',
                'group' => 'donate',
            ],
            [
                'key' => 'donate_impact_150',
                'value' => 'Complete care from rescue to adoption readiness',
                'type' => 'text',
                'group' => 'donate',
            ],
            [
                'key' => 'donate_enable_stripe',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'donate',
            ],
            [
                'key' => 'donate_enable_paypal',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'donate',
            ],

            // Payment Settings
            [
                'key' => 'payment_provider',
                'value' => 'stripe',
                'type' => 'select',
                'group' => 'payment',
            ],
            [
                'key' => 'payment_test_mode',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'payment',
            ],
            [
                'key' => 'payment_api_key',
                'value' => '',
                'type' => 'password',
                'group' => 'payment',
            ],
            [
                'key' => 'payment_webhook_secret',
                'value' => '',
                'type' => 'password',
                'group' => 'payment',
            ],
            [
                'key' => 'payment_currency',
                'value' => 'USD',
                'type' => 'select',
                'group' => 'payment',
            ],
            [
                'key' => 'payment_fee_coverage',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'payment',
            ],

            // Contact Settings
            [
                'key' => 'contact_organization_name',
                'value' => 'Lovely Paws Rescue',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_phone',
                'value' => '(555) 123-4567',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_email',
                'value' => 'contact@lovelypawsrescue.org',
                'type' => 'email',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_address',
                'value' => "123 Main Street\nCity, State 12345",
                'type' => 'textarea',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_hours',
                'value' => "Monday - Friday: 9:00 AM - 5:00 PM\nSaturday: 10:00 AM - 3:00 PM\nSunday: Closed",
                'type' => 'textarea',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_auto_reply',
                'value' => 'Thank you for contacting us! We will respond to your message within 24 hours.',
                'type' => 'textarea',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_enable_form',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_require_phone',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'contact',
            ],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
