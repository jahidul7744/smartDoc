<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\ProfileRequest;
use App\Services\Patient\PatientProfileService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(private readonly PatientProfileService $profileService)
    {
    }

    public function edit(Request $request): View
    {
        $user = $request->user();

        abort_unless($user->role === 'patient', 403);

        $patient = $user->patient;

        return view('patient.profile', [
            'user' => $user,
            'patient' => $patient,
            'progress' => $patient?->profileCompletionProgress() ?? 0,
        ]);
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->role === 'patient', 403);

        $patient = $this->profileService->updatePatientProfile($user, $request->validated());

        return redirect()
            ->route('patient.profile.edit')
            ->with('status', $patient->hasCompletedProfile()
                ? 'Profile completed successfully.'
                : 'Profile updated successfully.');
    }
}

