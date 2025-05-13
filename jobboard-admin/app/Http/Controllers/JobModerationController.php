<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobModerationController extends Controller
{
    public function approve($id)
    {
        $job = \App\Models\Job::findOrFail($id);
        $job->status = 'approved';
        $job->save();

        return response()->json(['message' => 'Job approved']);
    }

    public function reject($id)
    {
        $job = \App\Models\Job::findOrFail($id);
        $job->status = 'rejected';
        $job->save();

        return response()->json(['message' => 'Job rejected']);
    }

}
