<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Services\Doctor\DoctorDashboardService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DoctorDashboardService $dashboardService
    ) {
    }

    public function __invoke(): View
    {
        $doctor = $this->dashboardService->getDoctorProfileForUser((int) Auth::id());
        $summary = $this->dashboardService->getDashboardSummary($doctor->id);
        $upcomingAppointments = $this->dashboardService->getUpcomingAppointments($doctor->id, 5);
        $recentPrescriptions = $this->dashboardService->getPrescriptionHistory($doctor->id, 5);

        return view('doctor.dashboard', [
            'doctor' => $doctor,
            'summary' => $summary,
            'upcomingAppointments' => $upcomingAppointments,
            'recentPrescriptions' => $recentPrescriptions,
        ]);
    }
}

