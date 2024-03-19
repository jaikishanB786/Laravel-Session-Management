<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle($request, Closure $next, $key = 'default', $maxAttempts = 1, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            // Return response indicating rate limit exceeded
            return new Response('Too Many Attempts.', 429);
        }

        $this->limiter->hit($key, $decayMinutes);

        $response = $next($request);

        return $response;
    }

    protected function resolveRequestSignature($request)
    {
        return sha1($request->method().$request->path().$request->ip());
    }
}