@extends('layouts.doctor')

@php
use Illuminate\Support\Str;
@endphp

@section('title', 'Appointment Details · Doctor Portal')
@section('page-title', 'Consultation workspace')
@section('page-subtitle', 'Review patient context, record medical insights, and issue prescriptions quickly.')

@section('content')
    @php
        $diagnosis = $appointment->diagnosis;
        $prescription = $appointment->prescription;
        $recommendedTests = old('recommended_tests', $diagnosis?->recommended_tests ?? ['']);
        if (count($recommendedTests) < 3) {
            $recommendedTests = array_pad($recommendedTests, 3, '');
        }
        $medicineRows = old('medicines', $prescription?->medicines->map(fn ($medicine) => [
            'medicine_name' => $medicine->medicine_name,
            'dosage' => $medicine->dosage,
            'frequency' => $medicine->frequency,
            'duration' => $medicine->duration,
            'instructions' => $medicine->instructions,
        ])->toArray() ?? [['medicine_name' => '', 'dosage' => '', 'frequency' => '', 'duration' => '', 'instructions' => '']]);
    @endphp

    <div class="grid gap-8 lg:grid-cols-3">
        <section class="lg:col-span-2 space-y-6">
            <article class="rounded-2xl border border-slate-200 bg-white/90 p-6 shadow-sm backdrop-blur">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">Patient overview</h2>
                        <p class="text-sm text-slate-500">Essential context to support evidence-based decisions.</p>
                    </div>
                    <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-600">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </div>

                <dl class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 px-4 py-3">
                        <dt class="text-xs uppercase tracking-wide text-slate-400">Patient</dt>
                        <dd class="mt-2 text-sm font-semibold text-slate-800">{{ $appointment->patient->user->name }}</dd>
                        <dd class="mt-1 text-xs text-slate-500">{{ $appointment->patient->user->gender }} · {{ $appointment->patient->user->date_of_birth->age }} years</dd>
                    </div>
                    <div class="rounded-xl border border-slate-200 px-4 py-3">
                        <dt class="text-xs uppercase tracking-wide text-slate-400">Schedule</dt>
                        <dd class="mt-2 text-sm font-semibold text-slate-800">{{ optional($appointment->scheduled_at)->format('M d, Y · h:i A') }}</dd>
                        <dd class="mt-1 text-xs text-slate-500">{{ $appointment->diagnosticCenter->name }}</dd>
                    </div>
                    <div class="rounded-xl border border-slate-200 px-4 py-3 md:col-span-2">
                        <dt class="text-xs uppercase tracking-wide text-slate-400">AI-assisted insight</dt>
                        <dd class="mt-2 text-sm font-semibold text-slate-800">
                            {{ $appointment->predicted_illness ?? 'Pending user input / AI processing' }}
                            @if ($appointment->ai_confidence)
                                <span class="ml-2 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">
                                    Confidence {{ number_format($appointment->ai_confidence * 100, 1) }}%
                                </span>
                            @endif
                        </dd>
                        @if ($appointment->symptoms)
                            <div class="mt-3 grid gap-2 sm:grid-cols-2">
                                @foreach ($appointment->symptoms as $symptom)
                                    <div class="rounded-lg border border-slate-100 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                                        <span class="font-semibold text-slate-700">{{ Str::headline($symptom['name'] ?? '') }}</span>
                                        <span class="ml-2 text-slate-400">Severity {{ $symptom['severity'] ?? 'N/A' }}/10</span>
                                        <div class="mt-1 text-slate-400">Duration {{ $symptom['duration'] ?? 'N/A' }}</div>
                                        @if (! empty($symptom['notes']))
                                            <div class="mt-1 text-slate-500">{{ $symptom['notes'] }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if ($appointment->chief_complaint)
                            <p class="mt-4 rounded-xl bg-emerald-50/50 px-4 py-3 text-sm text-emerald-600">
                                “{{ $appointment->chief_complaint }}”
                            </p>
                        @endif
                    </div>
                </dl>
            </article>

            @if ($diagnosis)
                <article class="rounded-2xl border border-emerald-200 bg-emerald-50/80 p-6 shadow-inner">
                    <h2 class="text-lg font-semibold text-emerald-800">Diagnosis summary</h2>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-emerald-600">Final diagnosis</p>
                            <p class="mt-2 text-sm font-semibold text-emerald-900">{{ $diagnosis->final_diagnosis }}</p>
                            @if ($diagnosis->clinical_notes)
                                <p class="mt-3 text-sm text-emerald-700">{{ $diagnosis->clinical_notes }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-emerald-600">Follow-up</p>
                            <p class="mt-2 text-sm font-medium text-emerald-800">
                                {{ $diagnosis->follow_up_required ? 'Required' : 'Not required' }}
                            </p>
                            @if ($diagnosis->follow_up_at)
                                <p class="text-sm text-emerald-700">{{ $diagnosis->follow_up_at->format('M d, Y · h:i A') }}</p>
                            @endif
                        </div>
                    </div>
                    @if ($diagnosis->recommended_tests)
                        <div class="mt-4">
                            <p class="text-xs uppercase tracking-wide text-emerald-600">Recommended tests</p>
                            <ul class="mt-2 flex flex-wrap gap-2 text-xs font-medium text-emerald-700">
                                @foreach ($diagnosis->recommended_tests as $test)
                                    <li class="rounded-full bg-white/70 px-3 py-1 shadow-sm">{{ $test }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </article>
            @endif

            @if ($prescription)
                <article class="rounded-2xl border border-slate-200 bg-white/95 p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-800">Prescription summary</h2>
                    <p class="text-xs uppercase tracking-wide text-slate-400">Issued {{ optional($prescription->issued_at)->format('M d, Y h:i A') }}</p>
                    <div class="mt-4 space-y-4">
                        @foreach ($prescription->medicines as $medicine)
                            <div class="medicine-card rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <p class="text-sm font-semibold text-slate-800">{{ $medicine->medicine_name }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $medicine->dosage }} · {{ $medicine->frequency }} · {{ $medicine->duration }}
                                </p>
                                @if ($medicine->instructions)
                                    <p class="mt-1 text-xs text-slate-500">{{ $medicine->instructions }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @if ($prescription->general_instructions)
                        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                            {{ $prescription->general_instructions }}
                        </div>
                    @endif
                </article>
            @endif
        </section>

        <section class="space-y-6">
            <article class="rounded-2xl border border-slate-200 bg-white/95 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-800">Record diagnosis</h2>
                <p class="text-sm text-slate-500">Document clinical findings and outline next steps.</p>

                <form method="POST" action="{{ route('doctor.appointments.diagnosis.store', $appointment->id) }}" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label for="final_diagnosis" class="text-xs font-semibold uppercase tracking-wide text-slate-500">Final diagnosis<span class="text-rose-500">*</span></label>
                        <input
                            id="final_diagnosis"
                            name="final_diagnosis"
                            type="text"
                            value="{{ old('final_diagnosis', $diagnosis->final_diagnosis ?? '') }}"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100"
                            required
                        >
                        @error('final_diagnosis')
                            <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="clinical_notes" class="text-xs font-semibold uppercase tracking-wide text-slate-500">Clinical notes</label>
                        <textarea
                            id="clinical_notes"
                            name="clinical_notes"
                            rows="4"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100"
                        >{{ old('clinical_notes', $diagnosis->clinical_notes ?? '') }}</textarea>
                        @error('clinical_notes')
                            <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Recommended tests</p>
                        <div id="recommended-tests" class="mt-2 space-y-2">
                            @foreach ($recommendedTests as $index => $test)
                                <input
                                    name="recommended_tests[{{ $index }}]"
                                    type="text"
                                    value="{{ $test }}"
                                    placeholder="e.g., Complete Blood Count"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100"
                                >
                            @endforeach
                        </div>
                        @error('recommended_tests.*')
                            <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-4">
                        <label class="flex items-start gap-3 text-sm text-slate-700">
                            <input
                                type="checkbox"
                                name="follow_up_required"
                                value="1"
                                @checked(old('follow_up_required', $diagnosis->follow_up_required ?? false))
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-400"
                            >
                            <span>
                                <span class="font-semibold text-slate-800">Follow-up required</span>
                                <span class="block text-xs text-slate-500">Patient receives reminders based on your selected slot.</span>
                            </span>
                        </label>

                        <div class="mt-3">
                            <label for="follow_up_at" class="text-xs font-semibold uppercase tracking-wide text-slate-500">Follow-up schedule</label>
                            <input
                                id="follow_up_at"
                                name="follow_up_at"
                                type="datetime-local"
                                value="{{ old('follow_up_at', optional($diagnosis->follow_up_at)->format('Y-m-d\TH:i')) }}"
                                class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100"
                            >
                            @error('follow_up_at')
                                <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600">
                            Save diagnosis
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white/95 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-800">Issue prescription</h2>
                <p class="text-sm text-slate-500">Generate and share a digital prescription instantly.</p>

                @if ($diagnosis)
                <form method="POST" action="{{ route('doctor.diagnoses.prescriptions.store', $diagnosis->id) }}" class="mt-5 space-y-4">
                    @csrf

                    <div id="medicine-list" class="space-y-3">
                        @foreach ($medicineRows as $index => $medicine)
                            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Medicine {{ $index + 1 }}
                                    </label>
                                    <button type="button" class="text-xs font-medium text-rose-500 hover:text-rose-600 remove-medicine" data-index="{{ $index }}">
                                        Remove
                                    </button>
                                </div>
                                <div class="mt-3 grid gap-3 md:grid-cols-2">
                                    <div>
                                        <input
                                            name="medicines[{{ $index }}][medicine_name]"
                                            type="text"
                                            value="{{ $medicine['medicine_name'] }}"
                                            placeholder="Medicine name"
                                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100"
                                            required
                                        >
                                        @error("medicines.$index.medicine_name")
                                            <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <input
                                        name="medicines[{{ $index }}][dosage]"
                                        type="text"
                                        value="{{ $medicine['dosage'] }}"
                                        placeholder="Dosage (e.g., 500 mg)"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100"
                                    >
                                    <input
                                        name="medicines[{{ $index }}][frequency]"
                                        type="text"
                                        value="{{ $medicine['frequency'] }}"
                                        placeholder="Frequency (e.g., Twice daily)"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100"
                                    >
                                    <input
                                        name="medicines[{{ $index }}][duration]"
                                        type="text"
                                        value="{{ $medicine['duration'] }}"
                                        placeholder="Duration (e.g., 5 days)"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100"
                                    >
                                </div>
                                <textarea
                                    name="medicines[{{ $index }}][instructions]"
                                    rows="2"
                                    placeholder="Special instructions (e.g., after meals)"
                                    class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100"
                                >{{ $medicine['instructions'] }}</textarea>
                                @error("medicines.$index.instructions")
                                    <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    <button
                        type="button"
                        id="add-medicine"
                        class="inline-flex items-center gap-2 rounded-xl border border-dashed border-emerald-300 bg-emerald-50/70 px-4 py-2 text-sm font-semibold text-emerald-600 transition hover:bg-emerald-100"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m6-6H6" />
                        </svg>
                        Add medicine
                    </button>

                    <div>
                        <label for="general_instructions" class="text-xs font-semibold uppercase tracking-wide text-slate-500">General instructions</label>
                        <textarea
                            id="general_instructions"
                            name="general_instructions"
                            rows="3"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100"
                        >{{ old('general_instructions', $prescription->general_instructions ?? '') }}</textarea>
                        @error('general_instructions')
                            <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prescription_follow_up_at" class="text-xs font-semibold uppercase tracking-wide text-slate-500">Follow-up reminder</label>
                        <input
                            id="prescription_follow_up_at"
                            name="follow_up_at"
                            type="datetime-local"
                            value="{{ old('follow_up_at', optional($prescription->follow_up_at)->format('Y-m-d\TH:i')) }}"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100"
                        >
                        @error('follow_up_at')
                            <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600">
                            Issue &amp; send prescription
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4l16 8-16 8 4-8-4-8z" />
                            </svg>
                        </button>
                    </div>
                </form>
                @else
                    <div class="mt-5 rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-sm text-slate-500">
                        Record a diagnosis before issuing a prescription. Once saved, SmartDoc enables PDF generation and patient notifications here.
                    </div>
                @endif
            </article>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('medicine-list');
            const addButton = document.getElementById('add-medicine');

            const template = () => {
                const index = container.querySelectorAll('.medicine-card').length;
                return `
                    <div class="medicine-card rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 mt-3">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Medicine ${index + 1}
                            </label>
                            <button type="button" class="text-xs font-medium text-rose-500 hover:text-rose-600 remove-medicine" data-index="${index}">
                                Remove
                            </button>
                        </div>
                        <div class="mt-3 grid gap-3 md:grid-cols-2">
                            <div>
                                <input name="medicines[${index}][medicine_name]" type="text" placeholder="Medicine name" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100" required>
                            </div>
                            <input name="medicines[${index}][dosage]" type="text" placeholder="Dosage (e.g., 500 mg)" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100">
                            <input name="medicines[${index}][frequency]" type="text" placeholder="Frequency (e.g., Twice daily)" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100">
                            <input name="medicines[${index}][duration]" type="text" placeholder="Duration (e.g., 5 days)" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100">
                        </div>
                        <textarea name="medicines[${index}][instructions]" rows="2" placeholder="Special instructions (e.g., after meals)" class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-emerald-100"></textarea>
                    </div>
                `;
            };

            addButton?.addEventListener('click', () => {
                const wrapper = document.createElement('div');
                wrapper.innerHTML = template();
                container.appendChild(wrapper.firstElementChild);
            });

            container?.addEventListener('click', (event) => {
                if (event.target.matches('.remove-medicine')) {
                    const card = event.target.closest('.medicine-card') ?? event.target.closest('.rounded-xl');
                    card?.remove();
                }
            });
        });
    </script>
@endpush

