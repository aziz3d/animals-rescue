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
        // Insert default volunteer settings
        $settings = [
            // Hero Section
            ['key' => 'volunteers_hero_title', 'value' => 'Volunteer With Us', 'type' => 'text', 'group' => 'volunteers'],
            ['key' => 'volunteers_hero_description', 'value' => 'Join our dedicated team of volunteers and make a direct impact on the lives of rescued animals. Your time and skills can help save lives and create happy endings.', 'type' => 'textarea', 'group' => 'volunteers'],
            
            // Opportunities Section
            ['key' => 'volunteers_opportunities_title', 'value' => 'Volunteer Opportunities', 'type' => 'text', 'group' => 'volunteers'],
            
            // Opportunity Cards
            ['key' => 'opportunity_1_title', 'value' => 'Animal Care', 'type' => 'text', 'group' => 'volunteers'],
            ['key' => 'opportunity_1_description', 'value' => 'Help with daily care including feeding, cleaning, grooming, and providing companionship to our rescued animals.', 'type' => 'textarea', 'group' => 'volunteers'],
            ['key' => 'opportunity_1_tasks', 'value' => "• Feeding and watering animals\n• Cleaning kennels and living spaces\n• Basic grooming and exercise\n• Socialization and enrichment activities", 'type' => 'textarea', 'group' => 'volunteers'],
            
            ['key' => 'opportunity_2_title', 'value' => 'Adoption Support', 'type' => 'text', 'group' => 'volunteers'],
            ['key' => 'opportunity_2_description', 'value' => 'Assist potential adopters by answering questions, facilitating meet-and-greets, and helping with the adoption process.', 'type' => 'textarea', 'group' => 'volunteers'],
            ['key' => 'opportunity_2_tasks', 'value' => "• Meet with potential adopters\n• Conduct adoption interviews\n• Organize adoption events\n• Follow up with new families", 'type' => 'textarea', 'group' => 'volunteers'],
            
            ['key' => 'opportunity_3_title', 'value' => 'Administrative', 'type' => 'text', 'group' => 'volunteers'],
            ['key' => 'opportunity_3_description', 'value' => 'Support our operations with office work, data entry, fundraising assistance, and event coordination.', 'type' => 'textarea', 'group' => 'volunteers'],
            ['key' => 'opportunity_3_tasks', 'value' => "• Data entry and record keeping\n• Fundraising event support\n• Social media management\n• Grant writing and research", 'type' => 'textarea', 'group' => 'volunteers'],
            
            ['key' => 'opportunity_4_title', 'value' => 'Professional Skills', 'type' => 'text', 'group' => 'volunteers'],
            ['key' => 'opportunity_4_description', 'value' => 'Use your professional expertise in areas like veterinary care, photography, web design, or legal services.', 'type' => 'textarea', 'group' => 'volunteers'],
            ['key' => 'opportunity_4_tasks', 'value' => "• Veterinary and medical care\n• Photography for adoptions\n• Web design and IT support\n• Legal and financial advice", 'type' => 'textarea', 'group' => 'volunteers'],
            
            // CTA Section
            ['key' => 'volunteers_cta_title', 'value' => 'Ready to Make a Difference?', 'type' => 'text', 'group' => 'volunteers'],
            ['key' => 'volunteers_cta_description', 'value' => 'Complete our volunteer application to get started. The process takes just a few minutes, and we\'ll guide you through each step.', 'type' => 'textarea', 'group' => 'volunteers'],
            ['key' => 'volunteers_cta_button_text', 'value' => 'Apply to Volunteer', 'type' => 'text', 'group' => 'volunteers'],
            
            // What Happens Next Section
            ['key' => 'volunteers_next_title', 'value' => 'What Happens Next?', 'type' => 'text', 'group' => 'volunteers'],
            ['key' => 'volunteers_next_step1_title', 'value' => 'Application Review', 'type' => 'text', 'group' => 'volunteers'],
            ['key' => 'volunteers_next_step1_description', 'value' => 'We\'ll review your application and contact you within a week', 'type' => 'text', 'group' => 'volunteers'],
            ['key' => 'volunteers_next_step2_title', 'value' => 'Orientation', 'type' => 'text', 'group' => 'volunteers'],
            ['key' => 'volunteers_next_step2_description', 'value' => 'Attend a volunteer orientation session to learn about our procedures', 'type' => 'text', 'group' => 'volunteers'],
            ['key' => 'volunteers_next_step3_title', 'value' => 'Start Helping', 'type' => 'text', 'group' => 'volunteers'],
            ['key' => 'volunteers_next_step3_description', 'value' => 'Begin your volunteer work and start making a difference!', 'type' => 'text', 'group' => 'volunteers'],
            
            // Application Settings
            ['key' => 'volunteers_auto_approve', 'value' => '0', 'type' => 'boolean', 'group' => 'volunteers'],
            ['key' => 'volunteers_min_age', 'value' => '18', 'type' => 'number', 'group' => 'volunteers'],
            ['key' => 'volunteers_background_check', 'value' => '1', 'type' => 'boolean', 'group' => 'volunteers'],
            ['key' => 'volunteers_email_notifications', 'value' => '1', 'type' => 'boolean', 'group' => 'volunteers'],
            ['key' => 'volunteers_application_expiry', 'value' => '30', 'type' => 'number', 'group' => 'volunteers'],
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
        // Remove volunteer settings
        $keys = [
            'volunteers_hero_title', 'volunteers_hero_description', 'volunteers_opportunities_title',
            'opportunity_1_title', 'opportunity_1_description', 'opportunity_1_tasks',
            'opportunity_2_title', 'opportunity_2_description', 'opportunity_2_tasks',
            'opportunity_3_title', 'opportunity_3_description', 'opportunity_3_tasks',
            'opportunity_4_title', 'opportunity_4_description', 'opportunity_4_tasks',
            'volunteers_cta_title', 'volunteers_cta_description', 'volunteers_cta_button_text',
            'volunteers_next_title', 'volunteers_next_step1_title', 'volunteers_next_step1_description',
            'volunteers_next_step2_title', 'volunteers_next_step2_description',
            'volunteers_next_step3_title', 'volunteers_next_step3_description',
            'volunteers_auto_approve', 'volunteers_min_age', 'volunteers_background_check',
            'volunteers_email_notifications', 'volunteers_application_expiry'
        ];

        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
