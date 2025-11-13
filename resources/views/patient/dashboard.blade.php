@extends('layouts.patient')

@section('title', 'Patient dashboard · '.config('app.name', 'SmartDoc'))
@section('page-title', 'Patient dashboard')
@section('page-subtitle', 'Track appointments, prescriptions, and AI insights')

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-3xl border border-emerald-200 bg-white p-6 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Good to see you, {{ auth()->user()->name }} 👋</h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Your profile is complete. You can now access AI-driven symptom analysis, browse diagnostic centres,
                        and book appointments with specialists.
                    </p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Next step</p>
                    <p class="mt-2 text-sm text-slate-600">Start by entering your current symptoms to receive AI-powered predictions.</p>
                    <p class="mt-4 inline-flex items-center gap-2 text-xs font-semibold text-emerald-600">
                        Symptom intake module launching soon
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Need assistance?</p>
                    <p class="mt-2 text-sm text-slate-600">Our care team is available 24/7 to support your journey to better health.</p>
                    <p class="mt-4 inline-flex items-center gap-2 text-xs font-semibold text-emerald-600">
                        Support channel will be available in upcoming release
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Quick facts</h3>
            <dl class="mt-4 space-y-4 text-sm text-slate-600">
                <div class="flex items-center justify-between">
                    <dt>Profile status</dt>
                    <dd class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-600">
                        <span class="inline-block h-2 w-2 rounded-full bg-emerald-500"></span>
                        Complete
                    </dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt>Registered email</dt>
                    <dd class="font-medium text-slate-800">{{ auth()->user()->email }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt>Emergency contact</dt>
                    <dd class="text-right font-medium text-slate-800">
                        {{ auth()->user()->patient?->emergency_contact_name }}<br>
                        <span class="text-xs text-slate-500">{{ auth()->user()->patient?->emergency_contact_phone }}</span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
@endsection

