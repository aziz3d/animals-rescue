<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = [
            [
                'name' => 'Rachel Green',
                'email' => 'rachel.green@email.com',
                'subject' => 'Adoption Inquiry - Buddy',
                'message' => 'Hi, I saw Buddy on your website and I\'m very interested in adopting him. I have a large fenced yard and experience with Golden Retrievers. Could we schedule a meet and greet? I\'m available this weekend. Thank you!',
                'status' => 'replied',
                'created_at' => now()->subDays(1),
            ],
            [
                'name' => 'John Mitchell',
                'email' => 'john.mitchell@email.com',
                'subject' => 'Volunteer Application Follow-up',
                'message' => 'I submitted a volunteer application last week and wanted to follow up. I\'m particularly interested in helping with dog walking and would love to know about the next steps in the process.',
                'status' => 'read',
                'created_at' => now()->subDays(3),
            ],
            [
                'name' => 'Amy Thompson',
                'email' => 'amy.thompson@email.com',
                'subject' => 'Found Stray Cat',
                'message' => 'I found a stray cat in my neighborhood that appears to be injured. The cat seems friendly but has a limp. Can you help or provide guidance on what I should do? I can bring the cat to you if needed.',
                'status' => 'new',
                'created_at' => now()->subHours(6),
            ],
            [
                'name' => 'Steve Rodriguez',
                'email' => 'steve.rodriguez@email.com',
                'subject' => 'Donation Receipt Request',
                'message' => 'I made a donation last month but didn\'t receive a receipt for tax purposes. Could you please send me a receipt for my $150 donation made on March 15th? My transaction ID is txn_7890123456.',
                'status' => 'replied',
                'created_at' => now()->subDays(5),
            ],
            [
                'name' => 'Linda Parker',
                'email' => 'linda.parker@email.com',
                'subject' => 'Corporate Partnership Opportunity',
                'message' => 'I work for a local pet supply company and we\'re interested in partnering with your rescue. We would like to discuss donating supplies and potentially sponsoring adoption events. Please let me know who I should speak with.',
                'status' => 'read',
                'created_at' => now()->subDays(7),
            ],
            [
                'name' => 'Carlos Hernandez',
                'email' => 'carlos.hernandez@email.com',
                'subject' => 'Adoption Update - Luna',
                'message' => 'We adopted Luna from you 6 months ago and wanted to send an update! She\'s doing wonderfully and has become best friends with our other cat. Thank you for all the work you do. I\'ve attached some photos.',
                'status' => 'replied',
                'created_at' => now()->subDays(8),
            ],
            [
                'name' => 'Michelle Lee',
                'email' => 'michelle.lee@email.com',
                'subject' => 'Website Feedback',
                'message' => 'I love your new website! It\'s so easy to browse the available animals and read their stories. The photos are beautiful too. Keep up the great work!',
                'status' => 'read',
                'created_at' => now()->subDays(10),
            ],
            [
                'name' => 'Brian Wilson',
                'email' => 'brian.wilson@email.com',
                'subject' => 'Fostering Information',
                'message' => 'My family is interested in becoming foster parents for cats or kittens. We have experience with pets and a quiet home environment. Could you provide information about your foster program and requirements?',
                'status' => 'new',
                'created_at' => now()->subDays(2),
            ],
            [
                'name' => 'Sandra Davis',
                'email' => 'sandra.davis@email.com',
                'subject' => 'Event Venue Offer',
                'message' => 'I own a local community center and would like to offer our space for your adoption events at no charge. We have parking and good visibility from the street. Please contact me if you\'re interested.',
                'status' => 'new',
                'created_at' => now()->subDays(4),
            ],
            [
                'name' => 'Kevin Brown',
                'email' => 'kevin.brown@email.com',
                'subject' => 'Veterinary Services',
                'message' => 'I\'m a veterinarian and new to the area. I\'d like to discuss providing discounted services for your rescue animals. I have experience with shelter medicine and am passionate about rescue work.',
                'status' => 'read',
                'created_at' => now()->subDays(6),
            ],
        ];

        foreach ($contacts as $contactData) {
            Contact::create($contactData);
        }
    }
}