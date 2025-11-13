<?php

namespace Database\Seeders;

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
    }
}
