<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-slate-100 min-h-screen">
    <div class="flex min-h-screen">
        <aside class="w-56 shrink-0 bg-slate-800 dark:bg-slate-950 border-r border-slate-700 flex flex-col">
            <div class="p-4 border-b border-slate-700">
                <a href="{{ route('admin.dashboard') }}" class="text-lg font-semibold text-white">{{ config('app.name') }} Admin</a>
            </div>
            <nav class="flex-1 p-2 space-y-0.5">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-slate-700 text-white' : '' }}">Dashboard</a>
                <a href="{{ route('admin.assignments.index') }}" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white {{ request()->routeIs('admin.assignments.*') ? 'bg-slate-700 text-white' : '' }}">Assignments</a>
                <a href="{{ route('admin.customers.index') }}" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white {{ request()->routeIs('admin.customers.*') ? 'bg-slate-700 text-white' : '' }}">Customers</a>
                <a href="{{ route('admin.roles.index') }}" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white {{ request()->routeIs('admin.roles.*') ? 'bg-slate-700 text-white' : '' }}">Roles</a>
                <a href="{{ route('admin.statuses.index') }}" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white {{ request()->routeIs('admin.statuses.*') ? 'bg-slate-700 text-white' : '' }}">Statuses</a>
                <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white {{ request()->routeIs('admin.users.*') ? 'bg-slate-700 text-white' : '' }}">Users</a>
            </nav>
            <div class="p-2 border-t border-slate-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white">Logout</button>
                </form>
            </div>
        </aside>
        <main class="flex-1 overflow-auto p-6">
            @if (session('success'))
                <div class="mb-4 p-3 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
