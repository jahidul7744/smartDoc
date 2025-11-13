<?php

namespace App\Services\Admin;

use App\Exceptions\DomainException;
use App\Models\Appointment;
use App\Repositories\Contracts\AppointmentRepositoryInterface;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AdminAppointmentService
{
    public function __construct(
        private readonly AppointmentRepositoryInterface $appointmentRepository,
        private readonly DoctorRepositoryInterface $doctorRepository
    ) {
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        if (isset($filters['date_range']) && is_array($filters['date_range'])) {
            $filters['date_range'] = $this->normalizeDateRange($filters['date_range']);
        }

        return $this->appointmentRepository->paginateForAdmin($filters, $perPage);
    }

    public function find(int $appointmentId): Appointment
    {
        $appointment = $this->appointmentRepository->find($appointmentId, [
            'patient.user',
            'doctor.user',
            'diagnosticCenter',
            'diagnosis',
            'prescription',
        ]);

        if ($appointment === null) {
            throw new DomainException('Appointment not found.');
        }

        return $appointment;
    }

    public function updateStatus(int $appointmentId, string $status): Appointment
    {
        $appointment = $this->find($appointmentId);

        $validStatuses = ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'];

        if (! in_array($status, $validStatuses, true)) {
            throw new DomainException('Invalid appointment status.');
        }

        $updated = $this->appointmentRepository->updateStatus($appointment, $status);

        return $updated->load(['patient.user', 'doctor.user', 'diagnosticCenter']);
    }

    public function reassignDoctor(int $appointmentId, int $doctorId): Appointment
    {
        $appointment = $this->find($appointmentId);

        $doctor = $this->doctorRepository->findById($doctorId);

        if ($doctor === null) {
            throw new DomainException('Doctor not found.');
        }

        $updated = $this->appointmentRepository->update($appointment, [
            'doctor_id' => $doctorId,
            'diagnostic_center_id' => $doctor->diagnostic_center_id,
        ]);

        return $updated->load(['patient.user', 'doctor.user', 'diagnosticCenter']);
    }

    public function reschedule(int $appointmentId, Carbon $scheduledAt): Appointment
    {
        $appointment = $this->find($appointmentId);

        $updated = $this->appointmentRepository->update($appointment, [
            'scheduled_at' => $scheduledAt,
        ]);

        return $updated->load(['patient.user', 'doctor.user', 'diagnosticCenter']);
    }

    public function aggregateByStatus(): Collection
    {
        return Appointment::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
    }

    /**
     * @param  array<int, string|null>  $dateRange
     * @return array<int, string|null>
     */
    protected function normalizeDateRange(array $dateRange): array
    {
        [$start, $end] = $dateRange + [null, null];

        $startDate = $start ? Carbon::parse($start)->startOfDay()->toDateString() : null;
        $endDate = $end ? Carbon::parse($end)->endOfDay()->toDateString() : null;

        return [$startDate, $endDate];
    }
}

