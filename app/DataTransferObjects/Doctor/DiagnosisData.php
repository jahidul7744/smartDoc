<?php

namespace App\DataTransferObjects\Doctor;

use Carbon\CarbonImmutable;

final class DiagnosisData
{
    /**
     * @param  list<string>  $recommendedTests
     */
    public function __construct(
        public readonly string $finalDiagnosis,
        public readonly ?string $clinicalNotes,
        public readonly array $recommendedTests,
        public readonly bool $followUpRequired,
        public readonly ?CarbonImmutable $followUpAt
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $recommendedTests = array_values(array_filter(
            $payload['recommended_tests'] ?? [],
            fn ($test) => filled($test)
        ));

        $followUpAt = null;

        if (! empty($payload['follow_up_at'])) {
            $followUpAt = CarbonImmutable::parse($payload['follow_up_at']);
        }

        return new self(
            finalDiagnosis: $payload['final_diagnosis'],
            clinicalNotes: $payload['clinical_notes'] ?? null,
            recommendedTests: $recommendedTests,
            followUpRequired: (bool) ($payload['follow_up_required'] ?? false),
            followUpAt: $followUpAt
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toPersistenceArray(int $appointmentId, int $doctorId, int $patientId): array
    {
        return [
            'appointment_id' => $appointmentId,
            'doctor_id' => $doctorId,
            'patient_id' => $patientId,
            'final_diagnosis' => $this->finalDiagnosis,
            'clinical_notes' => $this->clinicalNotes,
            'recommended_tests' => $this->recommendedTests,
            'follow_up_required' => $this->followUpRequired,
            'follow_up_at' => $this->followUpAt,
        ];
    }
}

