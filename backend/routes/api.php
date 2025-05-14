<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Job;
use App\Http\Controllers\JobModerationController;
use App\Http\Controllers\AdminStatsController;
use App\Http\Controllers\AdminUserController;


//  تسجيل الدخول
Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('admin-token')->plainTextToken;

    return response()->json(['token' => $token]);
});

//  عرض الوظائف حسب الحالة
Route::get('/jobs', function (Request $request) {
    $status = $request->query('status', 'pending');
    return Job::where('status', $status)->get();
});

//  Routes محمية
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/jobs/{id}/approve', [JobModerationController::class, 'approve']);
    Route::post('/jobs/{id}/reject', [JobModerationController::class, 'reject']);

    Route::get('/admin/stats', [AdminStatsController::class, 'index']);

    Route::get('/users', [AdminUserController::class, 'index']);
    Route::post('/users/{id}/deactivate', [AdminUserController::class, 'deactivate']);
    Route::post('/users/{id}/activate', [AdminUserController::class, 'activate']);
});

Route::middleware(['auth:sanctum', 'admin'])->get('/users', function () {
    return \App\Models\User::all();
});









