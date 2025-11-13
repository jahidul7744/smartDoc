<?php

namespace App\Repositories\Contracts;

use App\Models\Prescription;
use Illuminate\Support\Collection;

interface PrescriptionRepositoryInterface
{
    public function findByDiagnosisId(int $diagnosisId, array $relations = []): ?Prescription;

    public function createWithMedicines(array $prescriptionAttributes, array $medicines): Prescription;

    public function updateWithMedicines(Prescription $prescription, array $attributes, array $medicines): Prescription;

    public function latestForDoctor(int $doctorId, int $limit = 10, array $relations = []): Collection;
}

