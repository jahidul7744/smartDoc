<?php

namespace App\Repositories\Contracts;

use App\Models\Doctor;

interface DoctorRepositoryInterface
{
    public function findById(int $id, array $relations = []): ?Doctor;

    public function findByUserId(int $userId, array $relations = []): ?Doctor;

    public function update(Doctor $doctor, array $attributes): Doctor;

    public function paginateForAdmin(array $filters, int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    public function create(array $attributes): Doctor;

    public function delete(Doctor $doctor): void;

    public function listByDiagnosticCenter(int $diagnosticCenterId): \Illuminate\Database\Eloquent\Collection;
}

