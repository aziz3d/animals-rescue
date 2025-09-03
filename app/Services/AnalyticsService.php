<?php

namespace App\Services;

use App\Models\Animal;
use App\Models\Story;
use App\Models\Volunteer;
use App\Models\Contact;
use App\Models\Donation;
use App\Models\PageVisit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get comprehensive dashboard statistics
     */
    public function getDashboardStats(): array
    {
        return [
            'animals' => $this->getAnimalStats(),
            'stories' => $this->getStoryStats(),
            'volunteers' => $this->getVolunteerStats(),
            'contacts' => $this->getContactStats(),
            'donations' => $this->getDonationStats(),
            'adoption_rates' => $this->getAdoptionRates(),
            'monthly_trends' => $this->getMonthlyTrends(),
            'page_visits' => $this->getPageVisitStats(),
        ];
    }

    /**
     * Get detailed animal statistics
     */
    public function getAnimalStats(): array
    {
        $total = Animal::count();
        $available = Animal::where('adoption_status', 'available')->count();
        $pending = Animal::where('adoption_status', 'pending')->count();
        $adopted = Animal::where('adoption_status', 'adopted')->count();

        return [
            'total' => $total,
            'available' => $available,
            'pending' => $pending,
            'adopted' => $adopted,
            'adoption_rate' => $total > 0 ? round(($adopted / $total) * 100, 1) : 0,
            'featured' => Animal::where('featured', true)->count(),
            'recent_additions' => Animal::where('created_at', '>=', now()->subDays(7))->count(),
        ];
    }

    /**
     * Get story statistics
     */
    public function getStoryStats(): array
    {
        return [
            'total' => Story::count(),
            'published' => Story::whereNotNull('published_at')->count(),
            'drafts' => Story::whereNull('published_at')->count(),
            'featured' => Story::where('featured', true)->count(),
            'recent_published' => Story::where('published_at', '>=', now()->subDays(7))->count(),
            'by_category' => Story::select('category', DB::raw('count(*) as count'))
                ->groupBy('category')
                ->pluck('count', 'category')
                ->toArray(),
        ];
    }

    /**
     * Get volunteer statistics
     */
    public function getVolunteerStats(): array
    {
        return [
            'total' => Volunteer::count(),
            'pending' => Volunteer::where('status', 'pending')->count(),
            'approved' => Volunteer::where('status', 'approved')->count(),
            'active' => Volunteer::where('status', 'active')->count(),
            'inactive' => Volunteer::where('status', 'inactive')->count(),
            'recent_applications' => Volunteer::where('applied_at', '>=', now()->subDays(7))->count(),
        ];
    }

    /**
     * Get contact statistics
     */
    public function getContactStats(): array
    {
        return [
            'total' => Contact::count(),
            'new' => Contact::where('status', 'new')->count(),
            'read' => Contact::where('status', 'read')->count(),
            'replied' => Contact::where('status', 'replied')->count(),
            'unread' => Contact::whereIn('status', ['new', 'read'])->count(),
            'recent_messages' => Contact::where('created_at', '>=', now()->subDays(7))->count(),
        ];
    }

    /**
     * Get donation statistics
     */
    public function getDonationStats(): array
    {
        $completed = Donation::completed();
        
        return [
            'total' => Donation::count(),
            'completed' => $completed->count(),
            'pending' => Donation::pending()->count(),
            'failed' => Donation::where('status', 'failed')->count(),
            'total_amount' => $completed->sum('amount'),
            'average_donation' => $completed->avg('amount') ?: 0,
            'recurring_donors' => Donation::recurring()->completed()->count(),
            'recent_donations' => Donation::where('created_at', '>=', now()->subDays(7))->count(),
        ];
    }

    /**
     * Calculate adoption rates over time
     */
    public function getAdoptionRates(): array
    {
        $last30Days = Animal::where('adoption_status', 'adopted')
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();

        $last90Days = Animal::where('adoption_status', 'adopted')
            ->where('updated_at', '>=', now()->subDays(90))
            ->count();

        $thisYear = Animal::where('adoption_status', 'adopted')
            ->whereYear('updated_at', now()->year)
            ->count();

        return [
            'last_30_days' => $last30Days,
            'last_90_days' => $last90Days,
            'this_year' => $thisYear,
            'monthly_average' => round($thisYear / max(now()->month, 1), 1),
        ];
    }

    /**
     * Get monthly trends for the last 6 months
     */
    public function getMonthlyTrends(): array
    {
        $months = [];
        $trends = [
            'animals_added' => [],
            'adoptions' => [],
            'volunteers' => [],
            'donations' => [],
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('M Y');
            $months[] = $monthKey;

            // Animals added
            $trends['animals_added'][] = Animal::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            // Adoptions (animals marked as adopted)
            $trends['adoptions'][] = Animal::where('adoption_status', 'adopted')
                ->whereYear('updated_at', $date->year)
                ->whereMonth('updated_at', $date->month)
                ->count();

            // Volunteer applications
            $trends['volunteers'][] = Volunteer::whereYear('applied_at', $date->year)
                ->whereMonth('applied_at', $date->month)
                ->count();

            // Completed donations
            $trends['donations'][] = Donation::completed()
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return [
            'months' => $months,
            'data' => $trends,
        ];
    }

    /**
     * Get recent activity for dashboard
     */
    public function getRecentActivity(int $limit = 10): array
    {
        $activities = collect();

        // Recent animals
        Animal::latest()->take($limit / 2)->get()->each(function ($animal) use ($activities) {
            $activities->push([
                'type' => 'animal_added',
                'title' => "{$animal->name} added to rescue",
                'subtitle' => "{$animal->species} • {$animal->breed}",
                'time' => $animal->created_at,
                'icon' => 'heart',
                'color' => 'blue',
                'url' => route('admin.animals.show', $animal),
            ]);
        });

        // Recent contacts
        Contact::latest()->take($limit / 2)->get()->each(function ($contact) use ($activities) {
            $activities->push([
                'type' => 'contact_received',
                'title' => "Message from {$contact->name}",
                'subtitle' => $contact->subject,
                'time' => $contact->created_at,
                'icon' => 'envelope',
                'color' => 'orange',
                'url' => route('admin.contacts.show', $contact),
            ]);
        });

        // Recent volunteer applications
        Volunteer::latest()->take($limit / 4)->get()->each(function ($volunteer) use ($activities) {
            $activities->push([
                'type' => 'volunteer_applied',
                'title' => "{$volunteer->name} applied to volunteer",
                'subtitle' => "Status: {$volunteer->status}",
                'time' => $volunteer->applied_at,
                'icon' => 'user-group',
                'color' => 'purple',
                'url' => route('admin.volunteers.show', $volunteer),
            ]);
        });

        return $activities->sortByDesc('time')->take($limit)->values()->toArray();
    }

    /**
     * Get page visit statistics
     */
    public function getPageVisitStats(): array
    {
        $today = PageVisit::whereDate('created_at', today())->count();
        $thisWeek = PageVisit::where('created_at', '>=', now()->startOfWeek())->count();
        $thisMonth = PageVisit::whereMonth('created_at', now()->month)->count();

        $popularPages = PageVisit::select('page_type', DB::raw('count(*) as visits'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('page_type')
            ->orderByDesc('visits')
            ->limit(5)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->page_type => $item->visits];
            })
            ->toArray();

        return [
            'total' => PageVisit::count(),
            'today' => $today,
            'this_week' => $thisWeek,
            'this_month' => $thisMonth,
            'popular_pages' => $popularPages,
            'daily_average' => round($thisMonth / max(now()->day, 1), 1),
        ];
    }
}