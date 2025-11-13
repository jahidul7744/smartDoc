<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PatientRegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(PatientRegisterRequest $request): RedirectResponse
    {
        $user = $this->authService->registerPatient($request->validated());

        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('status', 'Registration successful. Please verify your email address to continue.');
    }
}

