<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ProfileManagementController;
use App\Http\Controllers\API\JobController;
use App\Http\Middleware\EnsureUserIsEmployer;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;


/*
|--------------------------------------------------------------------------|
| Public Routes                                                            |
|--------------------------------------------------------------------------|
*/

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);

/*
|--------------------------------------------------------------------------|
| Protected Routes (require auth:sanctum)                                  |
|--------------------------------------------------------------------------|
*/

Route::middleware('auth:sanctum')->group(function () {

    // Get authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Profile Management
    Route::put('/profile', [ProfileManagementController::class, 'update']);

    // Dashboards per role
    Route::get('/dashboard/employer', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'message' => 'Welcome Employer',
            'user' => $user,
            'jobs' => $user->jobs, 
        ]);
    })->middleware('restrictTo:employer');

    Route::get('/dashboard/candidate', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'message' => 'Welcome Candidate',
            'user' => $user,
            'applied_jobs' => $user->jobs()->where('status', 'applied')->get(), 
        ]);
    })->middleware('restrictTo:candidate');

    Route::get('/dashboard/admin', function () {
        return response()->json(['message' => 'Welcome Admin']);
    })->middleware('restrictTo:admin');

    // Job Management (only for Employers)
    Route::middleware(EnsureUserIsEmployer::class)->group(function () {
        Route::get('/jobs', [JobController::class, 'index']);
        Route::get('/jobs/{id}', [JobController::class, 'show']);
        Route::post('/jobs', [JobController::class, 'store']);
        Route::put('/jobs/{job}', [JobController::class, 'update']);
        Route::delete('/jobs/{job}', [JobController::class, 'destroy']);
    });
});
