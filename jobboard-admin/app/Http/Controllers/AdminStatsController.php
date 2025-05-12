<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\Application;

class AdminStatsController extends Controller
{
    public function index()
    {
        return response()->json([
            'jobs_count' => Job::count(),
            'users_count' => User::count(),
            'applications_count' => Application::count(),
        ]);
    }
}
