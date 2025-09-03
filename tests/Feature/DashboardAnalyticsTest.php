<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AnalyticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that analytics service returns proper structure.
     */
    public function test_analytics_service_returns_proper_structure(): void
    {
        $service = new AnalyticsService();
        $stats = $service->getDashboardStats();

        $this->assertArrayHasKey('animals', $stats);
        $this->assertArrayHasKey('stories', $stats);
        $this->assertArrayHasKey('volunteers', $stats);
        $this->assertArrayHasKey('contacts', $stats);
        $this->assertArrayHasKey('donations', $stats);
        $this->assertArrayHasKey('adoption_rates', $stats);
        $this->assertArrayHasKey('monthly_trends', $stats);
        $this->assertArrayHasKey('page_visits', $stats);
    }

    /**
     * Test that dashboard loads for authenticated user.
     */
    public function test_dashboard_loads_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
        $response->assertSee('Quick Actions');
    }

    /**
     * Test that dashboard redirects for unauthenticated user.
     */
    public function test_dashboard_redirects_for_unauthenticated_user(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test recent activity functionality.
     */
    public function test_recent_activity_returns_activities(): void
    {
        $service = new AnalyticsService();
        $activities = $service->getRecentActivity(5);

        $this->assertIsArray($activities);
        $this->assertLessThanOrEqual(5, count($activities));
    }
}
