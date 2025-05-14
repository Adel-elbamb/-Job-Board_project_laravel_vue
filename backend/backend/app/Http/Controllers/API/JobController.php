<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    // list jobs
    public function index()
    {
        $jobs = Job::where('user_id', Auth::id())->latest()->get();
        return response()->json($jobs);
    }

    // add job
    public function store(StoreJobRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('company_logo')) {
            $data['company_logo'] = $request->file('company_logo')->store('logos', 'public');
        }

        $job = Job::create($data);
        return response()->json(['message' => 'Job created successfully', 'job' => $job], 201);
    }

    // update job
    public function update(UpdateJobRequest $request, Job $job)
    {
        if ($job->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->validated();
        if ($request->hasFile('company_logo')) {
            if ($job->company_logo) {
                Storage::disk('public')->delete($job->company_logo);
            }
            $data['company_logo'] = $request->file('company_logo')->store('logos', 'public');
        }
        $job->update($data);
        return response()->json(['message' => 'Job updated successfully', 'job' => $job]);
    }

    // delete job
    public function destroy(Job $job)
    {
        if ($job->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($job->company_logo) {
            Storage::disk('public')->delete($job->company_logo);
        }

        $job->delete();

        return response()->json(['message' => 'Job deleted successfully']);
    }
}
