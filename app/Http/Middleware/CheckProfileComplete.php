<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileComplete
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        $user = $request->user();

        if (! $user || $user->role !== 'patient') {
            return $next($request);
        }

        $patient = $user->patient;

        if (! $patient) {
            return redirect()->route('patient.profile.edit');
        }

        if ($patient->hasCompletedProfile()) {
            return $next($request);
        }

        if ($request->routeIs('patient.profile.*')) {
            return $next($request);
        }

        return redirect()
            ->route('patient.profile.edit')
            ->with('status', 'Complete your profile to access the system.');
    }
}

