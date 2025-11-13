<?php

namespace App\Repositories\Eloquent;

use App\Models\DiagnosticCenter;
use App\Repositories\Contracts\DiagnosticCenterRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class DiagnosticCenterRepository implements DiagnosticCenterRepositoryInterface
{
    public function paginateActive(array $filters, array $sort, int $perPage = 12): LengthAwarePaginator
    {
        $query = DiagnosticCenter::query()->active();

        $query = $this->applyFilters($query, $filters);
        $query = $this->applySorting($query, $sort, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    public function findActive(int $id): ?DiagnosticCenter
    {
        return DiagnosticCenter::query()
            ->active()
            ->find($id);
    }

    public function paginateForAdmin(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = DiagnosticCenter::query();
        $query = $this->applyFilters($query, $filters);

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function allForSelect(): Collection
    {
        return DiagnosticCenter::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function find(int $id, array $relations = []): ?DiagnosticCenter
    {
        return DiagnosticCenter::query()
            ->with($relations)
            ->find($id);
    }

    public function create(array $attributes): DiagnosticCenter
    {
        return DiagnosticCenter::query()->create($attributes);
    }

    public function update(DiagnosticCenter $diagnosticCenter, array $attributes): DiagnosticCenter
    {
        $diagnosticCenter->fill($attributes);
        $diagnosticCenter->save();

        return $diagnosticCenter;
    }

    public function delete(DiagnosticCenter $diagnosticCenter): void
    {
        $diagnosticCenter->delete();
    }

    public function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        return DiagnosticCenter::query()
            ->when($ignoreId, fn (Builder $builder) => $builder->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists();
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if ($search = Arr::get($filters, 'search')) {
            $query->where(function (Builder $builder) use ($search) {
                $builder
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%')
                    ->orWhere('state', 'like', '%' . $search . '%');
            });
        }

        if ($city = Arr::get($filters, 'city')) {
            $query->where('city', 'like', '%' . $city . '%');
        }

        if ($specialization = Arr::get($filters, 'specialization')) {
            $query->whereJsonContains('specializations', $specialization);
        }

        if (Arr::get($filters, 'has_available_slots') !== null) {
            $query->where('has_available_slots', (bool) Arr::get($filters, 'has_available_slots'));
        }

        if (($latitude = Arr::get($filters, 'latitude')) !== null && ($longitude = Arr::get($filters, 'longitude')) !== null) {
            $query->select('diagnostic_centers.*')
                ->selectRaw(
                    'ROUND(6371 * acos(
                        cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))
                    ), 2) as distance_km',
                    [$latitude, $longitude, $latitude]
                );

            if ($maxDistance = Arr::get($filters, 'max_distance')) {
                $query->having('distance_km', '<=', $maxDistance);
            }
        }

        return $query;
    }

    protected function applySorting(Builder $query, array $sort, array $filters): Builder
    {
        $sortBy = Arr::get($sort, 'by', 'name');
        $direction = Arr::get($sort, 'direction', 'asc');

        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        return match ($sortBy) {
            'rating' => $query->orderByDesc('rating')->orderByDesc('rating_count'),
            'distance' => $this->canSortByDistance($filters)
                ? $query->orderBy('distance_km')
                : $query->orderBy('name'),
            'availability' => $query->orderByDesc('has_available_slots'),
            default => $query->orderBy('name', $direction),
        };
    }

    protected function canSortByDistance(array $filters): bool
    {
        return Arr::get($filters, 'latitude') !== null
            && Arr::get($filters, 'longitude') !== null;
    }
}

