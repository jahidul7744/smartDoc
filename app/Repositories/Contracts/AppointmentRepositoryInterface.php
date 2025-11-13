<?php

namespace App\Repositories\Contracts;

use App\Models\Appointment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface AppointmentRepositoryInterface
{
    public function paginateUpcomingForDoctor(int $doctorId, int $perPage = 10): LengthAwarePaginator;

    public function paginatePastForDoctor(int $doctorId, int $perPage = 10): LengthAwarePaginator;

    public function findForDoctor(int $appointmentId, int $doctorId, array $relations = []): ?Appointment;

    public function updateStatus(Appointment $appointment, string $status): Appointment;

    public function createFollowUpFromAppointment(Appointment $appointment, array $attributes): Appointment;

    public function getDailySchedule(int $doctorId, \DateTimeInterface $date, array $relations = []): Collection;

    public function countUpcomingForDoctor(int $doctorId): int;

    public function countTodayForDoctor(int $doctorId): int;

    public function countFollowUpsForDoctor(int $doctorId): int;

    public function paginateForAdmin(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function find(int $id, array $relations = []): ?Appointment;

    public function update(Appointment $appointment, array $attributes): Appointment;
}

