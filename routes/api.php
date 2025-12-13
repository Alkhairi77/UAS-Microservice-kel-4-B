<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\JenisLimbahController;

Route::get('/ping', function () {
    Log::info('api ping test');
    return response()->json([
        'message' => 'pong'
    ]);

    Route::middleware(['correlation'])->group(function () {
        Route::get('/jenis-limbah', [JenisLimbahController::class, 'apiIndex']);
    });
});
