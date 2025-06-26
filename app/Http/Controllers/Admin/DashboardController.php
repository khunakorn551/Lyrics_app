<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lyrics;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get lyrics upload trend data for the last 7 days
        $lyricsChartData = $this->getLyricsChartData();
 
        // Get recent uploads with pagination
        $recentLyrics = Lyrics::with('user')->latest()->paginate(10); // Paginate with 10 items per page
 
        // User activity data
        $userActivityData = [
            'new_users' => \App\Models\User::where('created_at', '>=', now()->subDays(7))->count(),
            'active_users' => \App\Models\User::where('last_login_at', '>=', now()->subDays(7))->count(),
            'total_bookmarks' => \App\Models\Bookmark::count(),
        ];
 
        // Visits analytics for the last 7 days
        $visitDays = collect(range(6, 0))->map(function ($day) {
            return \Carbon\Carbon::now()->subDays($day);
        });
        $visitLabels = $visitDays->map(fn($date) => $date->format('M d'));
        $visitCounts = $visitDays->map(fn($date) => Visit::whereDate('visited_at', $date->toDateString())->count());
        $avgDurations = $visitDays->map(function ($date) {
            $visits = Visit::whereDate('visited_at', $date->toDateString())->whereNotNull('duration')->get();
            if ($visits->count() === 0) return 0;
            return round($visits->avg('duration') / 60, 2); // in minutes
        });
        $visitAnalytics = [
            'labels' => $visitLabels->toArray(),
            'counts' => $visitCounts->toArray(),
            'avg_durations' => $avgDurations->toArray(),
        ];
 
        dd($visitAnalytics);

        return view('admin.dashboard', compact('lyricsChartData', 'recentLyrics', 'userActivityData', 'visitAnalytics'));
    }

    private function getLyricsChartData()
    {
        $days = collect(range(6, 0))->map(function ($day) {
            return Carbon::now()->subDays($day);
        });

        $labels = $days->map(function ($date) {
            return $date->format('M d');
        });

        $data = $days->map(function ($date) {
            return Lyrics::whereDate('created_at', $date->toDateString())->count();
        });

        return [
            'labels' => $labels->toArray(),
            'data' => $data->toArray()
        ];
    }
} 