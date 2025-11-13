<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DiagnosisRequest;
use App\Services\Doctor\DoctorConsultationService;
use App\Services\Doctor\DoctorDashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DiagnosisController extends Controller
{
    public function __construct(
        private readonly DoctorDashboardService $dashboardService,
        private readonly DoctorConsultationService $consultationService
    ) {
    }

    public function store(DiagnosisRequest $request, int $appointment): RedirectResponse
    {
        $doctor = $this->dashboardService->getDoctorProfileForUser((int) Auth::id());
        $diagnosisData = $request->toDto();

        $this->consultationService->recordDiagnosis(
            doctorId: $doctor->id,
            appointmentId: $appointment,
            diagnosisData: $diagnosisData
        );

        return redirect()
            ->route('doctor.appointments.show', $appointment)
            ->with('status', 'Diagnosis saved successfully.');
    }
}

