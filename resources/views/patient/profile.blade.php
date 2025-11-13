@extends('layouts.patient')

@section('title', 'Complete profile · '.config('app.name', 'SmartDoc'))
@section('page-title', 'Complete your medical profile')
@section('page-subtitle', 'Provide essential health details to personalize your care')

@section('content')
    <div class="grid gap-8 lg:grid-cols-[320px,1fr]">
        <div class="space-y-6">
            <div class="rounded-3xl border border-emerald-500/30 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-600">Profile completion</p>
                        <p class="mt-1 text-3xl font-semibold text-slate-900">{{ $progress }}%</p>
                    </div>
                    <div class="relative h-20 w-20">
                        <svg class="h-20 w-20 text-slate-200" viewBox="0 0 120 120">
                            <circle cx="60" cy="60" r="54" fill="none" stroke="currentColor" stroke-width="10" />
                            <circle
                                cx="60"
                                cy="60"
                                r="54"
                                fill="none"
                                stroke="#10b981"
                                stroke-width="10"
                                stroke-linecap="round"
                                stroke-dasharray="{{ 339 * ($progress / 100) }} 339"
                                transform="rotate(-90 60 60)"
                            />
                        </svg>
                        <span class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-emerald-600">Goal 100%</span>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-600">
                    Completing your profile helps doctors review your history before appointments and ensures accurate AI predictions.
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-800">Patient information</h2>
                <dl class="mt-4 space-y-3 text-sm text-slate-600">
                    <div class="flex justify-between">
                        <dt>Name</dt>
                        <dd class="font-medium text-slate-800">{{ $user->name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt>Email</dt>
                        <dd class="font-medium text-slate-800">{{ $user->email }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt>Phone</dt>
                        <dd class="font-medium text-slate-800">{{ $user->phone }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt>Date of birth</dt>
                        <dd class="font-medium text-slate-800">{{ $user->date_of_birth->format('d M Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt>Address</dt>
                        <dd class="ml-4 max-w-[180px] text-right font-medium text-slate-800">{{ $user->address }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Medical background</h2>
            <p class="mt-2 text-sm text-slate-500">Provide accurate information to help doctors diagnose and prescribe effectively.</p>

            @if ($errors->any())
                <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-600">
                    <p class="font-semibold">We need a quick fix:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('patient.profile.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="medical_history" class="block text-sm font-semibold text-slate-700">Medical history</label>
                        <textarea id="medical_history" name="medical_history" rows="4" required
                                  class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-500/20"
                                  placeholder="Chronic conditions, previous surgeries, ongoing treatments">{{ old('medical_history', $patient?->medical_history) }}</textarea>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <div>
                            <label for="blood_group" class="block text-sm font-semibold text-slate-700">Blood group</label>
                            <select id="blood_group" name="blood_group" required
                                    class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-500/20">
                                <option value="" disabled {{ old('blood_group', $patient?->blood_group) ? '' : 'selected' }}>Select blood group</option>
                                @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                    <option value="{{ $group }}" @selected(old('blood_group', $patient?->blood_group) === $group)>{{ $group }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="allergies" class="block text-sm font-semibold text-slate-700">Allergies</label>
                            <input id="allergies" type="text" name="allergies" required
                                   value="{{ old('allergies', $patient?->allergies) }}"
                                   class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-500/20"
                                   placeholder="E.g., Penicillin, Peanuts, Pollen">
                        </div>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <div>
                            <label for="emergency_contact_name" class="block text-sm font-semibold text-slate-700">Emergency contact name</label>
                            <input id="emergency_contact_name" type="text" name="emergency_contact_name" required
                                   value="{{ old('emergency_contact_name', $patient?->emergency_contact_name) }}"
                                   class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-500/20">
                        </div>
                        <div>
                            <label for="emergency_contact_phone" class="block text-sm font-semibold text-slate-700">Emergency contact phone</label>
                            <input id="emergency_contact_phone" type="text" name="emergency_contact_phone" required
                                   value="{{ old('emergency_contact_phone', $patient?->emergency_contact_phone) }}"
                                   class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-500/20"
                                   placeholder="01XXXXXXXXX">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <p class="text-xs text-slate-500">All information is encrypted and shared securely with your healthcare team.</p>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 px-6 py-3 text-sm font-semibold text-emerald-950 transition hover:bg-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/20">
                        Save details
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

