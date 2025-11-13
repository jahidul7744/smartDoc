<?php

namespace App\Http\Requests\Doctor;

use App\DataTransferObjects\Doctor\DiagnosisData;
use Illuminate\Foundation\Http\FormRequest;

class DiagnosisRequest extends FormRequest
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
            'final_diagnosis' => ['required', 'string', 'max:255'],
            'clinical_notes' => ['nullable', 'string', 'max:2000'],
            'recommended_tests' => ['nullable', 'array'],
            'recommended_tests.*' => ['nullable', 'string', 'max:255'],
            'follow_up_required' => ['sometimes', 'boolean'],
            'follow_up_at' => ['nullable', 'date', 'after:now'],
        ];
    }

    public function toDto(): DiagnosisData
    {
        return DiagnosisData::fromArray($this->validated());
    }
}

