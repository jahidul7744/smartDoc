<?php

namespace App\Services\Patient;

use App\Models\Patient;
use App\Models\User;

class PatientProfileService
{
    public function updatePatientProfile(User $user, array $payload): Patient
    {
        $patient = $user->patient ?? Patient::create(['user_id' => $user->id]);

        $patient->fill([
            'medical_history' => $payload['medical_history'] ?? null,
            'blood_group' => $payload['blood_group'] ?? null,
            'allergies' => $payload['allergies'] ?? null,
            'emergency_contact_name' => $payload['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $payload['emergency_contact_phone'] ?? null,
        ]);

        $patient->profile_completed_at = $patient->hasCompletedProfile() ? now() : null;

        $patient->save();

        return $patient;
    }
}

