<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Story;
use Illuminate\Database\Seeder;

class HomepageTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some test animals
        Animal::create([
            'name' => 'Buddy',
            'species' => 'Dog',
            'breed' => 'Golden Retriever',
            'age' => 3,
            'gender' => 'male',
            'size' => 'large',
            'description' => 'Buddy is a friendly and energetic dog who loves to play fetch and go for long walks.',
            'medical_history' => 'Up to date on all vaccinations, neutered.',
            'adoption_status' => 'available',
            'featured' => true,
            'images' => ['/animals-rescue/dog1.jpg'],
        ]);

        Animal::create([
            'name' => 'Luna',
            'species' => 'Cat',
            'breed' => 'Siamese',
            'age' => 2,
            'gender' => 'female',
            'size' => 'medium',
            'description' => 'Luna is a gentle and affectionate cat who enjoys quiet moments and gentle pets.',
            'medical_history' => 'Spayed, vaccinated, microchipped.',
            'adoption_status' => 'available',
            'featured' => true,
            'images' => ['/animals-rescue/cat1.jpg'],
        ]);

        Animal::create([
            'name' => 'Max',
            'species' => 'Dog',
            'breed' => 'Labrador Mix',
            'age' => 5,
            'gender' => 'male',
            'size' => 'large',
            'description' => 'Max is a calm and well-trained dog, perfect for families with children.',
            'medical_history' => 'Neutered, all shots current.',
            'adoption_status' => 'available',
            'featured' => true,
            'images' => ['/animals-rescue/dog2.jpg'],
        ]);

        // Create some adopted animals for statistics
        Animal::create([
            'name' => 'Bella',
            'species' => 'Dog',
            'breed' => 'Beagle',
            'age' => 4,
            'gender' => 'female',
            'size' => 'medium',
            'description' => 'Bella found her forever home!',
            'medical_history' => 'Spayed, vaccinated.',
            'adoption_status' => 'adopted',
            'featured' => false,
            'images' => [],
        ]);

        Animal::create([
            'name' => 'Charlie',
            'species' => 'Cat',
            'breed' => 'Tabby',
            'age' => 1,
            'gender' => 'male',
            'size' => 'small',
            'description' => 'Charlie was adopted by a loving family.',
            'medical_history' => 'Neutered, vaccinated.',
            'adoption_status' => 'adopted',
            'featured' => false,
            'images' => [],
        ]);

        // Create some test stories
        Story::create([
            'title' => 'Buddy\'s Rescue Story',
            'slug' => 'buddys-rescue-story',
            'content' => 'Buddy was found abandoned on the side of the road, scared and hungry. After weeks of care and rehabilitation, he has transformed into the loving, playful dog he was meant to be. Now he\'s ready for his forever home!',
            'excerpt' => 'From abandoned and scared to loving and playful - Buddy\'s transformation is truly heartwarming.',
            'featured_image' => '/animals-rescue/story1.jpg',
            'category' => 'rescue',
            'featured' => true,
            'published_at' => now()->subDays(2),
        ]);

        Story::create([
            'title' => 'Luna Finds Her Perfect Match',
            'slug' => 'luna-finds-her-perfect-match',
            'content' => 'After months of waiting, Luna finally found her perfect family. The Johnson family fell in love with her gentle nature and she has settled into her new home beautifully. She now spends her days lounging in sunny windows and playing with her new cat siblings.',
            'excerpt' => 'Luna\'s patience paid off when she found the perfect family who understood her gentle nature.',
            'featured_image' => '/animals-rescue/story2.jpg',
            'category' => 'adoption',
            'featured' => false,
            'published_at' => now()->subDays(5),
        ]);
    }
}