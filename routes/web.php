<?php

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DiagnosticCenterController as AdminDiagnosticCenterController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Doctor\DiagnosisController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\Patient\DiagnosticCenterController;
use App\Http\Controllers\Patient\ProfileController;
use App\Http\Controllers\Patient\SymptomController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('patient/profile', [ProfileController::class, 'edit'])->name('patient.profile.edit');
    Route::put('patient/profile', [ProfileController::class, 'update'])->name('patient.profile.update');
});

Route::middleware(['auth', 'profile.complete'])->group(function () {
    Route::view('dashboard', 'patient.dashboard')->name('dashboard');
    Route::get('patient/diagnostic-centers', [DiagnosticCenterController::class, 'index'])
        ->name('patient.diagnostic-centers.index');
    Route::post('patient/diagnostic-centers/select', [DiagnosticCenterController::class, 'select'])
        ->name('patient.diagnostic-centers.select');
    Route::get('patient/symptoms', [SymptomController::class, 'create'])
        ->name('patient.symptoms.create');
    Route::post('patient/symptoms', [SymptomController::class, 'store'])
        ->name('patient.symptoms.store');
});

Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('dashboard', DoctorDashboardController::class)->name('dashboard');
    Route::get('appointments', [DoctorAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/past', [DoctorAppointmentController::class, 'past'])->name('appointments.past');
    Route::get('appointments/{appointment}', [DoctorAppointmentController::class, 'show'])->name('appointments.show');
    Route::post('appointments/{appointment}/diagnosis', [DiagnosisController::class, 'store'])->name('appointments.diagnosis.store');
    Route::post('diagnoses/{diagnosis}/prescriptions', [PrescriptionController::class, 'store'])->name('diagnoses.prescriptions.store');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', AdminDashboardController::class)->name('dashboard');

    Route::resource('centers', AdminDiagnosticCenterController::class);
    Route::resource('doctors', AdminDoctorController::class)->except(['show']);
    Route::post('doctors/{doctor}/assign', [AdminDoctorController::class, 'assign'])->name('doctors.assign');

    Route::get('appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
    Route::post('appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.status');
    Route::post('appointments/{appointment}/reschedule', [AdminAppointmentController::class, 'reschedule'])->name('appointments.reschedule');
    Route::post('appointments/{appointment}/reassign', [AdminAppointmentController::class, 'reassign'])->name('appointments.reassign');

    Route::get('notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/broadcast', [AdminNotificationController::class, 'broadcast'])->name('notifications.broadcast');
    Route::post('notifications/read', [AdminNotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('notifications/delete', [AdminNotificationController::class, 'destroy'])->name('notifications.destroy');
});
