<?php

use App\Http\Controllers\OrchestratorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'Orchestrator Service',
        'timestamp' => now()
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Orchestrator API Routes
Route::prefix('orchestrator')->group(function () {
    // Auth endpoints with limbah data
    Route::post('/login', [OrchestratorController::class, 'loginWithLimbahData'])
        ->name('orchestrator.login');
    
    Route::post('/register', [OrchestratorController::class, 'registerWithLimbahData'])
        ->name('orchestrator.register');
    
    // User with limbah data (requires token)
    Route::get('/user-with-limbah', [OrchestratorController::class, 'getUserWithLimbah'])
        ->name('orchestrator.user-limbah');
    
    // Get limbah by category
    Route::get('/limbah-by-category/{kategori}', [OrchestratorController::class, 'getLimbahByCategory'])
        ->name('orchestrator.limbah-category');
});
