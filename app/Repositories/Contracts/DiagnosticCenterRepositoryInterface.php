<?php

namespace App\Repositories\Contracts;

use App\Models\DiagnosticCenter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface DiagnosticCenterRepositoryInterface
{
    public function paginateActive(array $filters, array $sort, int $perPage = 12): LengthAwarePaginator;

    public function findActive(int $id): ?DiagnosticCenter;

    public function paginateForAdmin(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function allForSelect(): Collection;

    public function find(int $id, array $relations = []): ?DiagnosticCenter;

    public function create(array $attributes): DiagnosticCenter;

    public function update(DiagnosticCenter $diagnosticCenter, array $attributes): DiagnosticCenter;

    public function delete(DiagnosticCenter $diagnosticCenter): void;

    public function slugExists(string $slug, ?int $ignoreId = null): bool;
}

