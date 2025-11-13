@extends('layouts.doctor')

@section('title', 'Appointments · Doctor Portal')
@section('page-title', 'Appointment Manager')
@section('page-subtitle', 'Review upcoming visits and revisit past consultations.')

@section('content')
    <div class="rounded-2xl border border-slate-200 bg-white/90 p-6 shadow-sm backdrop-blur">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    @if ($activeTab === 'upcoming')
                        Upcoming appointments
                    @else
                        Past appointments
                    @endif
                </h2>
                <p class="text-sm text-slate-500">
                    {{ $activeTab === 'upcoming' ? 'Prioritise your day with AI-informed scheduling.' : 'Reference outcomes and prescriptions for continuity of care.' }}
                </p>
            </div>
            <div class="flex items-center gap-2 rounded-full bg-slate-100 p-1 text-sm font-medium text-slate-600">
                <a
                    href="{{ route('doctor.appointments.index') }}"
                    class="rounded-full px-4 py-1.5 transition @if($activeTab === 'upcoming') bg-white text-emerald-600 shadow-sm @else hover:text-emerald-600 @endif"
                >
                    Upcoming
                </a>
                <a
                    href="{{ route('doctor.appointments.past') }}"
                    class="rounded-full px-4 py-1.5 transition @if($activeTab === 'past') bg-white text-emerald-600 shadow-sm @else hover:text-emerald-600 @endif"
                >
                    History
                </a>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-medium">Patient</th>
                        <th class="px-6 py-3 font-medium">Date &amp; time</th>
                        <th class="px-6 py-3 font-medium">Center</th>
                        <th class="px-6 py-3 font-medium">AI insight</th>
                        <th class="px-6 py-3 font-medium text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white text-slate-700">
                    @forelse ($appointments as $appointment)
                        <tr class="transition hover:bg-emerald-50/60">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">{{ $appointment->patient->user->name }}</div>
                                <div class="text-xs text-slate-400">#{{ $appointment->uuid }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div>{{ optional($appointment->scheduled_at)->format('M d, Y') }}</div>
                                <div class="text-xs text-slate-500">{{ optional($appointment->scheduled_at)->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $appointment->diagnosticCenter->name }}</div>
                                <div class="text-xs text-slate-500">{{ $appointment->diagnosticCenter->city }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-xs text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12a3 3 0 006 0v-2a3 3 0 10-6 0v2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17h6m-6 4h6" />
                                    </svg>
                                    {{ $appointment->predicted_illness ?? 'Pending analysis' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-500">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                    <a
                                        href="{{ route('doctor.appointments.show', $appointment->id) }}"
                                        class="inline-flex items-center gap-1 rounded-full bg-emerald-500 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-600"
                                    >
                                        Details
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
                                No records found for this view. Adjust filters or check back later.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $appointments->withQueryString()->links() }}
        </div>
    </div>
@endsection

