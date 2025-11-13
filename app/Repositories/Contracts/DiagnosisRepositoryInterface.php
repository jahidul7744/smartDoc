<?php

namespace App\Repositories\Contracts;

use App\Models\Diagnosis;

interface DiagnosisRepositoryInterface
{
    public function findById(int $id, array $relations = []): ?Diagnosis;

    public function findByAppointmentId(int $appointmentId, array $relations = []): ?Diagnosis;

    public function create(array $attributes): Diagnosis;

    public function update(Diagnosis $diagnosis, array $attributes): Diagnosis;
}

