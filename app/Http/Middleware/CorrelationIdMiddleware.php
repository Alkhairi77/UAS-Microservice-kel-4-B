<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CorrelationIdMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $correlationId = $request->header('X-Correlation-Id')
            ?? (string) Str::uuid();

        // Simpan ke request
        $request->attributes->set('correlation_id', $correlationId);

        // Log context (WAJIB UNTUK POINT E)
        Log::info('Incoming request', [
            'correlation_id' => $correlationId,
            'service' => 'service-demo',
            'path' => $request->path(),
            'method' => $request->method(),
        ]);

        $response = $next($request);

        // Tambahkan ke response header
        $response->headers->set('X-Correlation-Id', $correlationId);

        return $response;
    }
}
