<?php

namespace Database\Factories;

use App\Models\DiagnosticCenter;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'doctor']),
            'diagnostic_center_id' => DiagnosticCenter::factory(),
            'specialization' => fake()->randomElement(['Cardiology', 'Dermatology', 'General Medicine', 'Neurology']),
            'qualifications' => 'MBBS, FCPS',
            'experience_years' => fake()->numberBetween(1, 35),
            'consultation_fee' => fake()->numberBetween(500, 2000),
            'registration_number' => 'BMDC-' . fake()->unique()->numerify('#####'),
            'bio' => fake()->sentence(12),
            'rating' => fake()->randomFloat(2, 3.5, 5),
            'rating_count' => fake()->numberBetween(0, 250),
            'is_active' => true,
        ];
    }
}

