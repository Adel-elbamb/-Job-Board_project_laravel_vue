<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProfileManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;



Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/profile', [ProfileManagementController::class, 'update']);

    Route::get('/dashboard/employerdashboard', function () {
        return response()->json(['message' => 'Welcome Employer']);
    })->middleware(\App\Http\Middleware\RestrictTo::class .'restrictTo:employer');

    Route::get('/dashboard/candidatedashboard', function () {
        return response()->json(['message' => 'Welcome Candidate']);
    })->middleware(\App\Http\Middleware\RestrictTo::class . ':candidate');

    Route::get('/dashboard/admindashboard', function () {
        return response()->json(['message' => 'Welcome Admin']);
    })->middleware(\App\Http\Middleware\RestrictTo::class .'restrictTo:admin');
});



