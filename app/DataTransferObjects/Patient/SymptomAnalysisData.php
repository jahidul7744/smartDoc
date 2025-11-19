<?php

namespace App\DataTransferObjects\Patient;

/**
 * @phpstan-type SymptomEntries list<SymptomEntryData>
 */
class SymptomAnalysisData
{
    /**
     * @param SymptomEntries $symptoms
     */
    public function __construct(
        public readonly int $patientId,
        public readonly int $diagnosticCenterId,
        public readonly array $symptoms,
        public readonly ?string $notes = null
    ) {
    }
}

