@extends('admin.layout')

@section('title', 'Add Customer')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.customers.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">← Back to Customers</a>
    </div>
    <h1 class="text-2xl font-semibold mb-6">Add Customer</h1>
    <form action="{{ route('admin.customers.store') }}" method="POST" class="space-y-4 max-w-2xl">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                   class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                   class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Phone</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                   class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror">
            @error('phone')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Address</label>
            <div class="flex gap-2">
                <input type="text" name="address" id="address" value="{{ old('address') }}" required
                       class="flex-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-500 @enderror"
                       placeholder="Type address or search on map">
                <button type="button" id="search-address-btn" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg text-sm font-medium shrink-0">Search</button>
            </div>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Search for an address to set location on the map, or click on the map to set coordinates.</p>
            @error('address')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Select location on map (OpenStreetMap)</label>
            <div id="map" class="w-full h-80 rounded-lg border border-slate-300 dark:border-slate-600"></div>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Click on the map to set latitude and longitude.</p>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="latitude" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Latitude</label>
                <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" readonly
                       class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm bg-slate-50 dark:bg-slate-800/80">
                @error('latitude')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="longitude" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Longitude</label>
                <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" readonly
                       class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm bg-slate-50 dark:bg-slate-800/80">
                @error('longitude')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium">Create Customer</button>
    </form>
@endsection

@push('styles')
    <link 
        rel="stylesheet" 
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
@endpush

@push('scripts')
    <script 
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const addressInput = document.getElementById('address');
            const mapEl = document.getElementById('map');
            const defaultLat = {{ old('latitude', -6.2088) }};
            const defaultLng = {{ old('longitude', 106.8456) }};
            const hasInitial = latInput.value && lngInput.value;

            const map = L.map('map').setView([hasInitial ? parseFloat(latInput.value) : defaultLat, hasInitial ? parseFloat(lngInput.value) : defaultLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            let marker = null;
            if (hasInitial) {
                marker = L.marker([parseFloat(latInput.value), parseFloat(lngInput.value)]).addTo(map);
            }

            function updateFromLatLng(lat, lng) {
                latInput.value = lat;
                lngInput.value = lng;
                if (marker) marker.setLatLng([lat, lng]);
                else marker = L.marker([lat, lng]).addTo(map);
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.display_name && !addressInput.value) addressInput.value = data.display_name;
                    })
                    .catch(() => {});
            }

            map.on('click', function (e) {
                updateFromLatLng(e.latlng.lat, e.latlng.lng);
            });

            document.getElementById('search-address-btn').addEventListener('click', function () {
                const q = addressInput.value.trim();
                if (!q) return;
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=1`)
                    .then(r => r.json())
                    .then(data => {
                        if (data[0]) {
                            const lat = parseFloat(data[0].lat);
                            const lon = parseFloat(data[0].lon);
                            addressInput.value = data[0].display_name;
                            updateFromLatLng(lat, lon);
                            map.setView([lat, lon], 16);
                        }
                    })
                    .catch(() => {});
            });
        });
    </script>
@endpush
