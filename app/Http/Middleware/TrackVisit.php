<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Visit;
use Carbon\Carbon;

class TrackVisit
{
    public function handle(Request $request, Closure $next)
    {
        \Log::info('TrackVisit middleware fired', [
            'session_id' => Session::getId(),
            'ip' => $request->ip(),
            'user_id' => Auth::check() ? Auth::id() : null,
            'url' => $request->fullUrl(),
        ]);

        $sessionId = Session::getId();
        $userId = Auth::check() ? Auth::id() : null;
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $now = Carbon::now();

        $visit = Visit::where('session_id', $sessionId)
            ->whereDate('visited_at', $now->toDateString())
            ->first();

        if (!$visit) {
            $visit = Visit::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'visited_at' => $now,
                'last_activity' => $now,
                'duration' => 0,
            ]);
        } else {
            $duration = $now->diffInSeconds(Carbon::parse($visit->last_activity));
            $visit->update([
                'last_activity' => $now,
                'duration' => ($visit->duration ?? 0) + $duration,
            ]);
        }

        return $next($request);
    }
} 