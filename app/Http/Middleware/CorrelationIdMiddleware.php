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

        // SIMPAN KE REQUEST
        $request->attributes->set('correlation_id', $correlationId);

        // LOG (INI WAJIB MUNCUL)
        Log::info('CorrelationId Middleware executed', [
            'correlation_id' => $correlationId,
            'path' => $request->path()
        ]);

        $response = $next($request);

        // TAMBAHKAN KE RESPONSE HEADER
        $response->headers->set('X-Correlation-Id', $correlationId);

        return $response;
    }
}
