<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', config('app.name', 'SmartDoc'))</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-slate-100">
        <div class="relative flex min-h-screen flex-col justify-center px-6 py-12">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -right-40 h-72 w-72 rounded-full bg-cyan-500/10 blur-3xl"></div>
                <div class="absolute bottom-0 left-1/2 h-64 w-64 -translate-x-1/2 rounded-full bg-emerald-500/10 blur-3xl"></div>
            </div>

            <div class="relative mx-auto w-full max-w-md">
                <div class="mb-8 flex flex-col items-center text-center">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <h1 class="mt-4 text-2xl font-semibold text-white">@yield('heading', 'Welcome to SmartDoc')</h1>
                    <p class="mt-2 text-sm text-slate-400">@yield('subheading', 'AI-powered healthcare appointment and diagnosis management')</p>
                </div>

                <div class="rounded-3xl border border-white/5 bg-slate-900/80 p-8 shadow-xl backdrop-blur">
                    @if (session('status'))
                        <div class="mb-4 rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-xl border border-rose-500/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-200">
                            <strong class="font-semibold">Please review the following:</strong>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>

                @hasSection('footer')
                    <div class="mt-6 text-center text-sm text-slate-400">
                        @yield('footer')
                    </div>
                @endif
            </div>
        </div>
    </body>
</html>

