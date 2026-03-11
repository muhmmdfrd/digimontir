@extends('admin.layout')

@section('title', 'Edit Role')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.roles.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">← Back to Roles</a>
    </div>
    <h1 class="text-2xl font-semibold mb-6">Edit Role</h1>
    <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="max-w-md space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="code" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Code</label>
            <input type="text" name="code" id="code" value="{{ old('code', $role->code) }}" required
                   class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('code') border-red-500 @enderror">
            @error('code')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required
                   class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium">Update Role</button>
    </form>
@endsection
