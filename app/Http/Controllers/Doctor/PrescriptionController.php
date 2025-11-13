<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\PrescriptionRequest;
use App\Services\Doctor\DoctorConsultationService;
use App\Services\Doctor\DoctorDashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    public function __construct(
        private readonly DoctorDashboardService $dashboardService,
        private readonly DoctorConsultationService $consultationService
    ) {
    }

    public function store(PrescriptionRequest $request, int $diagnosis): RedirectResponse
    {
        $doctor = $this->dashboardService->getDoctorProfileForUser((int) Auth::id());
        $prescriptionData = $request->toDto();

        $prescription = $this->consultationService->issuePrescription(
            doctorId: $doctor->id,
            diagnosisId: $diagnosis,
            prescriptionData: $prescriptionData
        );

        return redirect()
            ->route('doctor.appointments.show', $prescription->diagnosis->appointment_id)
            ->with('status', 'Prescription issued successfully.');
    }
}

