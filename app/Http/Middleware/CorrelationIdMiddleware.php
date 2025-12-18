<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CorrelationIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get correlation ID from header or generate new one
        $correlationId = $request->header('X-Correlation-ID') ?? (string) Str::uuid();

        // Store in request attributes for later use
        $request->attributes->set('correlation_id', $correlationId);

        // Add to log context
        Log::withContext([
            'correlation_id' => $correlationId,
            'service' => config('app.service_name', 'user-service'),
        ]);

        // Incoming Request
        Log::info('Permintaan Masuk');

        // Process the request
        $response = $next($request);

        // Add correlation ID to response headers
        $response->headers->set('X-Correlation-ID', $correlationId);

        return $response;
    }
}
