<?php

namespace App\Services\Patient;

use App\DataTransferObjects\Patient\PredictionOptionData;
use App\DataTransferObjects\Patient\SymptomAnalysisData;
use App\DataTransferObjects\Patient\SymptomAnalysisResult;
use App\Exceptions\DomainException;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SymptomAnalysisService
{
    public function __construct(
        private readonly DoctorRepositoryInterface $doctorRepository
    ) {
    }

    public function symptomSuggestions(): array
    {
        return config('medical.symptom_suggestions', []);
    }

    /**
     * @throws DomainException
     */
    public function analyze(SymptomAnalysisData $input): SymptomAnalysisResult
    {
        $config = $this->mlConfig();

        try {
            $response = Http::baseUrl($config['base_url'])
                ->timeout($config['timeout'])
                ->retry(
                    $config['retry_attempts'],
                    $config['retry_delay']
                )
                ->withHeaders($this->buildHeaders($config))
                ->post($config['predict_endpoint'], $this->buildRequestPayload($input));
        } catch (ConnectionException $exception) {
            throw new DomainException(
                __('We could not reach the AI predictor right now. Please try again in a moment.'),
                previous: $exception
            );
        }

        if (! $response->successful()) {
            throw new DomainException(__('The AI predictor returned an unexpected response. Please try again.'));
        }

        $responseData = $response->json();
        if (! is_array($responseData)) {
            throw new DomainException(__('The AI predictor returned invalid data. Please try again.'));
        }

        $primary = $this->extractPrimaryPrediction($responseData);
        $alternatives = $this->buildAlternativePredictions($responseData, $primary);
        $recommendedSpecializations = $this->determineSpecializations($primary, $alternatives);
        $recommendedDoctors = $this->fetchDoctorRecommendations(
            $input->diagnosticCenterId,
            $recommendedSpecializations
        );

        return new SymptomAnalysisResult(
            primaryPrediction: $primary,
            alternatives: $alternatives,
            recommendedSpecializations: $recommendedSpecializations,
            recommendedDoctors: $recommendedDoctors
        );
    }

    private function mlConfig(): array
    {
        return config('services.ml', []);
    }

    private function buildHeaders(array $config): array
    {
        if (empty($config['api_key'])) {
            return [];
        }

        return [
            'Authorization' => 'Bearer ' . $config['api_key'],
            'Accept' => 'application/json',
        ];
    }

    private function buildRequestPayload(SymptomAnalysisData $input): array
    {
        return [
            'patient_id' => $input->patientId,
            'diagnostic_center_id' => $input->diagnosticCenterId,
            'symptoms' => collect($input->symptoms)->map(function ($symptom) {
                return [
                    'name' => $symptom->name,
                    'severity' => $symptom->severity,
                    'duration_days' => $symptom->durationDays,
                ];
            })->all(),
            'notes' => $input->notes,
        ];
    }

    private function extractPrimaryPrediction(array $response): PredictionOptionData
    {
        $label = (string) (
            Arr::get($response, 'primary_prediction.illness') ??
            Arr::get($response, 'prediction.illness') ??
            Arr::get($response, 'predicted_illness') ??
            __('Unknown condition')
        );

        $confidence = (float) (
            Arr::get($response, 'primary_prediction.confidence') ??
            Arr::get($response, 'prediction.confidence') ??
            Arr::get($response, 'confidence', 0)
        );

        return new PredictionOptionData(
            label: $label,
            confidence: round($confidence, 2)
        );
    }

    /**
     * @return list<PredictionOptionData>
     */
    private function buildAlternativePredictions(array $response, PredictionOptionData $primary): array
    {
        $alternatives = collect(Arr::get($response, 'alternatives', Arr::get($response, 'top_predictions', [])))
            ->map(function ($alternative) {
                if (! is_array($alternative)) {
                    return null;
                }

                $illness = (string) (Arr::get($alternative, 'illness') ?? Arr::get($alternative, 'name'));
                if ($illness === '') {
                    return null;
                }

                return new PredictionOptionData(
                    label: $illness,
                    confidence: round((float) Arr::get($alternative, 'confidence', 0), 2)
                );
            })
            ->filter()
            ->values();

        return $alternatives
            ->reject(fn (PredictionOptionData $option) => Str::lower($option->label) === Str::lower($primary->label))
            ->take(2)
            ->values()
            ->all();
    }

    /**
     * @param list<PredictionOptionData> $alternatives
     * @return list<string>
     */
    private function determineSpecializations(PredictionOptionData $primary, array $alternatives): array
    {
        $mapping = config('medical.illness_specializations', []);
        $matched = $this->matchSpecializations($primary->label, $mapping);

        if (empty($matched)) {
            foreach ($alternatives as $alternative) {
                $matched = $this->matchSpecializations($alternative->label, $mapping);
                if (! empty($matched)) {
                    break;
                }
            }
        }

        if (empty($matched)) {
            $matched = ['General Physician'];
        }

        return array_values(array_unique($matched));
    }

    private function matchSpecializations(string $illness, array $mapping): array
    {
        $illnessKey = Str::lower($illness);

        $matched = [];
        foreach ($mapping as $keyword => $specializations) {
            if (Str::contains($illnessKey, Str::lower($keyword))) {
                $matched = array_merge($matched, $specializations);
            }
        }

        return $matched;
    }

    /**
     * @param list<string> $specializations
     */
    private function fetchDoctorRecommendations(int $diagnosticCenterId, array $specializations): Collection
    {
        $limit = (int) config('medical.doctor_recommendations.limit', 3);

        return $this->doctorRepository
            ->listByCenterAndSpecializations($diagnosticCenterId, $specializations, $limit)
            ->map(function ($doctor) {
                return [
                    'name' => $doctor->user?->name ?? __('Doctor'),
                    'specialization' => $doctor->specialization,
                    'experience_years' => $doctor->experience_years,
                    'rating' => $doctor->rating,
                    'rating_count' => $doctor->rating_count,
                    'consultation_fee' => $doctor->consultation_fee,
                    'qualifications' => $doctor->qualifications,
                ];
            });
    }
}

