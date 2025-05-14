<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $jobs = Job::where('status', 'approved')->get();
        return response()->json(['data' => $jobs], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'salary' => 'nullable|numeric',
        'type' => 'required|in:remote,onsite,hybrid',
        'logo' => 'nullable|image|max:2048', // If logo upload is used
    ]);

    $job = new Job();
    $job->user_id = auth()->id;
    $job->title = $request->title;
    // ... other fields
    $job->save();

    return response()->json($job, 201);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}