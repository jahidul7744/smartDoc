<?php

namespace App\Repositories\Eloquent;

use App\Models\Appointment;
use App\Repositories\Contracts\AppointmentRepositoryInterface;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function paginateUpcomingForDoctor(int $doctorId, int $perPage = 10): LengthAwarePaginator
    {
        return Appointment::query()
            ->with(['patient.user', 'diagnosticCenter'])
            ->where('doctor_id', $doctorId)
            ->upcoming()
            ->orderBy('scheduled_at')
            ->paginate($perPage);
    }

    public function paginatePastForDoctor(int $doctorId, int $perPage = 10): LengthAwarePaginator
    {
        return Appointment::query()
            ->with(['patient.user', 'diagnosis', 'prescription'])
            ->where('doctor_id', $doctorId)
            ->past()
            ->orderByDesc('scheduled_at')
            ->paginate($perPage);
    }

    public function findForDoctor(int $appointmentId, int $doctorId, array $relations = []): ?Appointment
    {
        return Appointment::query()
            ->with($relations)
            ->where('doctor_id', $doctorId)
            ->where('id', $appointmentId)
            ->first();
    }

    public function updateStatus(Appointment $appointment, string $status): Appointment
    {
        $appointment->status = $status;
        $appointment->save();

        return $appointment;
    }

    public function createFollowUpFromAppointment(Appointment $appointment, array $attributes): Appointment
    {
        return DB::transaction(function () use ($appointment, $attributes) {
            $followUp = Appointment::query()->create([
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'diagnostic_center_id' => $appointment->diagnostic_center_id,
                'scheduled_at' => $attributes['scheduled_at'],
                'status' => $attributes['status'] ?? 'pending',
                'chief_complaint' => $attributes['chief_complaint'] ?? $appointment->chief_complaint,
                'predicted_illness' => $appointment->predicted_illness,
                'symptoms' => $appointment->symptoms,
                'ai_confidence' => $appointment->ai_confidence,
                'follow_up_parent_id' => $appointment->id,
            ]);

            $appointment->followUps()->save($followUp);

            return $followUp;
        });
    }

    public function getDailySchedule(int $doctorId, \DateTimeInterface $date, array $relations = []): Collection
    {
        return Appointment::query()
            ->with($relations)
            ->where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', $date)
            ->orderBy('scheduled_at')
            ->get();
    }

    public function countUpcomingForDoctor(int $doctorId): int
    {
        return Appointment::query()
            ->where('doctor_id', $doctorId)
            ->upcoming()
            ->count();
    }

    public function countTodayForDoctor(int $doctorId): int
    {
        $today = CarbonImmutable::now();

        return Appointment::query()
            ->where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', $today)
            ->count();
    }

    public function countFollowUpsForDoctor(int $doctorId): int
    {
        return Appointment::query()
            ->where('doctor_id', $doctorId)
            ->whereNotNull('follow_up_parent_id')
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();
    }

    public function paginateForAdmin(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Appointment::query()->with([
            'patient.user',
            'doctor.user',
            'diagnosticCenter',
        ]);

        if ($status = Arr::get($filters, 'status')) {
            $query->where('status', $status);
        }

        if ($diagnosticCenterId = Arr::get($filters, 'diagnostic_center_id')) {
            $query->where('diagnostic_center_id', $diagnosticCenterId);
        }

        if ($doctorId = Arr::get($filters, 'doctor_id')) {
            $query->where('doctor_id', $doctorId);
        }

        if ($patientName = Arr::get($filters, 'patient_name')) {
            $query->whereHas('patient.user', function ($builder) use ($patientName) {
                $builder->where('name', 'like', '%' . $patientName . '%');
            });
        }

        if ($dateRange = Arr::get($filters, 'date_range')) {
            [$start, $end] = $dateRange;
            if ($start) {
                $query->whereDate('scheduled_at', '>=', $start);
            }
            if ($end) {
                $query->whereDate('scheduled_at', '<=', $end);
            }
        }

        return $query
            ->orderByDesc('scheduled_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(int $id, array $relations = []): ?Appointment
    {
        return Appointment::query()
            ->with($relations)
            ->find($id);
    }

    public function update(Appointment $appointment, array $attributes): Appointment
    {
        $appointment->fill($attributes);
        $appointment->save();

        return $appointment;
    }
}

