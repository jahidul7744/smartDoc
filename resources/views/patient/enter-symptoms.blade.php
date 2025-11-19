@extends('layouts.patient')

@section('title', 'AI Symptom Analysis · '.config('app.name', 'SmartDoc'))

@section('page-title', 'AI Symptom Analysis')
@section('page-subtitle', 'Describe your symptoms to receive instant AI insights and doctor recommendations')

@php
    $symptomRows = collect($formSymptoms ?? [])
        ->filter(fn ($row) => is_array($row))
        ->map(function ($row) {
            return [
                'name' => $row['name'] ?? '',
                'severity' => $row['severity'] ?? 5,
                'duration' => $row['duration'] ?? 3,
            ];
        })
        ->values();

    if ($symptomRows->isEmpty()) {
        $symptomRows = collect([
            ['name' => '', 'severity' => 5, 'duration' => 3],
        ]);
    }

    $notesInput = $notesValue ?? '';
@endphp

@section('content')
    <div class="grid gap-8 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl border border-emerald-200/50 bg-white/90 p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-wide text-slate-400">Diagnostic center</p>
                        <p class="text-lg font-semibold text-slate-800">{{ $selectedCenterName }}</p>
                    </div>
                    <a href="{{ route('patient.diagnostic-centers.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:border-emerald-300 hover:text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                        </svg>
                        Change center
                    </a>
                </div>
                <div class="mt-4 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-emerald-50/80 p-4">
                        <p class="text-xs uppercase text-emerald-500">Step 1</p>
                        <p class="mt-1 text-sm text-slate-600">List up to 10 symptoms with severity and duration.</p>
                    </div>
                    <div class="rounded-2xl bg-emerald-50/80 p-4">
                        <p class="text-xs uppercase text-emerald-500">Step 2</p>
                        <p class="mt-1 text-sm text-slate-600">Our ML engine predicts likely illnesses in seconds.</p>
                    </div>
                    <div class="rounded-2xl bg-emerald-50/80 p-4">
                        <p class="text-xs uppercase text-emerald-500">Step 3</p>
                        <p class="mt-1 text-sm text-slate-600">Receive specialization & doctor recommendations.</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('patient.symptoms.store') }}" class="rounded-2xl border border-slate-200 bg-white/95 p-6 shadow-sm">
                @csrf

                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-800">Symptom details</h2>
                        <p class="text-sm text-slate-500">Provide accurate information for tailored predictions.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-500">1–10 symptoms</span>
                </div>

                @error('symptoms')
                    <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-600">
                        {{ $message }}
                    </div>
                @enderror

                <div id="symptom-rows" class="mt-6 space-y-4" data-current-count="{{ $symptomRows->count() }}">
                    @foreach ($symptomRows as $index => $row)
                        <div class="rounded-2xl border border-slate-200/80 p-4 transition hover:border-emerald-200" data-symptom-row data-index="{{ $index }}">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <p class="text-sm font-medium text-slate-600">
                                    Symptom {{ $loop->iteration }}
                                </p>
                                <button type="button" class="text-xs font-medium text-rose-500 hover:text-rose-600" data-remove-row {{ $symptomRows->count() === 1 ? 'disabled' : '' }}>
                                    Remove
                                </button>
                            </div>
                            <div class="mt-4 grid gap-4 md:grid-cols-3">
                                <div class="md:col-span-2">
                                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Symptom name</label>
                                    <input type="text" name="symptoms[{{ $index }}][name]" list="symptom-suggestions" value="{{ $row['name'] }}" placeholder="e.g., Persistent cough" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" required>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Duration (days)</label>
                                    <input type="number" name="symptoms[{{ $index }}][duration]" min="1" max="365" value="{{ $row['duration'] }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" required>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Severity: <span class="text-emerald-600" data-severity-value>{{ $row['severity'] }}</span>/10</label>
                                <input type="range" name="symptoms[{{ $index }}][severity]" min="1" max="10" value="{{ $row['severity'] }}" class="mt-2 w-full accent-emerald-500" data-severity-input>
                            </div>
                        </div>
                    @endforeach
                </div>

                <datalist id="symptom-suggestions">
                    @foreach ($symptomSuggestions as $suggestion)
                        <option value="{{ $suggestion }}"></option>
                    @endforeach
                </datalist>

                <div class="mt-4">
                    <button type="button" id="add-symptom-row" class="inline-flex items-center gap-2 rounded-xl border border-dashed border-emerald-300 px-4 py-2 text-sm font-semibold text-emerald-600 transition hover:border-emerald-400 hover:bg-emerald-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add another symptom
                    </button>
                    <p class="mt-1 text-xs text-slate-400">Stop at ten symptoms so our AI can stay focused.</p>
                </div>

                <div class="mt-8">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Additional notes (optional)</label>
                    <textarea name="notes" rows="4" class="mt-2 w-full rounded-2xl border border-slate-200 px-3 py-3 text-sm text-slate-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Share any context like medications, allergies, or recent travel.">{{ $notesInput }}</textarea>
                </div>

                <div class="mt-8 flex flex-wrap items-center justify-between gap-4">
                    <p class="text-xs text-slate-400">
                        By continuing, you acknowledge this tool offers guidance only and does not replace medical advice.
                    </p>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 transition hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Run AI analysis
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white/95 p-6 shadow-sm">
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01M4.93 4.93l14.14 14.14M3 12a9 9 0 0115.36-6.36M21 12a9 9 0 01-15.36 6.36" />
        </svg>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Important</p>
                        <p class="mt-1 text-xs text-slate-500">
                            This AI assistant provides educational insights only. Always consult licensed physicians for diagnosis and treatment decisions.
                        </p>
                    </div>
                </div>
            </div>

            @if ($analysisResult)
                <div class="rounded-2xl border border-emerald-200 bg-white/95 p-6 shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-500">Primary prediction</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-800">{{ $analysisResult->primaryPrediction->label }}</p>
                    <p class="mt-1 text-sm text-slate-500">Confidence score: {{ number_format($analysisResult->primaryPrediction->confidence * 100, 0) }}%</p>
                    <div class="mt-4 rounded-2xl bg-emerald-50/80 p-4 text-sm text-slate-600">
                        We recommend booking with a specialist listed below to validate these findings.
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white/95 p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Top possible illnesses</p>
                    <div class="mt-4 space-y-3">
                        @php
                            $ranked = collect([$analysisResult->primaryPrediction])->merge($analysisResult->alternatives);
                        @endphp
                        @foreach ($ranked as $index => $prediction)
                            <div class="flex items-center justify-between rounded-2xl border border-slate-100 px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">{{ $index + 1 }}. {{ $prediction->label }}</p>
                                    <p class="text-xs text-slate-400">Confidence: {{ number_format($prediction->confidence * 100, 0) }}%</p>
                                </div>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">AI Insight</span>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-4 text-xs text-slate-400">This AI prediction does not replace professional medical diagnosis.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white/95 p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Recommended specializations</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($analysisResult->recommendedSpecializations as $specialization)
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                {{ $specialization }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white/95 p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Doctors in your center</p>
                            <p class="text-sm text-slate-500">Aligned with the recommended specialization(s).</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-500">
                            {{ $analysisResult->recommendedDoctors->count() }} available
                        </span>
                    </div>

                    <div class="mt-4 space-y-4">
                        @forelse ($analysisResult->recommendedDoctors as $doctor)
                            <div class="rounded-2xl border border-slate-100 px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">{{ $doctor['name'] }}</p>
                                        <p class="text-xs text-slate-400">{{ $doctor['specialization'] }}</p>
                                    </div>
                                    @if ($doctor['rating'])
                                        <div class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-600">
                                            ★ {{ number_format($doctor['rating'], 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-slate-500">
                                    <span>{{ $doctor['experience_years'] ?? '—' }} yrs experience</span>
                                    <span>Fee: {{ $doctor['consultation_fee'] ? '৳'.number_format($doctor['consultation_fee'], 2) : 'Contact center' }}</span>
                                    @if ($doctor['qualifications'])
                                        <span>{{ $doctor['qualifications'] }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 px-4 py-4 text-sm text-slate-500">
                                No doctors with these specialties are currently available in this center. Our care team will help you choose the closest match.
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-200 bg-white/90 p-6 text-center text-sm text-slate-500">
                    Submit your symptoms to unlock AI-powered predictions and curated doctor matches.
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('symptom-rows');
            const addButton = document.getElementById('add-symptom-row');
            const maxRows = 10;

            const updateSeverityLabels = () => {
                container.querySelectorAll('[data-severity-input]').forEach(input => {
                    const label = input.closest('[data-symptom-row]').querySelector('[data-severity-value]');
                    if (label) {
                        label.textContent = input.value;
                    }
                });
            };

            updateSeverityLabels();

            container.addEventListener('input', event => {
                if (event.target.matches('[data-severity-input]')) {
                    const row = event.target.closest('[data-symptom-row]');
                    const label = row.querySelector('[data-severity-value]');
                    if (label) {
                        label.textContent = event.target.value;
                    }
                }
            });

            container.addEventListener('click', event => {
                if (event.target.closest('[data-remove-row]')) {
                    const rows = container.querySelectorAll('[data-symptom-row]');
                    if (rows.length === 1) {
                        return;
                    }

                    event.target.closest('[data-symptom-row]').remove();
                }
            });

            addButton?.addEventListener('click', () => {
                const currentRows = container.querySelectorAll('[data-symptom-row]').length;
                if (currentRows >= maxRows) {
                    addButton.setAttribute('disabled', 'disabled');
                    addButton.classList.add('cursor-not-allowed', 'opacity-50');
                    return;
                }

                const index = Date.now();
                const row = document.createElement('div');
                row.className = 'rounded-2xl border border-slate-200/80 p-4 transition hover:border-emerald-200 mt-4';
                row.setAttribute('data-symptom-row', '');
                row.dataset.index = index;
                row.innerHTML = `
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <p class="text-sm font-medium text-slate-600">Symptom</p>
                        <button type="button" class="text-xs font-medium text-rose-500 hover:text-rose-600" data-remove-row>Remove</button>
                    </div>
                    <div class="mt-4 grid gap-4 md:grid-cols-3">
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Symptom name</label>
                            <input type="text" name="symptoms[${index}][name]" list="symptom-suggestions" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="e.g., Dizziness" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Duration (days)</label>
                            <input type="number" name="symptoms[${index}][duration]" min="1" max="365" value="3" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Severity: <span class="text-emerald-600" data-severity-value>5</span>/10</label>
                        <input type="range" name="symptoms[${index}][severity]" min="1" max="10" value="5" class="mt-2 w-full accent-emerald-500" data-severity-input>
                    </div>
                `;

                container.appendChild(row);
                updateSeverityLabels();
            });
        });
    </script>
@endpush

