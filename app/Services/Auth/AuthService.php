<?php

namespace App\Services\Auth;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function registerPatient(array $payload): User
    {
        return DB::transaction(function () use ($payload) {
            $user = User::create([
                'name' => $payload['name'],
                'email' => $payload['email'],
                'phone' => $payload['phone'],
                'date_of_birth' => $payload['date_of_birth'],
                'gender' => $payload['gender'],
                'address' => $payload['address'],
                'role' => 'patient',
                'password' => Hash::make($payload['password']),
            ]);

            Patient::create([
                'user_id' => $user->id,
            ]);

            event(new Registered($user));

            return $user;
        });
    }
}

