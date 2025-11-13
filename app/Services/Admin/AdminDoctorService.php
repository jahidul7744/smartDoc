<?php

namespace App\Services\Admin;

use App\DataTransferObjects\Admin\DoctorProfileData;
use App\Exceptions\DomainException;
use App\Models\Doctor;
use App\Models\User;
use App\Repositories\Contracts\DiagnosticCenterRepositoryInterface;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;

class AdminDoctorService
{
    public function __construct(
        private readonly DoctorRepositoryInterface $doctorRepository,
        private readonly DiagnosticCenterRepositoryInterface $diagnosticCenterRepository
    ) {
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->doctorRepository->paginateForAdmin($filters, $perPage);
    }

    public function find(int $doctorId): Doctor
    {
        $doctor = $this->doctorRepository->findById($doctorId, ['user', 'diagnosticCenter']);

        if ($doctor === null) {
            throw new DomainException('Doctor profile not found.');
        }

        return $doctor;
    }

    public function create(DoctorProfileData $data): Doctor
    {
        $this->assertDiagnosticCenterExists($data->diagnosticCenterId);

        return DB::transaction(function () use ($data) {
            $userAttributes = $data->userAttributes();

            if (! array_key_exists('password', $userAttributes)) {
                throw new DomainException('Password is required when creating a doctor profile.');
            }

            /** @var User $user */
            $user = User::query()->create($userAttributes);

            $doctor = $this->doctorRepository->create(
                $data->doctorAttributes($user->id)
            );

            return $doctor->load(['user', 'diagnosticCenter']);
        });
    }

    public function update(int $doctorId, DoctorProfileData $data): Doctor
    {
        $this->assertDiagnosticCenterExists($data->diagnosticCenterId);

        return DB::transaction(function () use ($doctorId, $data) {
            $doctor = $this->find($doctorId);
            $user = $doctor->user;

            $user->fill($data->userAttributes());

            if ($data->password) {
                $user->password = $data->password;
            }

            $user->save();

            $updatedDoctor = $this->doctorRepository->update($doctor, $data->doctorAttributes($user->id));

            return $updatedDoctor->load(['user', 'diagnosticCenter']);
        });
    }

    public function delete(int $doctorId): void
    {
        DB::transaction(function () use ($doctorId) {
            $doctor = $this->find($doctorId);
            $user = $doctor->user;

            $this->doctorRepository->delete($doctor);

            $user?->delete();
        });
    }

    public function assign(int $doctorId, int $diagnosticCenterId): Doctor
    {
        $doctor = $this->find($doctorId);

        $this->assertDiagnosticCenterExists($diagnosticCenterId);

        $updatedDoctor = $this->doctorRepository->update($doctor, [
            'diagnostic_center_id' => $diagnosticCenterId,
        ]);

        return $updatedDoctor->load(['user', 'diagnosticCenter']);
    }

    public function listByDiagnosticCenter(int $diagnosticCenterId): EloquentCollection
    {
        return $this->doctorRepository->listByDiagnosticCenter($diagnosticCenterId)->load(['user']);
    }

    protected function assertDiagnosticCenterExists(int $diagnosticCenterId): void
    {
        if ($this->diagnosticCenterRepository->find($diagnosticCenterId) === null) {
            throw new DomainException('Diagnostic center not found.');
        }
    }
}

