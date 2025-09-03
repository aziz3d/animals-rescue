<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class RefreshSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder is designed to refresh all data for testing purposes.
     */
    public function run(): void
    {
        $this->command->info('🔄 Refreshing database with fresh sample data...');
        
        // Truncate tables to start fresh
        $this->command->info('📝 Clearing existing data...');
        
        \App\Models\PageVisit::truncate();
        \App\Models\Contact::truncate();
        \App\Models\Donation::truncate();
        \App\Models\Volunteer::truncate();
        \App\Models\Story::truncate();
        \App\Models\Animal::truncate();
        
        // Keep users but update admin
        \App\Models\User::where('email', 'sunrise300@gmail.com')->delete();
        
        $this->command->info('🌱 Seeding fresh data...');
        
        // Run all seeders
        $this->call([
            AdminUserSeeder::class,
            AnimalSeeder::class,
            StorySeeder::class,
            VolunteerSeeder::class,
            DonationSeeder::class,
            ContactSeeder::class,
            ComprehensiveSeeder::class,
        ]);
        
        $this->command->info('✅ Database refreshed successfully!');
        $this->command->info('📊 Summary:');
        $this->command->info('   • Animals: ' . \App\Models\Animal::count());
        $this->command->info('   • Stories: ' . \App\Models\Story::count());
        $this->command->info('   • Volunteers: ' . \App\Models\Volunteer::count());
        $this->command->info('   • Donations: ' . \App\Models\Donation::count());
        $this->command->info('   • Contacts: ' . \App\Models\Contact::count());
        $this->command->info('   • Page Visits: ' . \App\Models\PageVisit::count());
        $this->command->info('🔑 Admin Login: sunrise300@gmail.com / azizkhan');
    }
}