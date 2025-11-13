<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\DiagnosticCenter;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'diagnostic_center_id' => function (array $attributes) {
                $doctor = Doctor::find($attributes['doctor_id']);

                if ($doctor === null) {
                    return DiagnosticCenter::factory()->create()->id;
                }

                return $doctor->diagnostic_center_id;
            },
            'scheduled_at' => fake()->dateTimeBetween('+1 day', '+4 weeks'),
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed']),
            'chief_complaint' => fake()->sentence(),
            'predicted_illness' => fake()->optional()->randomElement(['Flu', 'Migraine', 'Hypertension']),
            'symptoms' => [
                ['name' => 'fever', 'severity' => 5, 'duration' => '2 days'],
                ['name' => 'cough', 'severity' => 3, 'duration' => '1 day'],
            ],
            'ai_confidence' => fake()->randomFloat(2, 0.5, 0.95),
            'follow_up_parent_id' => null,
        ];
    }

    public function completed(): self
    {
        return $this->state(fn () => [
            'status' => 'completed',
            'scheduled_at' => fake()->dateTimeBetween('-4 weeks', '-1 day'),
        ]);
    }
}

