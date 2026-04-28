@extends('admin.layout')

@section('title', 'Create Assignment')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Create Assignment</h1>
        <a href="{{ route('admin.assignments.index') }}" class="text-indigo-600 hover:underline dark:text-indigo-400">Back to List</a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden p-6 max-w-3xl">
        <form action="{{ route('admin.assignments.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="customer_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Customer</label>
                <select id="customer_id" name="customer_id" class="w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" required>
                    <option value="">Select a Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->address }})</option>
                    @endforeach
                </select>
                @error('customer_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="technician_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Assign Technician</label>
                <select id="technician_id" name="technician_id" class="w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" required>
                    <option value="">Select a Technician</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}" {{ old('technician_id') == $tech->id ? 'selected' : '' }}>{{ $tech->name }}</option>
                    @endforeach
                </select>
                @error('technician_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="status_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Initial Status</label>
                <select id="status_id" name="status_id" class="w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" required>
                    <option value="">Select a Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                    @endforeach
                </select>
                @error('status_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="scheduled_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Scheduled Date</label>
                <input type="date" id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date', date('Y-m-d')) }}" class="w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" required>
                @error('scheduled_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label for="description_by_admin" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Task Description</label>
                <textarea id="description_by_admin" name="description_by_admin" rows="4" class="w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" required>{{ old('description_by_admin') }}</textarea>
                @error('description_by_admin') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors">
                    Assign Task
                </button>
            </div>
        </form>
    </div>
@endsection
