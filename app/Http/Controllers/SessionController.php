<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SessionController extends Controller
{
    public function continueSession(Request $req) {
        $ip = $req->ip();

        Cache::forget('active_session_' . $ip);

        return response()->json(['message' => 'Previous session closed.']);
    }

    public function startSession(Request $request)
    {
        // Check if there's already an active session for this IP
        if (!session()->has('active_session')) {
            // Set the active_session variable in the session
            session(['active_session' => true]);
            return response()->json(['message' => 'Session started.'], 200);
        } else {
            // Another session is already in progress
            return response()->json(['error' => 'Another session is already in progress.'], 403);
        }
    }

    // public function closeSession(Request $request)
    // {
    //     $ip = $request->ip();

    //     // Check if there's an active session for this IP
    //     if (Cache::has('active_session_' . $ip)) {
    //         // Remove the active session for this IP
    //         Cache::forget('active_session_' . $ip);

    //         return response()->json(['message' => 'Previous session closed.'], 200);
    //     } else {
    //         return response()->json(['error' => 'No active session to close.'], 404);
    //     }
    // }

    public function index(Request $request)
    {
        // Check if session is in progress
        $sessionInProgress = Cache::has('session:'.$request->ip());

        if ($sessionInProgress) {
            return view('session-management.session-prompt');
        }

        // Start session
        Cache::put('session:'.$request->ip(), true, now()->addMinutes(1));

        return view('session-management.index');
    }

    public function closeSession(Request $request)
    {
        // Close session
        Cache::forget('session:'.$request->ip());
        return redirect('/session');
    }
}