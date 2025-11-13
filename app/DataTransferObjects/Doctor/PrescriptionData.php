<?php

namespace App\DataTransferObjects\Doctor;

use Carbon\CarbonImmutable;

final class PrescriptionData
{
    /**
     * @param  list<PrescriptionMedicineData>  $medicines
     */
    public function __construct(
        public readonly ?string $generalInstructions,
        public readonly ?CarbonImmutable $followUpAt,
        public readonly array $medicines
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $medicines = array_map(
            static fn (array $medicinePayload) => PrescriptionMedicineData::fromArray($medicinePayload),
            $payload['medicines'] ?? []
        );

        $followUpAt = null;

        if (! empty($payload['follow_up_at'])) {
            $followUpAt = CarbonImmutable::parse($payload['follow_up_at']);
        }

        return new self(
            generalInstructions: $payload['general_instructions'] ?? null,
            followUpAt: $followUpAt,
            medicines: $medicines
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toPersistenceArray(int $diagnosisId, int $doctorId, int $patientId, int $diagnosticCenterId): array
    {
        return [
            'diagnosis_id' => $diagnosisId,
            'doctor_id' => $doctorId,
            'patient_id' => $patientId,
            'diagnostic_center_id' => $diagnosticCenterId,
            'general_instructions' => $this->generalInstructions,
            'follow_up_at' => $this->followUpAt,
            'issued_at' => now(),
        ];
    }

    /**
     * @return list<array<string, string|null>>
     */
    public function medicinesToPersistenceArray(): array
    {
        return array_map(
            static fn (PrescriptionMedicineData $medicine) => $medicine->toPersistenceArray(),
            $this->medicines
        );
    }
}

