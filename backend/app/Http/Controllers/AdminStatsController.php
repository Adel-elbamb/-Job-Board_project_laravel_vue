<?php


namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Job;

class AdminStatsController extends Controller
{
    public function index()
    {
        return response()->json([
            'total_users' => User::count(),
            'total_jobs' => Job::count(),
            'pending_jobs' => Job::where('status', 'pending')->count(),
        ]);
    }
}

