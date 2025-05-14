<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\JobController;

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserIsEmployer;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', EnsureUserIsEmployer::class])->group(function () {
    Route::get('/jobs', [JobController::class, 'index']);
    Route::get('/jobs/{id}', [JobController::class, 'show']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::put('/jobs/{job}', [JobController::class, 'update']);
    Route::delete('/jobs/{job}', [JobController::class, 'destroy']);
});
