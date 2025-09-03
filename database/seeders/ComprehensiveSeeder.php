<?php

namespace Database\Seeders;

use App\Models\PageVisit;
use Illuminate\Database\Seeder;

class ComprehensiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create page visit analytics data for dashboard
        $pages = [
            '/animals',
            '/stories',
            '/donate',
            '/volunteer',
            '/contact',
            '/',
            '/animals/buddy',
            '/animals/luna',
            '/animals/max',
            '/stories/bellas-journey-from-abandoned-to-beloved',
            '/stories/max-finds-his-perfect-match',
        ];

        // Generate page visits for the last 30 days
        for ($i = 30; $i >= 0; $i--) {
            $date = now()->subDays($i);
            
            foreach ($pages as $page) {
                // Generate random visits for each page (more for popular pages)
                $visits = match($page) {
                    '/' => rand(50, 150),
                    '/animals' => rand(30, 80),
                    '/stories' => rand(20, 50),
                    '/donate' => rand(10, 30),
                    '/volunteer' => rand(5, 20),
                    '/contact' => rand(5, 15),
                    default => rand(1, 10),
                };

                for ($j = 0; $j < $visits; $j++) {
                    PageVisit::create([
                        'url' => $page,
                        'page_type' => $this->getPageType($page),
                        'page_id' => $this->getPageId($page),
                        'ip_address' => $this->generateRandomIP(),
                        'user_agent' => $this->generateRandomUserAgent(),
                        'referrer' => $this->generateRandomReferrer(),
                        'created_at' => $date->copy()->addMinutes(rand(0, 1439)), // Random time during the day
                    ]);
                }
            }
        }

        $this->command->info('Created comprehensive sample data including page visits for analytics');
    }

    private function generateRandomIP(): string
    {
        return rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255);
    }

    private function generateRandomUserAgent(): string
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (iPad; CPU OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Android 11; Mobile; rv:89.0) Gecko/89.0 Firefox/89.0',
        ];

        return $userAgents[array_rand($userAgents)];
    }

    private function getPageType(string $page): string
    {
        if ($page === '/') return 'home';
        if (str_starts_with($page, '/animals/')) return 'animal';
        if (str_starts_with($page, '/stories/')) return 'story';
        if (str_starts_with($page, '/animals')) return 'animals';
        if (str_starts_with($page, '/stories')) return 'stories';
        if (str_starts_with($page, '/donate')) return 'donate';
        if (str_starts_with($page, '/volunteer')) return 'volunteer';
        if (str_starts_with($page, '/contact')) return 'contact';
        
        return 'page';
    }

    private function getPageId(string $page): ?int
    {
        // For specific animal or story pages, we could extract IDs
        // For now, return null for general pages
        return null;
    }

    private function generateRandomReferrer(): ?string
    {
        $referrers = [
            'https://www.google.com/',
            'https://www.facebook.com/',
            'https://www.instagram.com/',
            'https://www.petfinder.com/',
            null, // Direct visits
            null,
            null,
        ];

        return $referrers[array_rand($referrers)];
    }
}