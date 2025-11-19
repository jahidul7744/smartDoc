<?php

namespace App\DataTransferObjects\Patient;

use Illuminate\Support\Collection;

/**
 * @phpstan-import-type SymptomEntries from SymptomAnalysisData
 */
class SymptomAnalysisResult
{
    /**
     * @param list<PredictionOptionData> $alternatives
     * @param list<string> $recommendedSpecializations
     * @param Collection<int, array<string, mixed>> $recommendedDoctors
     */
    public function __construct(
        public readonly PredictionOptionData $primaryPrediction,
        public readonly array $alternatives,
        public readonly array $recommendedSpecializations,
        public readonly Collection $recommendedDoctors
    ) {
    }
}

