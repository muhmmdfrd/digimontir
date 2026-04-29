<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Technician') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-slate-100 min-h-screen">
    <div class="flex min-h-screen">
        <aside class="w-56 shrink-0 bg-slate-800 dark:bg-slate-950 border-r border-slate-700 flex flex-col">
            <div class="p-4 border-b border-slate-700">
                <a href="{{ route('technician.dashboard') }}" class="text-lg font-semibold text-white">{{ config('app.name') }} Tech</a>
            </div>
            <nav class="flex-1 p-2 space-y-0.5">
                <a href="{{ route('technician.dashboard') }}" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white {{ request()->routeIs('technician.dashboard') ? 'bg-slate-700 text-white' : '' }}">Dashboard</a>
            </nav>
            <div class="p-2 border-t border-slate-700 text-sm p-3 text-slate-400">
                Logged in as:<br/>
                <span class="text-white">{{ auth()->user()->name ?? 'User' }}</span>
            </div>
            <div class="p-2 border-t border-slate-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white">Logout</button>
                </form>
            </div>
        </aside>
        <main class="flex-1 overflow-auto p-6">
            @if (session('success'))
                <div class="mb-4 p-3 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/50 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            
            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800/50 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
