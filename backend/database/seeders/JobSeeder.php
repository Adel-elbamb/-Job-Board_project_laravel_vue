<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $employer = User::where('role', 'employer')->first();
        if (!$employer) {
            $employer = User::create([
                'username' => 'Abdalla',
                'email' => 'Abdalla@example.com',
                'password' => Hash::make('password'),
                'role' => 'employer',
                'email_verified_at' => now(),
            ]);
        }

        Job::create([
            'user_id' => $employer->id,
            'title' => 'Software Engineer',
            'description' => 'Develop and maintain web applications using Laravel and Vue.js.',
            'responsibilities' => 'Write clean code, collaborate with team, deliver features.',
            'qualifications' => '3+ years of experience in PHP and JavaScript.',
            'salary_from' => 80000.00,
            'salary_to' => 100000.00,
            'location' => 'Remote',
            'job_type' => 'remote',
            'deadline' => now()->addMonth(),
            'company_logo' => null,
            'status' => 'approved',
        ]);

        Job::create([
            'user_id' => $employer->id,
            'title' => 'Frontend Developer',
            'description' => 'Build responsive UI with Vue.js and Tailwind CSS.',
            'responsibilities' => 'Design and implement user interfaces.',
            'qualifications' => 'Experience with Vue 3 and Tailwind CSS.',
            'salary_from' => 70000.00,
            'salary_to' => 90000.00,
            'location' => 'New York',
            'job_type' => 'on-site',
            'deadline' => now()->addWeeks(2),
            'company_logo' => null,
            'status' => 'approved',
        ]);
    }
}
