<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    // Candidate applies to a job
    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }

        $application = Application::create([
            'user_id' => Auth::id(),
            'job_id' => $request->job_id,
            'resume_path' => $resumePath,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Application submitted successfully', 'application' => $application], 201);
    }

    // View candidate's applications
    public function myApplications()
    {
        $applications = Application::with('job')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json($applications);
    }

    // Cancel (delete) an application
    public function destroy($id)
    {
        $application = Application::where('user_id', Auth::id())->findOrFail($id);

        if ($application->resume_path) {
            Storage::disk('public')->delete($application->resume_path);
        }

        $application->delete();

        return response()->json(['message' => 'Application canceled successfully']);
    }
}
