<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if settings already exist before inserting
        $existingSettings = DB::table('settings')->pluck('key')->toArray();
        
        $newSettings = [
            [
                'key' => 'cta_title',
                'value' => 'Ready to Make a Difference?',
                'type' => 'text',
                'group' => 'general'
            ],
            [
                'key' => 'cta_description',
                'value' => 'Whether you\'re looking to adopt, volunteer, or donate, every action helps us save more lives.',
                'type' => 'textarea',
                'group' => 'general'
            ],
            [
                'key' => 'cta_button1_text',
                'value' => 'Get in Touch',
                'type' => 'text',
                'group' => 'general'
            ],
            [
                'key' => 'cta_button1_url',
                'value' => '/contact',
                'type' => 'text',
                'group' => 'general'
            ],
            [
                'key' => 'cta_button2_text',
                'value' => 'Start Volunteering',
                'type' => 'text',
                'group' => 'general'
            ],
            [
                'key' => 'cta_button2_url',
                'value' => '/volunteer',
                'type' => 'text',
                'group' => 'general'
            ]
        ];
        
        foreach ($newSettings as $setting) {
            if (!in_array($setting['key'], $existingSettings)) {
                DB::table('settings')->insert($setting);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'cta_title',
            'cta_description',
            'cta_button1_text',
            'cta_button1_url',
            'cta_button2_text',
            'cta_button2_url'
        ])->delete();
    }
};
