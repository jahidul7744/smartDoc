<?php

namespace Database\Factories;

use App\Models\Diagnosis;
use App\Models\Prescription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prescription>
 */
class PrescriptionFactory extends Factory
{
    protected $model = Prescription::class;

    public function definition(): array
    {
        return [
            'diagnosis_id' => function () {
                return DiagnosisFactory::new()->create()->id;
            },
            'doctor_id' => function (array $attributes) {
                return $this->resolveDiagnosis($attributes['diagnosis_id'])->doctor_id;
            },
            'patient_id' => function (array $attributes) {
                return $this->resolveDiagnosis($attributes['diagnosis_id'])->patient_id;
            },
            'diagnostic_center_id' => function (array $attributes) {
                $diagnosis = $this->resolveDiagnosis($attributes['diagnosis_id']);

                return $diagnosis->appointment->diagnostic_center_id;
            },
            'general_instructions' => fake()->sentence(8),
            'follow_up_at' => fake()->optional()->dateTimeBetween('+1 week', '+8 weeks'),
            'pdf_path' => null,
            'issued_at' => now(),
        ];
    }

    protected function resolveDiagnosis(int $diagnosisId): Diagnosis
    {
        return Diagnosis::query()->with('appointment')->findOrFail($diagnosisId);
    }
}

