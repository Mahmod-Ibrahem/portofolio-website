<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Video;
use App\Models\HomePage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    /**
     * Increment site visit count (called from the frontend homepage).
     * Tracks unique visits per IP per day to prevent refreshes inflating the count.
     * Updates the database counter.
     */
    public function trackVisit(Request $request): JsonResponse
    {
        $ip = $request->ip();
        $today = now()->format('Y-m-d');
        $dailyKey = "visit_tracked:{$today}:{$ip}";

        // Only count once per IP per day
        if (!Cache::has($dailyKey)) {
            Cache::put($dailyKey, true, now()->endOfDay());

            // Update database counter
            HomePage::singleton()->increment('visit_count');

            // Also track today's visits separately for daily stats using cache
            Cache::increment("site_visits:{$today}");
        }

        return response()->json([
            'success' => true,
            'visit_count' => HomePage::singleton()->visit_count
        ]);
    }

    /**
     * Get dashboard statistics:
     * - Total site visits (from database)
     * - Today's visits (from cache)
     * - Total articles count
     * - Published articles count
     * - Total videos count
     * - Published videos count
     */
    public function dashboard(): JsonResponse
    {
        $today = now()->format('Y-m-d');
        $homePage = HomePage::singleton();

        return response()->json([
            'success' => true,
            'data' => [
                'total_visits'       => (int) $homePage->visit_count,
                'today_visits'       => (int) Cache::get("site_visits:{$today}", 0),
                'articles_count'     => Article::count(),
                'articles_published' => Article::where('is_published', true)->count(),
                'videos_count'       => Video::count(),
                'videos_published'   => Video::where('is_published', true)->count(),
            ],
        ]);
    }
}
