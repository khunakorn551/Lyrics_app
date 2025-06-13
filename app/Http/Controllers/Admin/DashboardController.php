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

        return view('admin.dashboard', compact('lyricsChartData', 'recentLyrics'));
    }

    private function getLyricsChartData()
    {
        $days = collect(range(6, 0))->map(function ($day) {
            return Carbon::now()->subDays($day)->format('M d');
        });

        $data = $days->map(function ($day) {
            return Lyrics::whereDate('created_at', Carbon::parse($day))->count();
        });

        return [
            'labels' => $days->toArray(),
            'data' => $data->toArray()
        ];
    }
} 