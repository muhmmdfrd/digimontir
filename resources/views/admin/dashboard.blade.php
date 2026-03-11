@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.customers.index') }}" class="block p-6 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 transition-colors">
            <h2 class="font-medium text-slate-600 dark:text-slate-400">Customers</h2>
            <p class="text-2xl font-semibold mt-1">{{ \App\Models\Customer::count() }}</p>
        </a>
        <a href="{{ route('admin.roles.index') }}" class="block p-6 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 transition-colors">
            <h2 class="font-medium text-slate-600 dark:text-slate-400">Roles</h2>
            <p class="text-2xl font-semibold mt-1">{{ \App\Models\Role::count() }}</p>
        </a>
        <a href="{{ route('admin.statuses.index') }}" class="block p-6 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 transition-colors">
            <h2 class="font-medium text-slate-600 dark:text-slate-400">Statuses</h2>
            <p class="text-2xl font-semibold mt-1">{{ \App\Models\Status::count() }}</p>
        </a>
        <a href="{{ route('admin.users.index') }}" class="block p-6 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 transition-colors">
            <h2 class="font-medium text-slate-600 dark:text-slate-400">Users</h2>
            <p class="text-2xl font-semibold mt-1">{{ \App\Models\User::count() }}</p>
        </a>
    </div>
@endsection
