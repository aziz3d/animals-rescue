<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample animals
        \App\Models\Animal::create([
            'name' => 'Buddy',
            'species' => 'Dog',
            'breed' => 'Golden Retriever',
            'age' => 3,
            'gender' => 'male',
            'size' => 'large',
            'description' => 'Friendly and energetic dog looking for a loving home.',
            'medical_history' => 'Up to date on vaccinations, neutered.',
            'adoption_status' => 'available',
            'featured' => true,
            'images' => ['/animals-rescue/dog1.jpg'],
        ]);

        \App\Models\Animal::create([
            'name' => 'Whiskers',
            'species' => 'Cat',
            'breed' => 'Tabby',
            'age' => 2,
            'gender' => 'female',
            'size' => 'medium',
            'description' => 'Sweet and gentle cat who loves to cuddle.',
            'medical_history' => 'Spayed, all shots current.',
            'adoption_status' => 'adopted',
            'featured' => false,
            'images' => ['/animals-rescue/cat1.jpg'],
        ]);

        // Create sample stories
        \App\Models\Story::create([
            'title' => 'Buddy Finds His Forever Home',
            'slug' => 'buddy-finds-his-forever-home',
            'content' => 'After months at the shelter, Buddy finally found his perfect family...',
            'excerpt' => 'A heartwarming adoption story.',
            'featured_image' => '/animals-rescue/story1.jpg',
            'category' => 'adoption',
            'featured' => true,
            'published_at' => now()->subDays(5),
        ]);

        // Create sample volunteers
        \App\Models\Volunteer::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@example.com',
            'phone' => '555-0123',
            'areas_of_interest' => ['dog_walking', 'cleaning'],
            'availability' => ['weekends'],
            'experience' => 'I have been volunteering with animals for 2 years.',
            'status' => 'active',
            'applied_at' => now()->subDays(10),
        ]);

        // Create sample contacts
        \App\Models\Contact::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Interested in adopting',
            'message' => 'I would like to know more about the adoption process.',
            'status' => 'new',
        ]);

        // Create sample donations
        \App\Models\Donation::create([
            'donor_name' => 'Jane Smith',
            'donor_email' => 'jane@example.com',
            'amount' => 50.00,
            'type' => 'one-time',
            'status' => 'completed',
            'payment_method' => 'credit_card',
            'transaction_id' => 'txn_123456',
        ]);

        $this->command->info('Sample data created successfully!');
    }
}
