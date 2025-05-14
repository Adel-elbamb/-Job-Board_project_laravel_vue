<?php

use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\ApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\ProfileManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::get('/jobs', [JobController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/profile', [ProfileManagementController::class, 'update']);

    Route::get('/dashboard/employer', function () {
        return response()->json(['message' => 'Welcome Employer']);
    })->middleware('restrictTo:employer');

    Route::get('/dashboard/candidate', function () {
        return response()->json(['message' => 'Welcome Candidate']);
    })->middleware('restrictTo:candidate');

    Route::get('/dashboard/admin', function () {
        return response()->json(['message' => 'Welcome Admin']);
    })->middleware('restrictTo:admin');

    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::get('/applications/my', [ApplicationController::class, 'myApplications'])->middleware('restrictTo:candidate');
    Route::delete('/applications/{id}', [ApplicationController::class, 'destroy'])->middleware('restrictTo:candidate');
});