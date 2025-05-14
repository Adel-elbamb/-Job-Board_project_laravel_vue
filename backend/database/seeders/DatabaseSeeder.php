<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create test employer
        User::factory()->create([
            'username' => 'employer1',
            'email' => 'employer@example.com',
            'password' => bcrypt('password'),
            'role' => 'employer',
        ]);

        // Create test candidate
        User::factory()->create([
            'username' => 'candidate1',
            'email' => 'candidate@example.com',
            'password' => bcrypt('password'),
            'role' => 'candidate',
        ]);
    }
}