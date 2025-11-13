@extends('layouts.doctor')

@php
use Illuminate\Support\Str;
@endphp

@section('title', 'Doctor Dashboard · '.config('app.name'))
@section('page-title', 'Good day, Dr. '.$doctor->user->name)
@section('page-subtitle', 'Stay ahead with AI-driven insights and streamlined patient workflows.')

@section('content')
    <div class="space-y-10">
        <section>
            <div class="grid gap-4 md:grid-cols-3">
                <article class="rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-sm backdrop-blur">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-400">Upcoming</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-800">{{ $summary['upcoming_count'] }}</p>
                        </div>
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-4 text-sm text-slate-500">Booked consultations awaiting your expertise.</p>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-sm backdrop-blur">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-400">Today</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-800">{{ $summary['today_count'] }}</p>
                        </div>
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-sky-500/10 text-sky-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c1.657 0 3-1.343 3-3S13.657 2 12 2 9 3.343 9 5s1.343 3 3 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 21a8.38 8.38 0 00-7.5-4.5c-3.035 0-5.713 1.615-7.5 4.5" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-4 text-sm text-slate-500">Patient engagements scheduled for today.</p>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-sm backdrop-blur">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-400">Follow-ups</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-800">{{ $summary['follow_up_count'] }}</p>
                        </div>
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500/10 text-amber-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-3-3v6m9 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-4 text-sm text-slate-500">Patients requiring continued care and monitoring.</p>
                </article>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white/90 p-6 shadow-sm backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">Upcoming appointments</h2>
                        <p class="text-sm text-slate-500">AI-prioritised schedule for the next few days.</p>
                    </div>
                    <a href="{{ route('doctor.appointments.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-500">View all</a>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($upcomingAppointments as $appointment)
                        <a
                            href="{{ route('doctor.appointments.show', $appointment->id) }}"
                            class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 text-sm transition hover:border-emerald-200 hover:bg-emerald-50/70"
                        >
                            <div>
                                <p class="font-medium text-slate-800">{{ $appointment->patient->user->name }}</p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ optional($appointment->scheduled_at)->format('M d · h:i A') }} —
                                    {{ Str::of($appointment->predicted_illness ?? 'Awaiting notes')->headline() }}
                                </p>
                            </div>
                            <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-600">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </a>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 px-4 py-6 text-center text-sm text-slate-500">
                            No upcoming appointments. Enjoy a moment for planning and research.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white/90 p-6 shadow-sm backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">Recent prescriptions</h2>
                        <p class="text-sm text-slate-500">Digitally issued treatments over the past week.</p>
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($recentPrescriptions as $prescription)
                        <div class="rounded-xl border border-slate-200 px-4 py-3 text-sm shadow-sm">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-slate-800">{{ $prescription->patient->user->name }}</p>
                                <span class="text-xs text-slate-400">{{ optional($prescription->issued_at)->format('M d, Y') }}</span>
                            </div>
                            <p class="mt-2 text-xs uppercase tracking-wide text-slate-400">Diagnosis</p>
                            <p class="text-sm text-slate-600">{{ $prescription->diagnosis->final_diagnosis }}</p>
                            <p class="mt-2 text-xs uppercase tracking-wide text-slate-400">Medicines</p>
                            <ul class="mt-1 space-y-1 text-sm text-slate-600">
                                @foreach ($prescription->medicines as $medicine)
                                    <li class="flex items-start justify-between gap-4">
                                        <span class="font-medium">{{ $medicine->medicine_name }}</span>
                                        <span class="text-xs text-slate-500">{{ $medicine->dosage }} • {{ $medicine->frequency }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 px-4 py-6 text-center text-sm text-slate-500">
                            No prescriptions issued yet. Once you complete a consultation, your records appear here.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
@endsection

