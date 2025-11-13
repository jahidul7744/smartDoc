<?php

namespace App\DataTransferObjects\Admin;

final class DiagnosticCenterData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $slug,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly string $addressLine1,
        public readonly ?string $addressLine2,
        public readonly string $city,
        public readonly ?string $state,
        public readonly ?string $postalCode,
        public readonly string $country,
        public readonly ?float $latitude,
        public readonly ?float $longitude,
        public readonly array $specializations,
        public readonly bool $isActive,
        public readonly bool $hasAvailableSlots
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            name: $payload['name'],
            slug: $payload['slug'] ?? null,
            email: $payload['email'] ?? null,
            phone: $payload['phone'] ?? null,
            addressLine1: $payload['address_line1'],
            addressLine2: $payload['address_line2'] ?? null,
            city: $payload['city'],
            state: $payload['state'] ?? null,
            postalCode: $payload['postal_code'] ?? null,
            country: $payload['country'] ?? 'Bangladesh',
            latitude: isset($payload['latitude']) ? (float) $payload['latitude'] : null,
            longitude: isset($payload['longitude']) ? (float) $payload['longitude'] : null,
            specializations: array_values(array_filter($payload['specializations'] ?? [])),
            isActive: (bool) ($payload['is_active'] ?? true),
            hasAvailableSlots: (bool) ($payload['has_available_slots'] ?? true),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'phone' => $this->phone,
            'address_line1' => $this->addressLine1,
            'address_line2' => $this->addressLine2,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postalCode,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'specializations' => $this->specializations,
            'is_active' => $this->isActive,
            'has_available_slots' => $this->hasAvailableSlots,
        ];
    }
}

