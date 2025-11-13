<?php

namespace App\Http\Requests\Doctor;

use App\DataTransferObjects\Doctor\PrescriptionData;
use Illuminate\Foundation\Http\FormRequest;

class PrescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'doctor';
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'general_instructions' => ['nullable', 'string', 'max:2000'],
            'follow_up_at' => ['nullable', 'date', 'after:now'],
            'medicines' => ['required', 'array', 'min:1'],
            'medicines.*.medicine_name' => ['required', 'string', 'max:255'],
            'medicines.*.dosage' => ['nullable', 'string', 'max:255'],
            'medicines.*.frequency' => ['nullable', 'string', 'max:255'],
            'medicines.*.duration' => ['nullable', 'string', 'max:255'],
            'medicines.*.instructions' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function toDto(): PrescriptionData
    {
        return PrescriptionData::fromArray($this->validated());
    }
}

