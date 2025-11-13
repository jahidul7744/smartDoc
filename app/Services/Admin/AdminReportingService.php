<?php

namespace App\Services\Admin;

use App\Models\Appointment;
use App\Models\DiagnosticCenter;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class AdminReportingService
{
    public function overview(): array
    {
        return [
            'total_patients' => Patient::query()->count(),
            'total_doctors' => Doctor::query()->count(),
            'total_centers' => DiagnosticCenter::query()->count(),
            'upcoming_appointments' => Appointment::query()
                ->where('scheduled_at', '>=', now())
                ->count(),
        ];
    }

    public function appointmentTrends(int $days = 14): Collection
    {
        $endDate = CarbonImmutable::today();
        $startDate = $endDate->subDays($days - 1);

        $data = Appointment::query()
            ->selectRaw('DATE(scheduled_at) as date, COUNT(*) as total')
            ->whereBetween('scheduled_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        return collect()
            ->times($days, function ($index) use ($startDate, $data) {
                $date = $startDate->addDays($index)->toDateString();

                return [
                    'date' => $date,
                    'total' => (int) ($data[$date] ?? 0),
                ];
            });
    }

    public function specializationBreakdown(int $limit = 10): Collection
    {
        return Doctor::query()
            ->selectRaw('specialization, COUNT(*) as total')
            ->groupBy('specialization')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    public function topDiagnosticCenters(int $limit = 5): Collection
    {
        return Appointment::query()
            ->selectRaw('diagnostic_center_id, COUNT(*) as total')
            ->with('diagnosticCenter:id,name,city')
            ->groupBy('diagnostic_center_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }
}

