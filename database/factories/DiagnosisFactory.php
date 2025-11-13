<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Diagnosis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Diagnosis>
 */
class DiagnosisFactory extends Factory
{
    protected $model = Diagnosis::class;

    protected array $appointmentCache = [];

    public function definition(): array
    {
        return [
            'appointment_id' => function () {
                $appointment = AppointmentFactory::new()->completed()->create();

                return $appointment->id;
            },
            'doctor_id' => function (array $attributes) {
                return $this->resolveAppointmentDoctor($attributes['appointment_id']);
            },
            'patient_id' => function (array $attributes) {
                return $this->resolveAppointmentPatient($attributes['appointment_id']);
            },
            'final_diagnosis' => fake()->sentence(3),
            'clinical_notes' => fake()->paragraph(),
            'recommended_tests' => ['CBC', 'Chest X-Ray'],
            'follow_up_required' => fake()->boolean(),
            'follow_up_at' => fake()->optional()->dateTimeBetween('+1 week', '+6 weeks'),
        ];
    }

    protected function resolveAppointmentDoctor(int $appointmentId): int
    {
        return $this->resolveAppointment($appointmentId)->doctor_id;
    }

    protected function resolveAppointmentPatient(int $appointmentId): int
    {
        return $this->resolveAppointment($appointmentId)->patient_id;
    }

    protected function resolveAppointment(int $appointmentId): Appointment
    {
        if (! array_key_exists($appointmentId, $this->appointmentCache)) {
            $this->appointmentCache[$appointmentId] = Appointment::query()->findOrFail($appointmentId);
        }

        return $this->appointmentCache[$appointmentId];
    }
}

