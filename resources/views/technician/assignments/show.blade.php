@extends('technician.layout')

@section('title', 'Assignment Details')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Task Overview</h1>
        <div>
            <a href="{{ route('technician.dashboard') }}" class="text-indigo-600 hover:underline dark:text-indigo-400">Back to Dashboard</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Overview Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden p-6 max-w-3xl">
            <h2 class="text-lg font-semibold mb-4 border-b border-slate-200 dark:border-slate-700 pb-2">Customer & Goal</h2>
            
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Customer</dt>
                    <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $assignment->customer->name ?? '-' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Scheduled Date</dt>
                    <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $assignment->scheduled_date ? $assignment->scheduled_date->format('M d, Y') : '-' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Status</dt>
                    <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
                            {{ $assignment->status->name ?? '-' }}
                        </span>
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Assigned By</dt>
                    <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $assignment->admin->name ?? 'Admin' }}</dd>
                </div>
                
                <div class="sm:col-span-2 mt-2">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Service Task Description</dt>
                    <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100 whitespace-pre-wrap">{{ $assignment->description_by_admin }}</dd>
                </div>
                
                @if($assignment->review_by_admin)
                <div class="sm:col-span-2 mt-4 p-3 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-md">
                    <dt class="text-sm font-medium text-rose-700 dark:text-rose-400">Admin Notes / Return Reason</dt>
                    <dd class="mt-1 text-sm text-rose-900 dark:text-rose-300 whitespace-pre-wrap">{{ $assignment->review_by_admin }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <!-- Evidence Tracking Box -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden p-6 max-w-3xl">
            <h2 class="text-lg font-semibold mb-4 border-b border-slate-200 dark:border-slate-700 pb-2">Uploaded Evidence</h2>
            
            <div class="space-y-6">
                <!-- Check In Info -->
                <div>
                    <h3 class="text-sm font-medium text-slate-900 dark:text-slate-100 mb-2">Check In Record</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-sm text-slate-600 dark:text-slate-400">
                            <strong>Coords:</strong> 
                            @if($assignment->lat_check_in)
                                <br/>{{ $assignment->lat_check_in }}, {{ $assignment->lng_check_in }}
                            @else
                                <span class="italic">Pending</span>
                            @endif
                        </div>
                        <div>
                            @if($assignment->check_in_photo_path)
                                <img src="{{ asset('storage/' . $assignment->check_in_photo_path) }}" alt="Check-in Photo" class="w-full h-auto rounded border border-slate-200 dark:border-slate-600">
                            @else
                                <div class="w-full h-24 bg-slate-100 dark:bg-slate-700 flex items-center justify-center rounded border border-dashed border-slate-300 dark:border-slate-600 text-sm text-slate-500">No Check-in Photo</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Check Out Info -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                    <h3 class="text-sm font-medium text-slate-900 dark:text-slate-100 mb-2">Check Out Record</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-sm text-slate-600 dark:text-slate-400">
                            <strong>Coords:</strong> 
                            @if($assignment->lat_check_out)
                                <br/>{{ $assignment->lat_check_out }}, {{ $assignment->lng_check_out }}
                            @else
                                <span class="italic">Pending</span>
                            @endif
                            <br/><br/>
                            <strong>Your Closing Notes:</strong><br/>
                            {{ $assignment->description_by_technician ?? 'Pending' }}
                        </div>
                        <div>
                            @if($assignment->check_out_photo_path)
                                <img src="{{ asset('storage/' . $assignment->check_out_photo_path) }}" alt="Check-out Photo" class="w-full h-auto rounded border border-slate-200 dark:border-slate-600">
                            @else
                                <div class="w-full h-24 bg-slate-100 dark:bg-slate-700 flex items-center justify-center rounded border border-dashed border-slate-300 dark:border-slate-600 text-sm text-slate-500">No Check-out Photo</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
