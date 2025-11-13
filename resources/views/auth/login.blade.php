@extends('layouts.auth')

@section('title', 'Sign in · '.config('app.name', 'SmartDoc'))
@section('heading', 'Welcome back')
@section('subheading', 'Sign in to manage appointments and prescriptions')

@section('content')
    <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-200">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
            </div>
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-200">Password</label>
                <input id="password" type="password" name="password" required
                       class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
            </div>
        </div>

        <div class="flex items-center justify-between text-sm text-slate-400">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded border border-slate-500/40 bg-transparent text-emerald-400 focus:ring-emerald-400/40">
                <span>Keep me signed in</span>
            </label>
            <a href="{{ route('password.request') }}" class="font-semibold text-emerald-300 transition hover:text-emerald-200">Forgot password?</a>
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-4 py-3 text-center text-sm font-semibold text-emerald-950 transition hover:bg-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/40">
            Sign in
        </button>
    </form>
@endsection

@section('footer')
    New to SmartDoc?
    <a href="{{ route('register') }}" class="font-semibold text-emerald-300 transition hover:text-emerald-200">Create an account</a>
@endsection

