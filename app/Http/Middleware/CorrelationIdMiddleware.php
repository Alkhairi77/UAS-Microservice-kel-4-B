<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CorrelationIdMiddleware
{
    public function handle($request, Closure $next)
    {
        $correlationId = $request->header('X-Correlation-ID')
            ?? (string) Str::uuid();

        // set ke request
        $request->headers->set('X-Correlation-ID', $correlationId);

        // set ke log context
        Log::withContext([
            'correlation_id' => $correlationId,
            'service' => config('app.name'),
        ]);

        // Incoming Request
        Log::info('Permintaan Masuk');

        $response = $next($request);

        // set ke response
        $response->headers->set('X-Correlation-ID', $correlationId);

        return $response;
    }
}
