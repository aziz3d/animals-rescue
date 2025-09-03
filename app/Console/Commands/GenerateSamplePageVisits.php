<?php

namespace App\Console\Commands;

use App\Models\PageVisit;
use Illuminate\Console\Command;
use Carbon\Carbon;

class GenerateSamplePageVisits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:page-visits {--days=30 : Number of days to generate data for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sample page visit data for testing analytics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $pageTypes = ['home', 'animals_index', 'animal', 'stories_index', 'story', 'donate', 'volunteer', 'contact'];
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15',
        ];

        $this->info("Generating sample page visits for the last {$days} days...");

        $totalVisits = 0;

        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Generate 20-100 visits per day with some randomness
            $visitsPerDay = rand(20, 100);
            
            for ($j = 0; $j < $visitsPerDay; $j++) {
                $pageType = $pageTypes[array_rand($pageTypes)];
                $randomTime = $date->copy()->addMinutes(rand(0, 1439)); // Random time during the day
                
                PageVisit::create([
                    'url' => $this->generateUrl($pageType),
                    'page_type' => $pageType,
                    'page_id' => in_array($pageType, ['animal', 'story']) ? rand(1, 10) : null,
                    'ip_address' => $this->generateRandomIp(),
                    'user_agent' => $userAgents[array_rand($userAgents)],
                    'referrer' => rand(0, 3) === 0 ? 'https://google.com' : null,
                    'created_at' => $randomTime,
                    'updated_at' => $randomTime,
                ]);
                
                $totalVisits++;
            }
        }

        $this->info("Generated {$totalVisits} sample page visits successfully!");
    }

    private function generateUrl($pageType): string
    {
        $baseUrl = 'http://localhost:8000';
        
        return match($pageType) {
            'home' => $baseUrl . '/',
            'animals_index' => $baseUrl . '/animals',
            'animal' => $baseUrl . '/animals/' . rand(1, 10),
            'stories_index' => $baseUrl . '/stories',
            'story' => $baseUrl . '/stories/' . rand(1, 10),
            'donate' => $baseUrl . '/donate',
            'volunteer' => $baseUrl . '/volunteer',
            'contact' => $baseUrl . '/contact',
            default => $baseUrl . '/' . $pageType,
        };
    }

    private function generateRandomIp(): string
    {
        return rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255);
    }
}
