<?php

namespace Database\Seeders;

use App\Models\DiagnosticCenter;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $patientUser = User::factory()->create([
            'name' => 'Test Patient',
            'email' => 'patient@example.com',
            'phone' => '01700000000',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'address' => '123 Demo Street, Demo City',
            'role' => 'patient',
        ]);

        $patientUser->patient()->create([
            'medical_history' => 'No significant history.',
            'blood_group' => 'O+',
            'allergies' => 'None',
            'emergency_contact_name' => 'Demo Contact',
            'emergency_contact_phone' => '01700000001',
            'profile_completed_at' => now(),
        ]);

        DiagnosticCenter::factory()->count(15)->create();

        $diagnosticCenter = DiagnosticCenter::factory()->create([
            'name' => 'SmartDoc Health Hub',
            'slug' => 'smartdoc-health-hub',
            'city' => 'Dhaka',
            'specializations' => ['General Medicine', 'Cardiology'],
            'has_available_slots' => true,
        ]);

        $doctorUser = User::factory()->create([
            'name' => 'Dr. Ahsan Rahman',
            'email' => 'doctor@example.com',
            'phone' => '01700000002',
            'date_of_birth' => '1980-05-12',
            'gender' => 'male',
            'address' => '456 Clinic Road, Dhaka',
            'role' => 'doctor',
        ]);

        $doctorUser->doctor()->create([
            'diagnostic_center_id' => $diagnosticCenter->id,
            'specialization' => 'General Medicine',
            'qualifications' => 'MBBS, FCPS (Medicine)',
            'experience_years' => 15,
            'consultation_fee' => 1200,
            'registration_number' => 'BMDC-12345',
            'bio' => 'Experienced physician providing compassionate primary care.',
        ]);

        User::factory()->create([
            'name' => 'System Administrator',
            'email' => 'admin@example.com',
            'phone' => '01700000003',
            'date_of_birth' => '1985-02-02',
            'gender' => 'other',
            'address' => '789 Admin Avenue, Dhaka',
            'role' => 'admin',
        ]);
    }
}
