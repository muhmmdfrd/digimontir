@extends('technician.layout')

@section('title', 'My assignments')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
        <h1 class="text-2xl font-semibold">Assigned Tasks</h1>

        <!-- Header Filters -->
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
            <!-- Tabs -->
            <div class="flex space-x-1 rounded-lg bg-white dark:bg-slate-800 p-1 border border-slate-200 dark:border-slate-700 text-sm">
                <a href="{{ request()->fullUrlWithQuery(['tab' => null, 'page' => null]) }}" class="px-3 py-1.5 font-medium rounded-md {{ !request('tab') ? 'bg-indigo-50 dark:bg-slate-700 text-indigo-700 dark:text-white' : 'text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">All Time</a>
                <a href="{{ request()->fullUrlWithQuery(['tab' => 'past', 'page' => null]) }}" class="px-3 py-1.5 font-medium rounded-md {{ request('tab') == 'past' ? 'bg-indigo-50 dark:bg-slate-700 text-indigo-700 dark:text-white' : 'text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">Past</a>
                <a href="{{ request()->fullUrlWithQuery(['tab' => 'today', 'page' => null]) }}" class="px-3 py-1.5 font-medium rounded-md {{ request('tab') == 'today' ? 'bg-indigo-50 dark:bg-slate-700 text-indigo-700 dark:text-white' : 'text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">Today</a>
                <a href="{{ request()->fullUrlWithQuery(['tab' => 'upcoming', 'page' => null]) }}" class="px-3 py-1.5 font-medium rounded-md {{ request('tab') == 'upcoming' ? 'bg-indigo-50 dark:bg-slate-700 text-indigo-700 dark:text-white' : 'text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">Upcoming</a>
            </div>

            <!-- Status Dropdown -->
            <form method="GET" action="{{ route('technician.dashboard') }}">
                @if(request('tab'))
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                @endif
                <select name="status_id" class="text-sm rounded border-slate-300 dark:border-slate-700 dark:bg-slate-800 focus:border-indigo-500 py-1.5 px-3" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($assignments as $assignment)
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold text-lg dark:text-slate-200">
                            <a href="{{ route('technician.assignments.show', $assignment->id) }}" class="hover:text-indigo-600 hover:underline">
                                {{ $assignment->customer->name ?? 'Unknown Customer' }}
                            </a>
                        </h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Status: 
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                                {{ $assignment->status->name ?? 'No Status' }}
                            </span>
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-500 dark:text-slate-400 block font-medium">Scheduled</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 block">{{ $assignment->scheduled_date ? $assignment->scheduled_date->format('M d, Y') : 'Unscheduled' }}</span>
                    </div>
                </div>
                
                <div class="p-5 bg-slate-50 dark:bg-slate-800/50">
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 line-clamp-3 min-h-[3rem]">
                        {{ $assignment->description_by_admin ?? 'No description provided.' }}
                    </p>
                    
                    <div class="flex gap-2">
                        @if($assignment->status && $assignment->status->code === 'ASGN')
                            <button onclick="startCheckIn({{ $assignment->id }})" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-2 px-4 rounded text-sm font-medium transition-colors">
                                Check In (GPS)
                            </button>
                        @elseif($assignment->status && in_array($assignment->status->code, ['CKIN', 'RTRN']))
                            <button onclick="openCheckOutModal({{ $assignment->id }})" class="w-full bg-amber-500 hover:bg-amber-600 text-white py-2 px-4 rounded text-sm font-medium transition-colors">
                                {{ $assignment->status->code === 'RTRN' ? 'Re-Submit Check Out' : 'Check Out (GPS)' }}
                            </button>
                        @else
                            <button disabled class="w-full bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 py-2 px-4 rounded text-sm font-medium cursor-not-allowed border border-transparent">
                                {{ $assignment->status->name ?? 'Completed' }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-slate-800 p-8 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700 mb-4">
                    <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100">No tasks assigned</h3>
                <p class="text-slate-500 dark:text-slate-400 mt-1">You currently have no assignments allocated.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $assignments->links() }}
    </div>

    <!-- Check In Modal -->
    <div id="checkInModal" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-xl max-w-md w-full mx-4 shadow-xl border border-slate-200 dark:border-slate-700">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="font-semibold text-lg text-slate-900 dark:text-slate-100">Start Task (Check In)</h3>
                <button onclick="closeCheckInModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="checkInForm" method="POST" action="" enctype="multipart/form-data" class="p-5">
                @csrf
                <input type="hidden" name="lat_check_in" id="ci_lat">
                <input type="hidden" name="lng_check_in" id="ci_lng">

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Check-in Photo</label>
                    <input type="file" name="check_in_photo" id="ci_photo" accept="image/*" capture="environment" required class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-slate-700 dark:file:text-slate-300">
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" id="ci_submit" disabled class="w-full sm:w-auto px-6 py-2 bg-slate-300 text-slate-600 rounded-lg text-sm font-medium cursor-not-allowed">
                        Acquiring GPS...
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Check Out Modal -->
    <div id="checkOutModal" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-xl max-w-md w-full mx-4 shadow-xl border border-slate-200 dark:border-slate-700">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="font-semibold text-lg text-slate-900 dark:text-slate-100">Complete Task (Check Out)</h3>
                <button onclick="closeCheckOutModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="checkOutForm" method="POST" action="" enctype="multipart/form-data" class="p-5">
                @csrf
                <input type="hidden" name="lat_check_out" id="co_lat">
                <input type="hidden" name="lng_check_out" id="co_lng">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Technician Notes</label>
                    <textarea name="description_by_technician" rows="3" class="w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-amber-500 focus:ring-amber-500 p-2" required placeholder="Describe the work done..."></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Evidentiary Photo</label>
                    <input type="file" name="check_out_photo" id="co_photo" accept="image/*" capture="environment" required class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 dark:file:bg-slate-700 dark:file:text-slate-300">
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" id="co_submit" disabled class="w-full sm:w-auto px-6 py-2 bg-slate-300 text-slate-600 rounded-lg text-sm font-medium cursor-not-allowed">
                        Acquiring GPS...
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Check-In Logic
        function startCheckIn(id) {
            document.getElementById('checkInForm').action = `/technician/assignments/${id}/check-in`;
            const submitBtn = document.getElementById('ci_submit');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Acquiring GPS...';
            submitBtn.className = 'w-full sm:w-auto px-6 py-2 bg-slate-300 text-slate-600 rounded-lg text-sm font-medium cursor-not-allowed';
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        document.getElementById('ci_lat').value = position.coords.latitude;
                        document.getElementById('ci_lng').value = position.coords.longitude;
                        
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Submit & Start';
                        submitBtn.className = 'w-full sm:w-auto px-6 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-sm font-medium transition-colors cursor-pointer';
                    }, 
                    (error) => {
                        alert("Geolocation failed! Please ensure location permissions are granted.");
                        closeCheckInModal();
                    },
                    { enableHighAccuracy: true }
                );
                document.getElementById('checkInModal').classList.remove('hidden');
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
        
        function closeCheckInModal() {
            document.getElementById('checkInModal').classList.add('hidden');
            document.getElementById('checkInForm').reset();
        }

        // Check-Out Logic
        function openCheckOutModal(id) {
            document.getElementById('checkOutForm').action = `/technician/assignments/${id}/check-out`;
            const submitBtn = document.getElementById('co_submit');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Acquiring GPS...';
            submitBtn.className = 'w-full sm:w-auto px-6 py-2 bg-slate-300 text-slate-600 rounded-lg text-sm font-medium cursor-not-allowed';

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        document.getElementById('co_lat').value = position.coords.latitude;
                        document.getElementById('co_lng').value = position.coords.longitude;

                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Submit & Close Task';
                        submitBtn.className = 'w-full sm:w-auto px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-medium transition-colors cursor-pointer';
                    },
                    (error) => {
                        alert("We need your location for checkout. Please enable GPS permissions.");
                        closeCheckOutModal();
                    },
                    { enableHighAccuracy: true }
                );
                
                document.getElementById('checkOutModal').classList.remove('hidden');
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function closeCheckOutModal() {
            document.getElementById('checkOutModal').classList.add('hidden');
            document.getElementById('checkOutForm').reset();
        }
    </script>
    @endpush
@endsection
