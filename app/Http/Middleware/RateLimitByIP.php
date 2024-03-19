<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RateLimitByIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();

        // Check if there's already an active session for this IP
        if (Cache::has('active_session_' . $ip)) {
            return response()->json(['error' => 'Another session is already in progress.'], 403);
        }

        // Set the active session for this IP
        Cache::put('active_session_' . $ip, true, now()->addMinutes(30));

        return $next($request);
    }
}