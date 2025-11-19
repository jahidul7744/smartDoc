<?php

namespace App\DataTransferObjects\Patient;

class PredictionOptionData
{
    public function __construct(
        public readonly string $label,
        public readonly float $confidence
    ) {
    }
}

