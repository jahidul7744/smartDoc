<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Patient Portal · '.config('app.name', 'SmartDoc'))</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-100 font-sans text-slate-900">
        <div class="min-h-screen">
            <div class="flex">
                <aside class="hidden w-64 flex-col justify-between bg-slate-900 px-6 py-8 text-slate-200 lg:flex">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <span class="text-lg font-semibold">SmartDoc</span>
                        </div>

                        <nav class="mt-10 space-y-2 text-sm">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 rounded-xl px-4 py-2 transition @if(request()->routeIs('dashboard')) bg-emerald-500/10 text-emerald-300 @else text-slate-300 hover:bg-slate-800/60 @endif">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7m-9 13V5m0 0L5 10m14 0l-7-7" />
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('patient.profile.edit') }}" class="flex items-center gap-2 rounded-xl px-4 py-2 transition @if(request()->routeIs('patient.profile.*')) bg-emerald-500/10 text-emerald-300 @else text-slate-300 hover:bg-slate-800/60 @endif">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 21a8.38 8.38 0 00-7.5-4.5c-3.035 0-5.713 1.615-7.5 4.5" />
                                </svg>
                                Profile
                            </a>
                        </nav>
                    </div>

                    <div class="space-y-2 text-xs text-slate-500">
                        <p>&copy; {{ date('Y') }} SmartDoc. All rights reserved.</p>
                    </div>
                </aside>

                <div class="flex min-h-screen flex-1 flex-col">
                    <header class="border-b border-slate-200 bg-white/80 backdrop-blur">
                        <div class="flex items-center justify-between px-4 py-4 lg:px-8">
                            <div>
                                <h1 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Patient Dashboard')</h1>
                                <p class="text-sm text-slate-500">@yield('page-subtitle', 'Stay on top of your health journey')</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <p class="text-sm font-medium text-slate-700">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-400">{{ ucfirst(auth()->user()->role) }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-rose-300 hover:bg-rose-50 hover:text-rose-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-7.5A2.25 2.25 0 003.75 5.25v13.5A2.25 2.25 0 006 21h7.5a2.25 2.25 0 002.25-2.25V15M12 9l3-3m0 0l3 3m-3-3v12" />
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </header>

                    <main class="flex-1 px-4 py-8 lg:px-8">
                        @if (session('status'))
                            <div class="mb-6 rounded-xl border border-emerald-400/40 bg-emerald-100 px-4 py-3 text-sm text-emerald-700">
                                {{ session('status') }}
                            </div>
                        @endif

                        @yield('content')
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>

