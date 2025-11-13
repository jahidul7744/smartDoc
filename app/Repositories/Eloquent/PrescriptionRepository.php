<?php

namespace App\Repositories\Eloquent;

use App\Models\Prescription;
use App\Repositories\Contracts\PrescriptionRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PrescriptionRepository implements PrescriptionRepositoryInterface
{
    public function findByDiagnosisId(int $diagnosisId, array $relations = []): ?Prescription
    {
        return Prescription::query()
            ->with($relations)
            ->where('diagnosis_id', $diagnosisId)
            ->first();
    }

    public function createWithMedicines(array $prescriptionAttributes, array $medicines): Prescription
    {
        return DB::transaction(function () use ($prescriptionAttributes, $medicines) {
            /** @var Prescription $prescription */
            $prescription = Prescription::query()->create($prescriptionAttributes);
            $this->syncMedicines($prescription, $medicines);

            return $prescription->load('medicines');
        });
    }

    public function updateWithMedicines(Prescription $prescription, array $attributes, array $medicines): Prescription
    {
        return DB::transaction(function () use ($prescription, $attributes, $medicines) {
            $prescription->fill($attributes);
            $prescription->save();
            $this->syncMedicines($prescription, $medicines);

            return $prescription->load('medicines');
        });
    }

    public function latestForDoctor(int $doctorId, int $limit = 10, array $relations = []): Collection
    {
        return Prescription::query()
            ->with($relations)
            ->where('doctor_id', $doctorId)
            ->orderByDesc('issued_at')
            ->limit($limit)
            ->get();
    }

    protected function syncMedicines(Prescription $prescription, array $medicines): void
    {
        $prescription->medicines()->delete();
        $prescription->medicines()->createMany($medicines);
    }
}

