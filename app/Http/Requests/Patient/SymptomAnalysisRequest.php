<?php

namespace App\Http\Requests\Patient;

use App\DataTransferObjects\Patient\SymptomAnalysisData;
use App\DataTransferObjects\Patient\SymptomEntryData;
use Illuminate\Foundation\Http\FormRequest;

class SymptomAnalysisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'patient';
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'symptoms' => ['required', 'array', 'min:1', 'max:10'],
            'symptoms.*.name' => ['required', 'string', 'max:150'],
            'symptoms.*.severity' => ['required', 'integer', 'min:1', 'max:10'],
            'symptoms.*.duration' => ['required', 'integer', 'min:1', 'max:365'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'symptoms.*.name' => 'symptom name',
            'symptoms.*.severity' => 'symptom severity',
            'symptoms.*.duration' => 'symptom duration (days)',
        ];
    }

    public function messages(): array
    {
        return [
            'symptoms.min' => __('Add at least :min symptom to continue.', ['min' => 1]),
            'symptoms.max' => __('You can track up to :max symptoms per analysis.', ['max' => 10]),
        ];
    }

    public function toDto(int $diagnosticCenterId, int $patientId): SymptomAnalysisData
    {
        $symptomEntries = collect($this->input('symptoms', []))
            ->map(fn (array $symptom) => new SymptomEntryData(
                trim($symptom['name']),
                (int) $symptom['severity'],
                (int) $symptom['duration']
            ))
            ->values()
            ->all();

        return new SymptomAnalysisData(
            patientId: $patientId,
            diagnosticCenterId: $diagnosticCenterId,
            symptoms: $symptomEntries,
            notes: $this->string('notes')->nullable()
        );
    }
}

