<?php

namespace App\Console\Commands;

use App\Services\AnalyticsService;
use Illuminate\Console\Command;

class TestAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:analytics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the analytics service functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Analytics Service...');
        
        $service = new AnalyticsService();
        $stats = $service->getDashboardStats();
        
        $this->info('=== Dashboard Statistics ===');
        $this->line("Animals: {$stats['animals']['total']} total, {$stats['animals']['available']} available");
        $this->line("Stories: {$stats['stories']['total']} total, {$stats['stories']['published']} published");
        $this->line("Volunteers: {$stats['volunteers']['total']} total, {$stats['volunteers']['active']} active");
        $this->line("Page Visits: {$stats['page_visits']['this_month']} this month, {$stats['page_visits']['today']} today");
        
        $this->info('=== Adoption Rates ===');
        $this->line("Last 30 days: {$stats['adoption_rates']['last_30_days']} adoptions");
        $this->line("This year: {$stats['adoption_rates']['this_year']} adoptions");
        
        $this->info('=== Popular Pages ===');
        foreach ($stats['page_visits']['popular_pages'] as $page => $visits) {
            $this->line("- {$page}: {$visits} visits");
        }
        
        $this->info('Analytics service is working correctly!');
    }
}
