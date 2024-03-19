<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();

        if(Cache::has('active_session_' . $ip)) {
            return response()->json(['message' => 'Another Session is alreadt in progress'], 403);
        }

        Cache::put('actuve_session_' . $ip, true, now()->addMinutes(30));

        return $next($request);
    }
}