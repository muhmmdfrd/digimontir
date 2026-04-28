@extends('admin.layout')

@section('title', 'Assignment Details')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Assignment Details</h1>
        <div class="flex gap-2 items-center">
            @if($assignment->status && $assignment->status->code === 'WREV')
                <button onclick="document.getElementById('completeModal').classList.remove('hidden')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium">Sign Off</button>
                <button onclick="document.getElementById('returnModal').classList.remove('hidden')" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-sm font-medium">Return</button>
            @endif
            <a href="{{ route('admin.assignments.edit', $assignment) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">Edit</a>
            <a href="{{ route('admin.assignments.index') }}" class="text-indigo-600 hover:underline dark:text-indigo-400 ml-2">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Overview Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden p-6 max-w-3xl">
            <h2 class="text-lg font-semibold mb-4 border-b border-slate-200 dark:border-slate-700 pb-2">Overview</h2>
            
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Customer</dt>
                    <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $assignment->customer->name ?? '-' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Technician</dt>
                    <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $assignment->technician->name ?? '-' }}</dd>
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
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Created At</dt>
                    <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $assignment->created_at->format('M d, Y h:i A') }}</dd>
                </div>
                
                <div class="sm:col-span-2 mt-2">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Task Description</dt>
                    <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100 whitespace-pre-wrap">{{ $assignment->description_by_admin }}</dd>
                </div>
            </dl>
        </div>

        <!-- Progress Tracking Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden p-6 max-w-3xl">
            <h2 class="text-lg font-semibold mb-4 border-b border-slate-200 dark:border-slate-700 pb-2">Tracking & Evidence</h2>
            
            <div class="space-y-6">
                <!-- Check In Info -->
                <div>
                    <h3 class="text-sm font-medium text-slate-900 dark:text-slate-100 mb-2">Check In</h3>
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
                                <img src="{{ asset('storage/' . $assignment->check_in_photo_path) }}" alt="Check-in Photo" class="w-full h-auto rounded border">
                            @else
                                <div class="w-full h-24 bg-slate-100 dark:bg-slate-700 flex items-center justify-center rounded border border-dashed border-slate-300 dark:border-slate-600 text-sm text-slate-500">No Photo</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Check Out Info -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                    <h3 class="text-sm font-medium text-slate-900 dark:text-slate-100 mb-2">Check Out</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-sm text-slate-600 dark:text-slate-400">
                            <strong>Coords:</strong> 
                            @if($assignment->lat_check_out)
                                <br/>{{ $assignment->lat_check_out }}, {{ $assignment->lng_check_out }}
                            @else
                                <span class="italic">Pending</span>
                            @endif
                            <br/><br/>
                            <strong>Tech Notes:</strong><br/>
                            {{ $assignment->description_by_technician ?? 'Pending' }}
                        </div>
                        <div>
                            @if($assignment->check_out_photo_path)
                                <img src="{{ asset('storage/' . $assignment->check_out_photo_path) }}" alt="Check-out Photo" class="w-full h-auto rounded border">
                            @else
                                <div class="w-full h-24 bg-slate-100 dark:bg-slate-700 flex items-center justify-center rounded border border-dashed border-slate-300 dark:border-slate-600 text-sm text-slate-500">No Photo</div>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($assignment->closed_at)
                <!-- Completion Info -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                    <h3 class="text-sm font-medium text-slate-900 dark:text-slate-100 mb-1">Admin Evaluation <span class="text-xs font-normal text-slate-500">({{ $assignment->closed_at->format('M d') }})</span></h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">
                        <strong>Rating:</strong> {{ $assignment->rating ? $assignment->rating . '/5' : 'None' }}<br/>
                        <strong>Notes:</strong> {{ $assignment->review_by_admin ?? 'None' }}
                    </p>
                </div>
                @endif
                
            </div>
        </div>
    </div>
    <!-- Complete Modal -->
    <div id="completeModal" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-xl max-w-md w-full mx-4 shadow-xl border border-slate-200 dark:border-slate-700">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="font-semibold text-lg text-slate-900 dark:text-slate-100">Review & Complete Task</h3>
            </div>
            <form method="POST" action="{{ route('admin.assignments.complete', $assignment) }}" class="p-5">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Rating (1-5)</label>
                    <input type="number" name="rating" min="1" max="5" value="5" class="w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Review Notes</label>
                    <textarea name="review_by_admin" rows="3" class="w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" required></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('completeModal').classList.add('hidden')" class="px-4 py-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg text-sm font-medium">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium">Submit & Close Task</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Return Modal -->
    <div id="returnModal" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-xl max-w-md w-full mx-4 shadow-xl border border-slate-200 dark:border-slate-700">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="font-semibold text-lg text-slate-900 dark:text-slate-100">Return Task to Technician</h3>
            </div>
            <form method="POST" action="{{ route('admin.assignments.return', $assignment) }}" class="p-5">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Reason for Return</label>
                    <textarea name="review_by_admin" rows="3" placeholder="Explain what needs to be fixed..." class="w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-rose-500 focus:ring-rose-500 p-2" required></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('returnModal').classList.add('hidden')" class="px-4 py-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg text-sm font-medium">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-sm font-medium">Return Task</button>
                </div>
            </form>
        </div>
    </div>
@endsection
