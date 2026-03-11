@extends('admin.layout')

@section('title', 'Customer: ' . $customer->name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.customers.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">← Back to Customers</a>
    </div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Customer: {{ $customer->name }}</h1>
        <a href="{{ route('admin.customers.edit', $customer) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">Edit</a>
    </div>
    <dl class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 max-w-md space-y-3">
        <div>
            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Name</dt>
            <dd class="mt-0.5">{{ $customer->name }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Email</dt>
            <dd class="mt-0.5">{{ $customer->email }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Phone</dt>
            <dd class="mt-0.5">{{ $customer->phone }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Address</dt>
            <dd class="mt-0.5">{{ $customer->address }}</dd>
        </div>
        @if ($customer->latitude && $customer->longitude)
            <div>
                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Location</dt>
                <dd class="mt-0.5">{{ $customer->latitude }}, {{ $customer->longitude }}</dd>
            </div>
        @endif
    </dl>
@endsection
