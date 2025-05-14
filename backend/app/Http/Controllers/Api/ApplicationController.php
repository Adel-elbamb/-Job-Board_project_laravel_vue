<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'restrictTo:candidate']);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Please login to submit an application'], 401);
        }

        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'contact_info' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $application = Application::create([
            'user_id' => $user->id,
            'job_id' => $validated['job_id'],
            'contact_info' => $validated['contact_info'] ?? null,
            'resume' => $request->hasFile('resume') ? $request->file('resume')->store('resumes') : null,
        ]);

        return response()->json([
            'message' => 'Application submitted',
            'data' => ['id' => $application->id],
        ], 201);
    }

    public function myApplications()
    {
        $applications = Application::where('user_id', auth()->id)->with('job')->get();
        if ($applications->isEmpty()) {
            return response()->json(['message' => 'No applications found', 'data' => []], 200);
        }
        return response()->json(['data' => $applications], 200);
    }

    public function destroy($id)
    {
        $application = Application::where('user_id', auth()->id)->findOrFail($id);
        if ($application->resume_path) {
            Storage::disk('public')->delete($application->resume_path);
        }
        $application->delete();
        return response()->json(['message' => 'Application canceled', 'data' => ['id' => $id]], 200);
    }
}
