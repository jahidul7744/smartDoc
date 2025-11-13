@extends('layouts.auth')

@section('title', 'Reset password · '.config('app.name', 'SmartDoc'))
@section('heading', 'Reset your password')
@section('subheading', 'We will send a secure link to your email address')

@section('content')
    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf
        <div>
            <label for="email" class="block text-sm font-semibold text-slate-200">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-4 py-3 text-center text-sm font-semibold text-emerald-950 transition hover:bg-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/40">
            Email password reset link
        </button>
    </form>
@endsection

@section('footer')
    Remembered your password?
    <a href="{{ route('login') }}" class="font-semibold text-emerald-300 transition hover:text-emerald-200">Back to sign in</a>
@endsection

