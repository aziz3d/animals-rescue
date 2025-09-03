<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track GET requests for public pages
        if ($request->isMethod('GET') && !$request->is('admin/*') && !$request->is('dashboard*')) {
            $this->trackVisit($request);
        }

        return $response;
    }

    /**
     * Track the page visit.
     */
    private function trackVisit(Request $request): void
    {
        try {
            $url = $request->url();
            $pageType = $this->determinePageType($request);
            $pageId = $this->extractPageId($request, $pageType);

            PageVisit::create([
                'url' => $url,
                'page_type' => $pageType,
                'page_id' => $pageId,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer' => $request->header('referer'),
            ]);
        } catch (\Exception $e) {
            logger()->error('Failed to track page visit: ' . $e->getMessage());
        }
    }

    /**
     * Determine the page type based on the request.
     */
    private function determinePageType(Request $request): string
    {
        $path = $request->path();

        if ($path === '/') {
            return 'home';
        }

        if (str_starts_with($path, 'animals/')) {
            return 'animal';
        }

        if (str_starts_with($path, 'stories/')) {
            return 'story';
        }

        if ($path === 'animals') {
            return 'animals_index';
        }

        if ($path === 'stories') {
            return 'stories_index';
        }

        if ($path === 'donate') {
            return 'donate';
        }

        if ($path === 'volunteer') {
            return 'volunteer';
        }

        if ($path === 'contact') {
            return 'contact';
        }

        return 'other';
    }

    /**
     * Extract the page ID for specific content pages.
     */
    private function extractPageId(Request $request, string $pageType): ?int
    {
        if (in_array($pageType, ['animal', 'story'])) {
            $segments = $request->segments();
            if (count($segments) >= 2) {
                return null;
            }
        }

        return null;
    }
}
