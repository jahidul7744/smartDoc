@extends('layouts.auth')

@section('title', 'Verify your email · '.config('app.name', 'SmartDoc'))
@section('heading', 'Verify your email address')
@section('subheading', 'We sent a secure verification link to your inbox')

@section('content')
    <div class="space-y-6 text-sm text-slate-300">
        <p>
            Please confirm your email address by clicking the link we sent to <strong>{{ auth()->user()->email }}</strong>.
            This step ensures your medical records and appointments remain secure.
        </p>

        <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
            @csrf
            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-4 py-3 text-center text-sm font-semibold text-emerald-950 transition hover:bg-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/40">
                Resend verification email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center text-xs text-slate-400">
            @csrf
            <button type="submit" class="font-semibold text-emerald-300 transition hover:text-emerald-200">
                Log out and sign in with a different email
            </button>
        </form>
    </div>
@endsection

