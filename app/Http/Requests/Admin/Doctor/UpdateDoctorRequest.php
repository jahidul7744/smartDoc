<?php

namespace App\Http\Requests\Admin\Doctor;

use App\DataTransferObjects\Admin\DoctorProfileData;
use App\Models\Doctor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
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
        /** @var Doctor|null $doctor */
        $doctor = $this->route('doctor');

        $userId = $doctor?->user_id ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($userId)],
            'date_of_birth' => ['required', 'date', 'before:-18 years'],
            'gender' => ['required', 'in:male,female,other'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
            'diagnostic_center_id' => ['required', 'integer', 'exists:diagnostic_centers,id'],
            'specialization' => ['required', 'string', 'max:120'],
            'qualifications' => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'between:0,60'],
            'consultation_fee' => ['nullable', 'numeric', 'between:0,999999.99'],
            'registration_number' => ['nullable', 'string', 'max:120'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function toDto(): DoctorProfileData
    {
        return DoctorProfileData::fromArray($this->validated());
    }
}

