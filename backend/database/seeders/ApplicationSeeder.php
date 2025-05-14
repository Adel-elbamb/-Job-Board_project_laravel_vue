<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $candidate = User::where('role', 'candidate')->first();
        if (!$candidate) {
            $candidate = User::create([
                'username' => 'mohamed',
                'email' => 'mohamed@gmail.com',
                'password' => Hash::make('123456'),
                'role' => 'candidate',
                'email_verified_at' => now(),
            ]);
        }

        $employer = User::where('role', 'employer')->first();
        if (!$employer) {
            $employer = User::create([
                'username' => 'ali',
                'email' => 'ali@gmail.com',
                'password' => Hash::make('123456'),
                'role' => 'employer',
                'email_verified_at' => now(),
            ]);
        }

        $job = Job::where('status', 'approved')->first();
        if (!$job) {
            $job = Job::create([
                'user_id' => $employer->id,
                'title' => 'Test Job',
                'description' => 'Test job description.',
                'responsibilities' => 'Test responsibilities.',
                'qualifications' => 'Test qualifications.',
                'salary_from' => 60000.00,
                'salary_to' => 80000.00,
                'location' => 'Test Location',
                'job_type' => 'remote',
                'deadline' => now()->addMonth(),
                'company_logo' => null,
                'status' => 'approved',
            ]);
        }

        Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'resume_path' => 'resumes/sample.pdf',
            'contact_info' => null,
        ]);

        Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'resume_path' => null,
            'contact_info' => 'Phone: 123-456-7890, Email: mohamed@gmail.com',
        ]);
    }
}