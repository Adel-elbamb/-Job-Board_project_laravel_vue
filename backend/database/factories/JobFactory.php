<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'user_id' => User::where('role', 'employer')->first()->id ?? User::factory()->create(['role' => 'employer'])->id,
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(),
            'responsibilities' => $this->faker->paragraph(),
            'qualifications' => $this->faker->sentence(),
            'salary_from' => $this->faker->numberBetween(50000, 80000),
            'salary_to' => $this->faker->numberBetween(80000, 120000),
            'location' => $this->faker->city(),
            'job_type' => $this->faker->randomElement(['remote', 'on-site', 'hybrid']),
            'deadline' => $this->faker->dateTimeBetween('now', '+1 year'),
            'company_logo' => null,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}