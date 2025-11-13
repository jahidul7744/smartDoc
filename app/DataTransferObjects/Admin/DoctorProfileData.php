<?php

namespace App\DataTransferObjects\Admin;

use Carbon\CarbonImmutable;

final class DoctorProfileData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $phone,
        public readonly CarbonImmutable $dateOfBirth,
        public readonly string $gender,
        public readonly string $address,
        public readonly ?string $password,
        public readonly int $diagnosticCenterId,
        public readonly string $specialization,
        public readonly ?string $qualifications,
        public readonly ?int $experienceYears,
        public readonly ?float $consultationFee,
        public readonly ?string $registrationNumber,
        public readonly ?string $bio,
        public readonly bool $isActive
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            name: $payload['name'],
            email: $payload['email'],
            phone: $payload['phone'],
            dateOfBirth: CarbonImmutable::parse($payload['date_of_birth']),
            gender: $payload['gender'],
            address: $payload['address'],
            password: $payload['password'] ?? null,
            diagnosticCenterId: (int) $payload['diagnostic_center_id'],
            specialization: $payload['specialization'],
            qualifications: $payload['qualifications'] ?? null,
            experienceYears: isset($payload['experience_years']) ? (int) $payload['experience_years'] : null,
            consultationFee: isset($payload['consultation_fee']) ? (float) $payload['consultation_fee'] : null,
            registrationNumber: $payload['registration_number'] ?? null,
            bio: $payload['bio'] ?? null,
            isActive: (bool) ($payload['is_active'] ?? true)
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function userAttributes(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->dateOfBirth->format('Y-m-d'),
            'gender' => $this->gender,
            'address' => $this->address,
            'role' => 'doctor',
        ] + ($this->password ? ['password' => $this->password] : []);
    }

    /**
     * @return array<string, mixed>
     */
    public function doctorAttributes(int $userId): array
    {
        return [
            'user_id' => $userId,
            'diagnostic_center_id' => $this->diagnosticCenterId,
            'specialization' => $this->specialization,
            'qualifications' => $this->qualifications,
            'experience_years' => $this->experienceYears,
            'consultation_fee' => $this->consultationFee,
            'registration_number' => $this->registrationNumber,
            'bio' => $this->bio,
            'is_active' => $this->isActive,
        ];
    }
}

