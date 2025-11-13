<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'medical_history' => ['required', 'string'],
            'blood_group' => ['required', 'string', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'allergies' => ['required', 'string'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'regex:/^01[0-9]{9}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'emergency_contact_phone.regex' => 'Emergency contact number must follow the local format (e.g., 01XXXXXXXXX).',
        ];
    }
}

