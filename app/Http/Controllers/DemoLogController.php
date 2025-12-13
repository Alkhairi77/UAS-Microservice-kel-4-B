<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DemoLogController extends Controller
{
    public function index(Request $request)
    {
        $correlationId = $request->attributes->get('correlation_id');

        Log::info('Incoming request', [
            'correlation_id' => $correlationId,
            'service' => 'service-demo'
        ]);

        Log::info('Process business logic', [
            'correlation_id' => $correlationId,
            'service' => 'service-demo'
        ]);

        return response()->json([
            'message' => 'Logging demo success',
            'correlation_id' => $correlationId
        ]);
    }
}
