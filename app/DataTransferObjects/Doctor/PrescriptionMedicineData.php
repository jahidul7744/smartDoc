<?php

namespace App\DataTransferObjects\Doctor;

final class PrescriptionMedicineData
{
    public function __construct(
        public readonly string $medicineName,
        public readonly ?string $dosage,
        public readonly ?string $frequency,
        public readonly ?string $duration,
        public readonly ?string $instructions
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            medicineName: $payload['medicine_name'],
            dosage: $payload['dosage'] ?? null,
            frequency: $payload['frequency'] ?? null,
            duration: $payload['duration'] ?? null,
            instructions: $payload['instructions'] ?? null
        );
    }

    /**
     * @return array<string, string|null>
     */
    public function toPersistenceArray(): array
    {
        return [
            'medicine_name' => $this->medicineName,
            'dosage' => $this->dosage,
            'frequency' => $this->frequency,
            'duration' => $this->duration,
            'instructions' => $this->instructions,
        ];
    }
}

