<?php

namespace App\Services\Doctor;

use App\DataTransferObjects\Doctor\DiagnosisData;
use App\DataTransferObjects\Doctor\PrescriptionData;
use App\Exceptions\DomainException;
use App\Models\Diagnosis;
use App\Models\Prescription;
use App\Repositories\Contracts\AppointmentRepositoryInterface;
use App\Repositories\Contracts\DiagnosisRepositoryInterface;
use App\Repositories\Contracts\PrescriptionRepositoryInterface;

class DoctorConsultationService
{
    public function __construct(
        private readonly AppointmentRepositoryInterface $appointmentRepository,
        private readonly DiagnosisRepositoryInterface $diagnosisRepository,
        private readonly PrescriptionRepositoryInterface $prescriptionRepository
    ) {
    }

    public function recordDiagnosis(int $doctorId, int $appointmentId, DiagnosisData $diagnosisData): Diagnosis
    {
        $appointment = $this->appointmentRepository->findForDoctor(
            $appointmentId,
            $doctorId,
            ['patient']
        );

        if ($appointment === null) {
            throw new DomainException('Appointment not found for the doctor.');
        }

        $existingDiagnosis = $this->diagnosisRepository->findByAppointmentId($appointmentId);

        $attributes = $diagnosisData->toPersistenceArray(
            appointmentId: $appointmentId,
            doctorId: $doctorId,
            patientId: $appointment->patient_id
        );

        $diagnosis = $existingDiagnosis === null
            ? $this->diagnosisRepository->create($attributes)
            : $this->diagnosisRepository->update($existingDiagnosis, $attributes);

        $this->appointmentRepository->updateStatus($appointment, 'completed');

        if ($diagnosisData->followUpRequired && $diagnosisData->followUpAt !== null) {
            $this->appointmentRepository->createFollowUpFromAppointment($appointment, [
                'scheduled_at' => $diagnosisData->followUpAt,
                'status' => 'pending',
            ]);
        }

        return $diagnosis->loadMissing(['appointment', 'patient.user']);
    }

    public function issuePrescription(int $doctorId, int $diagnosisId, PrescriptionData $prescriptionData): Prescription
    {
        $diagnosis = $this->diagnosisRepository->findById(
            $diagnosisId,
            ['appointment']
        );

        if ($diagnosis === null || $diagnosis->doctor_id !== $doctorId) {
            throw new DomainException('Diagnosis not found for the doctor.');
        }

        $medicines = $prescriptionData->medicinesToPersistenceArray();

        if (count($medicines) === 0) {
            throw new DomainException('A prescription must include at least one medicine.');
        }

        $attributes = $prescriptionData->toPersistenceArray(
            diagnosisId: $diagnosisId,
            doctorId: $doctorId,
            patientId: $diagnosis->patient_id,
            diagnosticCenterId: $diagnosis->appointment->diagnostic_center_id
        );

        $existing = $this->prescriptionRepository->findByDiagnosisId($diagnosisId);

        $prescription = $existing === null
            ? $this->prescriptionRepository->createWithMedicines($attributes, $medicines)
            : $this->prescriptionRepository->updateWithMedicines($existing, $attributes, $medicines);

        return $prescription->loadMissing(['diagnosis', 'medicines', 'patient.user']);
    }
}

