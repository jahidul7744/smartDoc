<?php

namespace App\Http\Requests\Admin\DiagnosticCenter;

use App\DataTransferObjects\Admin\DiagnosticCenterData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDiagnosticCenterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $centerId = (int) $this->route('center');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', Rule::unique('diagnostic_centers', 'slug')->ignore($centerId)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('diagnostic_centers', 'email')->ignore($centerId)],
            'phone' => ['nullable', 'string', 'max:50', Rule::unique('diagnostic_centers', 'phone')->ignore($centerId)],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:30'],
            'country' => ['nullable', 'string', 'max:120'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'specializations' => ['nullable', 'array'],
            'specializations.*' => ['string', 'max:120'],
            'is_active' => ['sometimes', 'boolean'],
            'has_available_slots' => ['sometimes', 'boolean'],
        ];
    }

    public function toDto(): DiagnosticCenterData
    {
        return DiagnosticCenterData::fromArray($this->validated());
    }
}

