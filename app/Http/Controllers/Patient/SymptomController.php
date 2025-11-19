<?php

namespace App\Http\Controllers\Patient;

use App\DataTransferObjects\Patient\SymptomAnalysisResult;
use App\Exceptions\DomainException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\SymptomAnalysisRequest;
use App\Services\Patient\SymptomAnalysisService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SymptomController extends Controller
{
    public function __construct(
        private readonly SymptomAnalysisService $symptomAnalysisService
    ) {
    }

    public function create(Request $request): View|RedirectResponse
    {
        if (! $this->selectedCenterId()) {
            return $this->redirectToCenterSelection();
        }

        return $this->renderForm(
            analysisResult: null,
            formSymptoms: $request->old('symptoms'),
            notes: $request->old('notes')
        );
    }

    public function store(SymptomAnalysisRequest $request): View|RedirectResponse
    {
        $centerId = $this->selectedCenterId();

        if (! $centerId) {
            return $this->redirectToCenterSelection();
        }

        $patient = $request->user()?->patient;
        if (! $patient) {
            return redirect()
                ->route('patient.profile.edit')
                ->withErrors(['symptoms' => __('We could not locate your patient profile. Please complete your profile before continuing.')]);
        }

        try {
            $result = $this->symptomAnalysisService->analyze(
                $request->toDto($centerId, $patient->id)
            );
        } catch (DomainException $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'symptoms' => $exception->getMessage(),
                ]);
        }

        session()->flash('status', __('AI analysis complete. Review the recommendations below.'));

        return $this->renderForm(
            analysisResult: $result,
            formSymptoms: $request->input('symptoms'),
            notes: $request->string('notes')->nullable()
        );
    }

    private function renderForm(?SymptomAnalysisResult $analysisResult, ?array $formSymptoms, ?string $notes): View
    {
        return view('patient.enter-symptoms', [
            'selectedCenterName' => session('patient.selected_center_name', __('Not selected')),
            'symptomSuggestions' => $this->symptomAnalysisService->symptomSuggestions(),
            'analysisResult' => $analysisResult,
            'formSymptoms' => $formSymptoms,
            'notesValue' => $notes,
        ]);
    }

    private function selectedCenterId(): ?int
    {
        return session('patient.selected_center_id');
    }

    private function redirectToCenterSelection(): RedirectResponse
    {
        return redirect()
            ->route('patient.diagnostic-centers.index')
            ->with('status', __('Select a diagnostic center to continue with symptom analysis.'));
    }
}

