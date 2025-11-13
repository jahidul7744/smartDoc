<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Services\Doctor\DoctorDashboardService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function __construct(
        private readonly DoctorDashboardService $dashboardService
    ) {
    }

    public function index(Request $request): View
    {
        $doctor = $this->dashboardService->getDoctorProfileForUser((int) Auth::id());

        $appointments = $this->dashboardService->getUpcomingAppointments(
            $doctor->id,
            perPage: (int) $request->integer('per_page', 10)
        );

        return view('doctor.appointments.index', [
            'doctor' => $doctor,
            'appointments' => $appointments,
            'activeTab' => 'upcoming',
        ]);
    }

    public function past(Request $request): View
    {
        $doctor = $this->dashboardService->getDoctorProfileForUser((int) Auth::id());

        $appointments = $this->dashboardService->getPastAppointments(
            $doctor->id,
            perPage: (int) $request->integer('per_page', 10)
        );

        return view('doctor.appointments.index', [
            'doctor' => $doctor,
            'appointments' => $appointments,
            'activeTab' => 'past',
        ]);
    }

    public function show(int $appointment): View
    {
        $doctor = $this->dashboardService->getDoctorProfileForUser((int) Auth::id());
        $appointmentDetails = $this->dashboardService->getAppointmentDetails($doctor->id, $appointment);

        return view('doctor.appointments.show', [
            'doctor' => $doctor,
            'appointment' => $appointmentDetails,
        ]);
    }
}

