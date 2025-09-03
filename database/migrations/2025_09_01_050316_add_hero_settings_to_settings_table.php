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
        // Insert default hero settings
        $settings = [
            ['key' => 'hero_background_image', 'value' => '', 'type' => 'image', 'group' => 'general'],
            ['key' => 'hero_title', 'value' => 'Every Paw Deserves Love', 'type' => 'text', 'group' => 'general'],
            ['key' => 'hero_subtitle', 'value' => 'Join us in our mission to rescue, rehabilitate, and rehome animals in need. Together, we can make a difference, one paw at a time.', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'hero_cta1_text', 'value' => 'Adopt a Pet', 'type' => 'text', 'group' => 'general'],
            ['key' => 'hero_cta1_url', 'value' => '/animals', 'type' => 'text', 'group' => 'general'],
            ['key' => 'hero_cta2_text', 'value' => 'Donate Now', 'type' => 'text', 'group' => 'general'],
            ['key' => 'hero_cta2_url', 'value' => '/donate', 'type' => 'text', 'group' => 'general'],
            ['key' => 'hero_cta3_text', 'value' => 'Volunteer', 'type' => 'text', 'group' => 'general'],
            ['key' => 'hero_cta3_url', 'value' => '/volunteer', 'type' => 'text', 'group' => 'general'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting + ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove hero settings
        $keys = [
            'hero_background_image', 'hero_title', 'hero_subtitle',
            'hero_cta1_text', 'hero_cta1_url', 'hero_cta2_text', 'hero_cta2_url',
            'hero_cta3_text', 'hero_cta3_url'
        ];

        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
