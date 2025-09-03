<?php

namespace Database\Seeders;

use App\Models\Story;
use Illuminate\Database\Seeder;

class StorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $stories = [
            [
                'title' => 'Bella\'s Journey: From Abandoned to Beloved',
                'slug' => 'bellas-journey-from-abandoned-to-beloved',
                'content' => 'Bella was found abandoned in a cardboard box on a cold winter morning. She was just 8 weeks old, scared, and malnourished. Our rescue team immediately took her in and provided the medical care she desperately needed. After weeks of rehabilitation and lots of love from our volunteers, Bella transformed into a playful, loving puppy. Today, she lives with the Johnson family, where she enjoys daily walks in the park and has become best friends with their 5-year-old daughter Emma. Bella\'s story reminds us why we do this work - every animal deserves a second chance at happiness.',
                'excerpt' => 'Bella was found abandoned in a cardboard box, but with love and care, she found her forever home with the Johnson family.',
                'featured_image' => 'stories/dog-hugging-human.jpg',
                'category' => 'rescue',
                'featured' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Max Finds His Perfect Match',
                'slug' => 'max-finds-his-perfect-match',
                'content' => 'Max, a 3-year-old Golden Retriever mix, had been with us for over 6 months. Despite his gentle nature and beautiful golden coat, he struggled to find a home because of his size and energy level. That all changed when Sarah, a marathon runner, visited our shelter. She was looking for a running companion, and Max was the perfect fit! Now, Max runs 5 miles every morning with Sarah and has even participated in several charity runs to raise money for animal rescues. It just goes to show that every pet has their perfect person - sometimes it just takes time to find each other.',
                'excerpt' => 'After 6 months at the shelter, Max found his perfect running partner in Sarah, and now they\'re inseparable.',
                'featured_image' => 'stories/dog-looking-at-camera.jpg',
                'category' => 'adoption',
                'featured' => false,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Whiskers: A Senior Cat\'s Second Chance',
                'slug' => 'whiskers-senior-cats-second-chance',
                'content' => 'At 12 years old, Whiskers was surrendered to our shelter when his elderly owner could no longer care for him. Senior cats often have a harder time finding homes, but Whiskers had a special charm that captured the heart of retired teacher Margaret. Margaret was looking for a calm, loving companion to keep her company, and Whiskers was exactly what she needed. Now, Whiskers spends his days lounging in sunny spots by Margaret\'s windows and purring contentedly in her lap while she reads. Age is just a number when it comes to love and companionship.',
                'excerpt' => 'Senior cat Whiskers found the perfect retirement home with Margaret, proving that older pets make wonderful companions.',
                'featured_image' => 'stories/cat-looking-at-camera.jpg',
                'category' => 'adoption',
                'featured' => false,
                'published_at' => now()->subDays(8),
            ],
            [
                'title' => 'Rescue Update: New Medical Equipment Saves Lives',
                'slug' => 'rescue-update-new-medical-equipment-saves-lives',
                'content' => 'Thanks to generous donations from our community, we were able to purchase new medical equipment that has already saved several lives. Our new digital X-ray machine and surgical equipment have allowed us to provide better care for injured animals. Last month alone, we were able to perform life-saving surgery on three animals who might not have survived otherwise. This includes Luna, a cat who was hit by a car, Rocky, a dog with a severe leg fracture, and Pip, a rabbit who needed emergency surgery. Your donations directly impact our ability to save lives, and we are incredibly grateful for your continued support.',
                'excerpt' => 'New medical equipment funded by community donations has already saved several animal lives this month.',
                'featured_image' => 'stories/dog-and-cat.jpg',
                'category' => 'news',
                'featured' => false,
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => 'Volunteer Spotlight: Maria\'s 5 Years of Dedication',
                'slug' => 'volunteer-spotlight-marias-5-years-dedication',
                'content' => 'This month, we celebrate Maria Rodriguez, who has been volunteering with us for 5 incredible years. Maria started as a dog walker and has since become one of our most trusted volunteers, helping with everything from animal care to adoption events. She has personally helped over 200 animals find their forever homes through her dedication and expertise. Maria says, "Every animal that comes through our doors has a story, and I feel privileged to be part of their journey to finding love and happiness." We are so grateful for volunteers like Maria who make our mission possible.',
                'excerpt' => 'Celebrating Maria Rodriguez\'s 5 years of volunteer service and her role in helping over 200 animals find homes.',
                'featured_image' => 'stories/hi-five-cats-dogs-being-friends.jpg',
                'category' => 'news',
                'featured' => false,
                'published_at' => now()->subDays(15),
            ],
            [
                'title' => 'The Great Kitten Rescue of 2024',
                'slug' => 'great-kitten-rescue-2024',
                'content' => 'In what became known as "The Great Kitten Rescue of 2024," our team responded to a call about a feral cat colony that had grown to over 30 cats. Working with local authorities and other rescue organizations, we spent three weeks safely trapping, spaying/neutering, and finding homes for these cats. The operation resulted in 15 kittens and 8 adult cats being placed in loving homes, while the remaining cats were returned to their territory after being spayed/neutered to prevent further overpopulation. This massive effort required coordination between multiple organizations and countless volunteer hours, but seeing all these cats get the care they needed made every moment worth it.',
                'excerpt' => 'A coordinated rescue effort helped over 30 cats from a feral colony, with 23 finding new homes.',
                'featured_image' => 'stories/2-cute-mouses.jpg',
                'category' => 'rescue',
                'featured' => false,
                'published_at' => now()->subDays(20),
            ],
        ];

        foreach ($stories as $storyData) {
            Story::create($storyData);
        }
    }
}