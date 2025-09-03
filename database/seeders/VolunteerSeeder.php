<?php

namespace Database\Seeders;

use App\Models\Volunteer;
use Illuminate\Database\Seeder;

class VolunteerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $volunteers = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@email.com',
                'phone' => '(555) 123-4567',
                'areas_of_interest' => ['Animal Care', 'Dog Walking'],
                'availability' => ['Monday Morning', 'Wednesday Evening', 'Saturday All Day'],
                'experience' => 'I have been volunteering at animal shelters for over 3 years. I have experience with both cats and dogs, including helping with feeding, cleaning, and basic medical care. I also foster kittens during kitten season.',
                'status' => 'active',
                'applied_at' => now()->subDays(45),
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@email.com',
                'phone' => '(555) 234-5678',
                'areas_of_interest' => ['Transportation', 'Event Support'],
                'availability' => ['Tuesday Evening', 'Thursday Evening', 'Sunday Afternoon'],
                'experience' => 'I work in logistics and would love to help with transporting animals to vet appointments or adoption events. I have a large SUV that can safely transport multiple animals.',
                'status' => 'active',
                'applied_at' => now()->subDays(30),
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily.rodriguez@email.com',
                'phone' => '(555) 345-6789',
                'areas_of_interest' => ['Administrative Support', 'Social Media'],
                'availability' => ['Monday Evening', 'Friday Evening', 'Weekend Mornings'],
                'experience' => 'I work in marketing and would love to help with social media management, creating adoption posts, and general administrative tasks. I am proficient in Photoshop and social media platforms.',
                'status' => 'approved',
                'applied_at' => now()->subDays(20),
            ],
            [
                'name' => 'David Thompson',
                'email' => 'david.thompson@email.com',
                'phone' => '(555) 456-7890',
                'areas_of_interest' => ['Animal Care', 'Maintenance'],
                'availability' => ['Saturday Morning', 'Sunday Morning'],
                'experience' => 'I am retired and have plenty of time to help. I have owned pets my whole life and am comfortable with all types of animals. I also have handyman skills and can help with facility maintenance.',
                'status' => 'active',
                'applied_at' => now()->subDays(60),
            ],
            [
                'name' => 'Jessica Martinez',
                'email' => 'jessica.martinez@email.com',
                'phone' => '(555) 567-8901',
                'areas_of_interest' => ['Foster Care', 'Animal Care'],
                'availability' => ['Flexible - Work from Home'],
                'experience' => 'I work from home and have experience fostering both cats and dogs. I have a fenced yard and am comfortable with animals that need extra medical attention or behavioral support.',
                'status' => 'active',
                'applied_at' => now()->subDays(15),
            ],
            [
                'name' => 'Robert Wilson',
                'email' => 'robert.wilson@email.com',
                'phone' => '(555) 678-9012',
                'areas_of_interest' => ['Dog Walking', 'Training'],
                'availability' => ['Tuesday Morning', 'Thursday Morning', 'Saturday Afternoon'],
                'experience' => 'I am a certified dog trainer and would love to help with basic training and socialization of rescue dogs. I have experience working with dogs with behavioral issues.',
                'status' => 'pending',
                'applied_at' => now()->subDays(5),
            ],
            [
                'name' => 'Amanda Davis',
                'email' => 'amanda.davis@email.com',
                'phone' => '(555) 789-0123',
                'areas_of_interest' => ['Event Support', 'Fundraising'],
                'availability' => ['Weekend Events', 'Evening Events'],
                'experience' => 'I have experience organizing charity events and fundraisers. I would love to help coordinate adoption events, fundraising activities, and community outreach programs.',
                'status' => 'approved',
                'applied_at' => now()->subDays(35),
            ],
            [
                'name' => 'Christopher Lee',
                'email' => 'christopher.lee@email.com',
                'phone' => '(555) 890-1234',
                'areas_of_interest' => ['Photography', 'Social Media'],
                'availability' => ['Saturday Afternoon', 'Sunday Afternoon'],
                'experience' => 'I am a professional photographer and would love to help take high-quality photos of animals for adoption listings and social media. Good photos can really help animals get adopted faster.',
                'status' => 'active',
                'applied_at' => now()->subDays(25),
            ],
        ];

        foreach ($volunteers as $volunteerData) {
            Volunteer::create($volunteerData);
        }
    }
}