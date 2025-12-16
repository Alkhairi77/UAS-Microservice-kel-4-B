<?php

use App\Http\Controllers\JenisLimbahController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Jenis Limbah API Routes
Route::prefix('jenis-limbah')->group(function () {
    Route::get('/', [JenisLimbahController::class, 'index'])->name('jenis-limbah.index');
    Route::post('/', [JenisLimbahController::class, 'store'])->name('jenis-limbah.store');
    Route::get('/{id}', [JenisLimbahController::class, 'show'])->name('jenis-limbah.show');
    Route::put('/{id}', [JenisLimbahController::class, 'update'])->name('jenis-limbah.update');
    Route::delete('/{id}', [JenisLimbahController::class, 'destroy'])->name('jenis-limbah.destroy');
});
