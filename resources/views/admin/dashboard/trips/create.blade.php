@extends('layouts.dashboard')

@section('title', 'Add Trip - RideShare Dashboard')
@section('page-title', 'Add New Trip')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card bg-white rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Add New Trip</h2>
            <a href="{{ route('admin.trips.index') }}" class="text-blue-500 hover:text-blue-600">
                <i class="fas fa-arrow-left mr-2"></i>Back to Trips
            </a>
        </div>

        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.trips.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Trip Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Trip Information</h3>
                    
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Customer *</label>
                        <select name="user_id" id="user_id" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                required>
                            <option value="">Select Customer</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="driver_id" class="block text-sm font-medium text-gray-700 mb-2">Driver *</label>
                        <select name="driver_id" id="driver_id" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                required>
                            <option value="">Select Driver</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->user->name }} ({{ $driver->license_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="vehicle_id" class="block text-sm font-medium text-gray-700 mb-2">Vehicle *</label>
                        <select name="vehicle_id" id="vehicle_id" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                required>
                            <option value="">Select Vehicle</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->model }} - {{ $vehicle->plate_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" id="status" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                required>
                            <option value="">Select Status</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="fare" class="block text-sm font-medium text-gray-700 mb-2">Fare *</label>
                        <input type="number" name="fare" id="fare" value="{{ old('fare') }}" 
                               step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Location Information</h3>
                    
                    <div>
                        <label for="pickup_location" class="block text-sm font-medium text-gray-700 mb-2">Pickup Location *</label>
                        <input type="text" name="pickup_location" id="pickup_location" value="{{ old('pickup_location') }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                    </div>

                    <div>
                        <label for="dropoff_location" class="block text-sm font-medium text-gray-700 mb-2">Dropoff Location *</label>
                        <input type="text" name="dropoff_location" id="dropoff_location" value="{{ old('dropoff_location') }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="pickup_latitude" class="block text-sm font-medium text-gray-700 mb-2">Pickup Latitude</label>
                            <input type="number" name="pickup_latitude" id="pickup_latitude" value="{{ old('pickup_latitude') }}" 
                                   step="0.0001" min="-90" max="90"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="e.g., 40.7128">
                        </div>

                        <div>
                            <label for="pickup_longitude" class="block text-sm font-medium text-gray-700 mb-2">Pickup Longitude</label>
                            <input type="number" name="pickup_longitude" id="pickup_longitude" value="{{ old('pickup_longitude') }}" 
                                   step="0.0001" min="-180" max="180"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="e.g., -74.0060">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="dropoff_latitude" class="block text-sm font-medium text-gray-700 mb-2">Dropoff Latitude</label>
                            <input type="number" name="dropoff_latitude" id="dropoff_latitude" value="{{ old('dropoff_latitude') }}" 
                                   step="0.0001" min="-90" max="90"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="e.g., 40.7589">
                        </div>

                        <div>
                            <label for="dropoff_longitude" class="block text-sm font-medium text-gray-700 mb-2">Dropoff Longitude</label>
                            <input type="number" name="dropoff_longitude" id="dropoff_longitude" value="{{ old('dropoff_longitude') }}" 
                                   step="0.0001" min="-180" max="180"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="e.g., -73.9851">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                            <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                            <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time') }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.trips.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-save mr-2"></i>Create Trip
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 