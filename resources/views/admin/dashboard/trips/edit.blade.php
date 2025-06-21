@extends('layouts.dashboard')

@section('title', 'Edit Trip - RideShare')
@section('page-title', 'Edit Trip')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Trip #{{ $trip->id }}</h1>
            <p class="text-gray-600">Update trip information</p>
        </div>
        <a href="{{ route('admin.trips.show', $trip) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-eye mr-2"></i>
            View Trip
        </a>
    </div>

    <!-- Edit Form -->
    <div class="card bg-white rounded-lg p-6">
        <form action="{{ route('admin.trips.update', $trip) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Basic Information</h3>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" {{ $trip->status->value === $status->value ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fare" class="block text-sm font-medium text-gray-700 mb-2">Fare ($)</label>
                        <input type="number" name="fare" id="fare" step="0.01" min="0" value="{{ old('fare', $trip->fare) }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('fare')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="driver_id" class="block text-sm font-medium text-gray-700 mb-2">Driver</label>
                        <select name="driver_id" id="driver_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Driver</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ $trip->driver_id == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->name }} ({{ $driver->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('driver_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="vehicle_id" class="block text-sm font-medium text-gray-700 mb-2">Vehicle</label>
                        <select name="vehicle_id" id="vehicle_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Vehicle</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ $trip->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->model }} - {{ $vehicle->plate_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Location Information</h3>
                    
                    <div>
                        <label for="pickup_location" class="block text-sm font-medium text-gray-700 mb-2">Pickup Location</label>
                        <input type="text" name="pickup_location" id="pickup_location" value="{{ old('pickup_location', $trip->pickup_location) }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('pickup_location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="pickup_latitude" class="block text-sm font-medium text-gray-700 mb-2">Pickup Latitude</label>
                            <input type="number" name="pickup_latitude" id="pickup_latitude" step="any" value="{{ old('pickup_latitude', $trip->pickup_latitude) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('pickup_latitude')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="pickup_longitude" class="block text-sm font-medium text-gray-700 mb-2">Pickup Longitude</label>
                            <input type="number" name="pickup_longitude" id="pickup_longitude" step="any" value="{{ old('pickup_longitude', $trip->pickup_longitude) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('pickup_longitude')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="dropoff_location" class="block text-sm font-medium text-gray-700 mb-2">Dropoff Location</label>
                        <input type="text" name="dropoff_location" id="dropoff_location" value="{{ old('dropoff_location', $trip->dropoff_location) }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('dropoff_location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="dropoff_latitude" class="block text-sm font-medium text-gray-700 mb-2">Dropoff Latitude</label>
                            <input type="number" name="dropoff_latitude" id="dropoff_latitude" step="any" value="{{ old('dropoff_latitude', $trip->dropoff_latitude) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('dropoff_latitude')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="dropoff_longitude" class="block text-sm font-medium text-gray-700 mb-2">Dropoff Longitude</label>
                            <input type="number" name="dropoff_longitude" id="dropoff_longitude" step="any" value="{{ old('dropoff_longitude', $trip->dropoff_longitude) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('dropoff_longitude')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.trips.show', $trip) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Update Trip
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 