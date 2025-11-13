<?php

namespace App\Repositories\Eloquent;

use App\Models\Doctor;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Arr;

class DoctorRepository implements DoctorRepositoryInterface
{
    public function findById(int $id, array $relations = []): ?Doctor
    {
        return Doctor::query()
            ->with($relations)
            ->find($id);
    }

    public function findByUserId(int $userId, array $relations = []): ?Doctor
    {
        return Doctor::query()
            ->with($relations)
            ->where('user_id', $userId)
            ->first();
    }

    public function update(Doctor $doctor, array $attributes): Doctor
    {
        $doctor->fill($attributes);
        $doctor->save();

        return $doctor;
    }

    public function paginateForAdmin(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Doctor::query()->with(['user', 'diagnosticCenter']);

        if ($search = Arr::get($filters, 'search')) {
            $query->whereHas('user', function ($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if ($specialization = Arr::get($filters, 'specialization')) {
            $query->where('specialization', 'like', '%' . $specialization . '%');
        }

        if ($diagnosticCenterId = Arr::get($filters, 'diagnostic_center_id')) {
            $query->where('diagnostic_center_id', $diagnosticCenterId);
        }

        if (Arr::get($filters, 'is_active') !== null) {
            $query->where('is_active', (bool) Arr::get($filters, 'is_active'));
        }

        return $query->orderByDesc('created_at')->paginate($perPage)->withQueryString();
    }

    public function create(array $attributes): Doctor
    {
        return Doctor::query()->create($attributes);
    }

    public function delete(Doctor $doctor): void
    {
        $doctor->delete();
    }

    public function listByDiagnosticCenter(int $diagnosticCenterId): EloquentCollection
    {
        return Doctor::query()
            ->where('diagnostic_center_id', $diagnosticCenterId)
            ->orderBy('user_id')
            ->get();
    }
}

