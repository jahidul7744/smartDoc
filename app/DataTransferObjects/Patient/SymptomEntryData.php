<?php

namespace App\DataTransferObjects\Patient;

class SymptomEntryData
{
    public function __construct(
        public readonly string $name,
        public readonly int $severity,
        public readonly int $durationDays
    ) {
    }
}

