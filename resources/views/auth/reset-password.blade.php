@extends('layouts.auth')

@section('title', 'Choose a new password · '.config('app.name', 'SmartDoc'))
@section('heading', 'Choose a new password')
@section('subheading', 'Secure your account with a strong password')

@section('content')
    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-200">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                       class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
            </div>
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-200">New password</label>
                <input id="password" type="password" name="password" required
                       class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-200">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
            </div>
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-4 py-3 text-center text-sm font-semibold text-emerald-950 transition hover:bg-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/40">
            Update password
        </button>
    </form>
@endsection

@section('footer')
    Changed your mind?
    <a href="{{ route('login') }}" class="font-semibold text-emerald-300 transition hover:text-emerald-200">Back to sign in</a>
@endsection

