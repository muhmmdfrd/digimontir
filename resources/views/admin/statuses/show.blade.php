@extends('admin.layout')

@section('title', 'Status: ' . $status->name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.statuses.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">← Back to Statuses</a>
    </div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Status: {{ $status->name }}</h1>
        <a href="{{ route('admin.statuses.edit', $status) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">Edit</a>
    </div>
    <dl class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 max-w-md space-y-3">
        <div>
            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Code</dt>
            <dd class="mt-0.5">{{ $status->code }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Name</dt>
            <dd class="mt-0.5">{{ $status->name }}</dd>
        </div>
    </dl>
@endsection
