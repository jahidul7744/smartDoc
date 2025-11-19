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

    public function allWithUser(): \Illuminate\Database\Eloquent\Collection;

    /**
     * @param list<string> $specializations
     */
    public function listByCenterAndSpecializations(
        int $diagnosticCenterId,
        array $specializations,
        int $limit = 3
    ): \Illuminate\Database\Eloquent\Collection;
}

