<?php

namespace App\Services\Admin;

use App\DataTransferObjects\Admin\DiagnosticCenterData;
use App\Exceptions\DomainException;
use App\Models\DiagnosticCenter;
use App\Repositories\Contracts\DiagnosticCenterRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AdminDiagnosticCenterService
{
    public function __construct(
        private readonly DiagnosticCenterRepositoryInterface $diagnosticCenterRepository
    ) {
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->diagnosticCenterRepository->paginateForAdmin($filters, $perPage);
    }

    public function allForSelect(): Collection
    {
        return $this->diagnosticCenterRepository->allForSelect();
    }

    public function find(int $id): DiagnosticCenter
    {
        $center = $this->diagnosticCenterRepository->find($id);

        if ($center === null) {
            throw new DomainException('Diagnostic center not found.');
        }

        return $center;
    }

    public function create(DiagnosticCenterData $data): DiagnosticCenter
    {
        $attributes = $this->prepareAttributes($data);

        return $this->diagnosticCenterRepository->create($attributes);
    }

    public function update(int $id, DiagnosticCenterData $data): DiagnosticCenter
    {
        $center = $this->find($id);
        $attributes = $this->prepareAttributes($data, $center->id);

        return $this->diagnosticCenterRepository->update($center, $attributes);
    }

    public function delete(int $id): void
    {
        $center = $this->find($id);

        $this->diagnosticCenterRepository->delete($center);
    }

    /**
     * @return array<string, mixed>
     */
    protected function prepareAttributes(DiagnosticCenterData $data, ?int $ignoreId = null): array
    {
        $attributes = $data->toArray();

        $slug = $attributes['slug'] ?: Str::slug($attributes['name']);
        $uniqueSlug = $this->ensureUniqueSlug($slug, $ignoreId);
        $attributes['slug'] = $uniqueSlug;

        return $attributes;
    }

    protected function ensureUniqueSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = Str::slug($baseSlug);

        if ($slug === '') {
            throw new DomainException('Diagnostic center slug cannot be empty.');
        }

        $original = $slug;
        $counter = 1;

        while ($this->diagnosticCenterRepository->slugExists($slug, $ignoreId)) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

