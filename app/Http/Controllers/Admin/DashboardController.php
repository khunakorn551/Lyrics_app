<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lyrics;
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

        return view('admin.dashboard', compact('lyricsChartData', 'recentLyrics', 'userActivityData'));
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