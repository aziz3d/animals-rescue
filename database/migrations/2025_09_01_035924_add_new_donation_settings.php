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
        // Add new donation settings only if they don't exist
        $settings = [
            'donate_impact_25' => 'Provides nutritious meals for one animal for a week',
            'donate_impact_75' => 'Covers basic veterinary checkup and vaccinations',
            'donate_impact_150' => 'Complete care from rescue to adoption readiness',
            'donate_enable_stripe' => '1',
            'donate_enable_paypal' => '1',
        ];

        foreach ($settings as $key => $value) {
            if (!DB::table('settings')->where('key', $key)->exists()) {
                DB::table('settings')->insert([
                    'key' => $key,
                    'value' => $value,
                    'type' => in_array($key, ['donate_enable_stripe', 'donate_enable_paypal']) ? 'boolean' : 'text',
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
        // Remove the new donation settings
        DB::table('settings')->whereIn('key', [
            'donate_impact_25',
            'donate_impact_75',
            'donate_impact_150',
            'donate_enable_stripe',
            'donate_enable_paypal',
        ])->delete();
    }
};
