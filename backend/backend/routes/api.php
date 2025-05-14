<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\JobController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserIsEmployer;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', EnsureUserIsEmployer::class])->group(function () {
    // list all jobs of current user
    Route::get('/jobs', [JobController::class, 'index']);

    // add new job
    Route::post('/jobs', [JobController::class, 'store']);

    // update job
    Route::put('/jobs/{job}', [JobController::class, 'update']);

    // delete job
    Route::delete('/jobs/{job}', [JobController::class, 'destroy']);
});
