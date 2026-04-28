@extends('admin.layout')

@section('title', 'Assignments')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Assignments</h1>
        <a href="{{ route('admin.assignments.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">Create Assignment</a>
    </div>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Tabs -->
        <div class="flex space-x-1 rounded-lg bg-slate-200 p-1 dark:bg-slate-700 text-sm">
            <a href="{{ request()->fullUrlWithQuery(['tab' => null, 'page' => null]) }}" class="px-3 py-1.5 font-medium rounded-md {{ !request('tab') ? 'bg-white shadow dark:bg-slate-600 text-slate-800 dark:text-white' : 'text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-white' }}">All Time</a>
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'past', 'page' => null]) }}" class="px-3 py-1.5 font-medium rounded-md {{ request('tab') == 'past' ? 'bg-white shadow dark:bg-slate-600 text-slate-800 dark:text-white' : 'text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-white' }}">Past</a>
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'today', 'page' => null]) }}" class="px-3 py-1.5 font-medium rounded-md {{ request('tab') == 'today' ? 'bg-white shadow dark:bg-slate-600 text-slate-800 dark:text-white' : 'text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-white' }}">Today</a>
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'upcoming', 'page' => null]) }}" class="px-3 py-1.5 font-medium rounded-md {{ request('tab') == 'upcoming' ? 'bg-white shadow dark:bg-slate-600 text-slate-800 dark:text-white' : 'text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-white' }}">Upcoming</a>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('admin.assignments.index') }}" class="flex items-center gap-2">
            @if(request('tab'))
                <input type="hidden" name="tab" value="{{ request('tab') }}">
            @endif
            <select name="technician_id" class="text-sm rounded border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-2 py-1.5 focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                <option value="">All Technicians</option>
                @foreach($technicians as $tech)
                    <option value="{{ $tech->id }}" {{ request('technician_id') == $tech->id ? 'selected' : '' }}>{{ $tech->name }}</option>
                @endforeach
            </select>
            
            <select name="status_id" class="text-sm rounded border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-2 py-1.5 focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                @endforeach
            </select>
        </form>
    </div>
    
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 dark:bg-slate-700/50">
                <tr>
                    <th class="text-left px-4 py-3 text-sm font-medium text-slate-600 dark:text-slate-400">Date</th>
                    <th class="text-left px-4 py-3 text-sm font-medium text-slate-600 dark:text-slate-400">Customer</th>
                    <th class="text-left px-4 py-3 text-sm font-medium text-slate-600 dark:text-slate-400">Technician</th>
                    <th class="text-left px-4 py-3 text-sm font-medium text-slate-600 dark:text-slate-400">Status</th>
                    <th class="text-right px-4 py-3 text-sm font-medium text-slate-600 dark:text-slate-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse ($assignments as $assignment)
                    <tr>
                        <td class="px-4 py-3">{{ $assignment->scheduled_date ? $assignment->scheduled_date->format('M d, Y') : '-' }}</td>
                        <td class="px-4 py-3 font-medium">{{ $assignment->customer->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $assignment->technician->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-300">
                                {{ $assignment->status->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.assignments.show', $assignment) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">View</a>
                            <a href="{{ route('admin.assignments.edit', $assignment) }}" class="ml-3 text-indigo-600 dark:text-indigo-400 hover:underline text-sm">Edit</a>
                            <form action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST" class="inline ml-3" onsubmit="return confirm('Delete this assignment?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">No assignments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($assignments->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700">
                {{ $assignments->links() }}
            </div>
        @endif
    </div>
@endsection
