@extends('layouts.auth')

@section('title', 'Create Patient Account · '.config('app.name', 'SmartDoc'))
@section('heading', 'Create your SmartDoc account')
@section('subheading', 'Register to access AI-powered symptom analysis and appointments')

@section('content')
    <form method="POST" action="{{ route('register.store') }}" class="space-y-6">
        @csrf
        <div class="grid gap-4 lg:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-200">Full name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
            </div>
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-200">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
            </div>
            <div>
                <label for="phone" class="block text-sm font-semibold text-slate-200">Phone number</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                       class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40" placeholder="01XXXXXXXXX">
            </div>
            <div>
                <label for="date_of_birth" class="block text-sm font-semibold text-slate-200">Date of birth</label>
                <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                       class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
            </div>
            <div>
                <label for="gender" class="block text-sm font-semibold text-slate-200">Gender</label>
                <select id="gender" name="gender" required
                        class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
                    <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select gender</option>
                    @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('gender') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="address" class="block text-sm font-semibold text-slate-200">Address</label>
                <input id="address" type="text" name="address" value="{{ old('address') }}" required
                       class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
            </div>
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-200">Password</label>
                <input id="password" type="password" name="password" required
                       class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40"
                       placeholder="At least 8 characters, letters & numbers">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-200">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
            </div>
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-4 py-3 text-center text-sm font-semibold text-emerald-950 transition hover:bg-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/40">
            Create account
        </button>
    </form>
@endsection

@section('footer')
    Already have an account?
    <a href="{{ route('login') }}" class="font-semibold text-emerald-300 transition hover:text-emerald-200">Sign in</a>
@endsection

