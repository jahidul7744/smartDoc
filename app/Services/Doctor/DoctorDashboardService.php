<?php

namespace App\Services\Doctor;

use App\Exceptions\DomainException;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Repositories\Contracts\AppointmentRepositoryInterface;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use App\Repositories\Contracts\PrescriptionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DoctorDashboardService
{
    public function __construct(
        private readonly DoctorRepositoryInterface $doctorRepository,
        private readonly AppointmentRepositoryInterface $appointmentRepository,
        private readonly PrescriptionRepositoryInterface $prescriptionRepository
    ) {
    }

    public function getDoctorProfileForUser(int $userId): Doctor
    {
        $doctor = $this->doctorRepository->findByUserId(
            $userId,
            ['user', 'diagnosticCenter']
        );

        if ($doctor === null) {
            throw new DomainException('Doctor profile not found for authenticated user.');
        }

        return $doctor;
    }

    /**
     * @return array<string, int>
     */
    public function getDashboardSummary(int $doctorId): array
    {
        return [
            'upcoming_count' => $this->appointmentRepository->countUpcomingForDoctor($doctorId),
            'today_count' => $this->appointmentRepository->countTodayForDoctor($doctorId),
            'follow_up_count' => $this->appointmentRepository->countFollowUpsForDoctor($doctorId),
        ];
    }

    public function getUpcomingAppointments(int $doctorId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->appointmentRepository->paginateUpcomingForDoctor($doctorId, $perPage);
    }

    public function getPastAppointments(int $doctorId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->appointmentRepository->paginatePastForDoctor($doctorId, $perPage);
    }

    public function getAppointmentDetails(int $doctorId, int $appointmentId): Appointment
    {
        $appointment = $this->appointmentRepository->findForDoctor(
            $appointmentId,
            $doctorId,
            [
                'patient.user',
                'diagnosticCenter',
                'diagnosis',
                'prescription.medicines',
            ]
        );

        if ($appointment === null) {
            throw new DomainException('Appointment not found for the doctor.');
        }

        return $appointment;
    }

    public function getPrescriptionHistory(int $doctorId, int $limit = 10): Collection
    {
        return $this->prescriptionRepository->latestForDoctor(
            $doctorId,
            $limit,
            ['patient.user', 'diagnosis.appointment']
        );
    }
}

