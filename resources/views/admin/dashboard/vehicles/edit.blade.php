@extends('layouts.dashboard')

@section('title', 'Edit Vehicle - RideShare Dashboard')
@section('page-title', 'Edit Vehicle')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card bg-white rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Edit Vehicle</h2>
            <a href="{{ route('admin.vehicles.show', $vehicle) }}" class="text-blue-500 hover:text-blue-600">
                <i class="fas fa-eye mr-2"></i>View Details
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

        <form action="{{ route('admin.vehicles.update', $vehicle) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Vehicle Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Vehicle Information</h3>
                    
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">Model *</label>
                        <input type="text" name="model" id="model" value="{{ old('model', $vehicle->model) }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                    </div>

                    <div>
                        <label for="plate_number" class="block text-sm font-medium text-gray-700 mb-2">Plate Number *</label>
                        <input type="text" name="plate_number" id="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                    </div>

                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Year *</label>
                        <select name="year" id="year" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                required>
                            <option value="">Select Year</option>
                            @for($year = date('Y') + 1; $year >= 2000; $year--)
                                <option value="{{ $year }}" {{ old('year', $vehicle->year) == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Vehicle Type *</label>
                        <select name="type" id="type" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                required>
                            <option value="">Select Type</option>
                            <option value="sedan" {{ old('type', $vehicle->type?->value) == 'sedan' ? 'selected' : '' }}>Sedan</option>
                            <option value="suv" {{ old('type', $vehicle->type?->value) == 'suv' ? 'selected' : '' }}>SUV</option>
                            <option value="luxury" {{ old('type', $vehicle->type?->value) == 'luxury' ? 'selected' : '' }}>Luxury</option>
                            <option value="van" {{ old('type', $vehicle->type?->value) == 'van' ? 'selected' : '' }}>Van</option>
                        </select>
                    </div>

                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Color *</label>
                        <input type="text" name="color" id="color" value="{{ old('color', $vehicle->color) }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                    </div>
                </div>

                <!-- Assignment & Location -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Assignment & Location</h3>
                    
                    <div>
                        <label for="driver_id" class="block text-sm font-medium text-gray-700 mb-2">Assigned Driver</label>
                        <select name="driver_id" id="driver_id" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">No Driver Assigned</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver_id', $vehicle->driver_id) == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->user->name }} ({{ $driver->license_number }})
                                    @if($driver->id == $vehicle->driver_id) - Currently Assigned @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                        <input type="number" name="latitude" id="latitude" value="{{ old('latitude', $vehicle->latitude) }}" 
                               step="0.0001" min="-90" max="90"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="e.g., 40.7128">
                    </div>

                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                        <input type="number" name="longitude" id="longitude" value="{{ old('longitude', $vehicle->longitude) }}" 
                               step="0.0001" min="-180" max="180"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="e.g., -74.0060">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_available" id="is_available" value="1" 
                               {{ old('is_available', $vehicle->is_available) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_available" class="ml-2 block text-sm text-gray-900">
                            Available for trips
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.vehicles.show', $vehicle) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-save mr-2"></i>Update Vehicle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 