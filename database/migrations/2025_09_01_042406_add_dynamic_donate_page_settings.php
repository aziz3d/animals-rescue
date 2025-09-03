<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new dynamic donate page settings
        $settings = [
            'donate_page_title' => 'Support Our Mission',
            'donate_page_subtitle' => 'Your generous donation helps us rescue, rehabilitate, and rehome animals in need. Every contribution makes a difference in an animal\'s life.',
            'donate_form_title' => 'Make a Donation',
            'donate_impact_section_title' => 'Your Impact',
            'donate_impact_items' => json_encode([
                ['amount' => 25, 'title' => 'Feeds an Animal', 'description' => 'Provides nutritious meals for one animal for a week'],
                ['amount' => 75, 'title' => 'Medical Care', 'description' => 'Covers basic veterinary checkup and vaccinations'],
                ['amount' => 150, 'title' => 'Full Rescue', 'description' => 'Complete care from rescue to adoption readiness']
            ])
        ];

        foreach ($settings as $key => $value) {
            if (!DB::table('settings')->where('key', $key)->exists()) {
                DB::table('settings')->insert([
                    'key' => $key,
                    'value' => $value,
                    'type' => $key === 'donate_impact_items' ? 'json' : ($key === 'donate_page_subtitle' ? 'textarea' : 'text'),
                    'group' => 'donate',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the dynamic donate page settings
        DB::table('settings')->whereIn('key', [
            'donate_page_title',
            'donate_page_subtitle',
            'donate_form_title',
            'donate_impact_section_title',
            'donate_impact_items',
        ])->delete();
    }
};
