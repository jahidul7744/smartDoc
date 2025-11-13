<?php

namespace App\Repositories\Eloquent;

use App\Models\Diagnosis;
use App\Repositories\Contracts\DiagnosisRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DiagnosisRepository implements DiagnosisRepositoryInterface
{
    public function findByAppointmentId(int $appointmentId, array $relations = []): ?Diagnosis
    {
        return Diagnosis::query()
            ->with($relations)
            ->where('appointment_id', $appointmentId)
            ->first();
    }

    public function findById(int $id, array $relations = []): ?Diagnosis
    {
        return Diagnosis::query()
            ->with($relations)
            ->find($id);
    }

    public function create(array $attributes): Diagnosis
    {
        return DB::transaction(function () use ($attributes) {
            return Diagnosis::query()->create($attributes);
        });
    }

    public function update(Diagnosis $diagnosis, array $attributes): Diagnosis
    {
        $diagnosis->fill($attributes);
        $diagnosis->save();

        return $diagnosis;
    }
}

