@extends('layouts.dashboard')

@section('title', 'Trip Details - RideShare')
@section('page-title', 'Trip Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Trip #{{ $trip->id }}</h1>
            <p class="text-gray-600">Trip details and information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.trips.edit', $trip) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Edit Trip
            </a>
            <form action="{{ route('admin.trips.destroy', $trip) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this trip?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Trip
                </button>
            </form>
        </div>
    </div>

    <!-- Trip Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Trip ID:</span>
                    <span class="font-medium">#{{ $trip->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="px-2 py-1 rounded-full text-sm font-medium
                        @if($trip->status->value === 'completed') bg-green-100 text-green-800
                        @elseif($trip->status->value === 'in_progress') bg-blue-100 text-blue-800
                        @elseif($trip->status->value === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($trip->status->value === 'accepted') bg-purple-100 text-purple-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $trip->status->value)) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Fare:</span>
                    <span class="font-medium text-green-600">${{ number_format($trip->fare, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Created:</span>
                    <span class="font-medium">{{ $trip->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Updated:</span>
                    <span class="font-medium">{{ $trip->updated_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">User Information</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">User:</span>
                    <span class="font-medium">{{ $trip->user->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium">{{ $trip->user->email ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Phone:</span>
                    <span class="font-medium">{{ $trip->user->phone ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pickup Location -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pickup Location</h3>
            <div class="space-y-4">
                <div>
                    <span class="text-gray-600">Address:</span>
                    <p class="font-medium">{{ $trip->pickup_location }}</p>
                </div>
                @if($trip->pickup_latitude && $trip->pickup_longitude)
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-600">Latitude:</span>
                        <p class="font-medium">{{ $trip->pickup_latitude }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Longitude:</span>
                        <p class="font-medium">{{ $trip->pickup_longitude }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="https://maps.google.com/?q={{ $trip->pickup_latitude }},{{ $trip->pickup_longitude }}" target="_blank" class="text-blue-500 hover:text-blue-600 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        View on Map
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Dropoff Location -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Dropoff Location</h3>
            <div class="space-y-4">
                <div>
                    <span class="text-gray-600">Address:</span>
                    <p class="font-medium">{{ $trip->dropoff_location }}</p>
                </div>
                @if($trip->dropoff_latitude && $trip->dropoff_longitude)
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-600">Latitude:</span>
                        <p class="font-medium">{{ $trip->dropoff_latitude }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Longitude:</span>
                        <p class="font-medium">{{ $trip->dropoff_longitude }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="https://maps.google.com/?q={{ $trip->dropoff_latitude }},{{ $trip->dropoff_longitude }}" target="_blank" class="text-blue-500 hover:text-blue-600 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        View on Map
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Driver and Vehicle Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Driver Information -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Driver Information</h3>
            @if($trip->driver)
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Name:</span>
                    <span class="font-medium">{{ $trip->driver->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium">{{ $trip->driver->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Phone:</span>
                    <span class="font-medium">{{ $trip->driver->phone }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Experience:</span>
                    <span class="font-medium">{{ $trip->driver->experience_years ?? 'N/A' }} years</span>
                </div>
            </div>
            @else
            <p class="text-gray-500">No driver assigned</p>
            @endif
        </div>

        <!-- Vehicle Information -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Vehicle Information</h3>
            @if($trip->vehicle)
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Model:</span>
                    <span class="font-medium">{{ $trip->vehicle->model }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Plate Number:</span>
                    <span class="font-medium">{{ $trip->vehicle->plate_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Year:</span>
                    <span class="font-medium">{{ $trip->vehicle->year }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Color:</span>
                    <span class="font-medium">{{ $trip->vehicle->color }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Type:</span>
                    <span class="font-medium">{{ ucfirst($trip->vehicle->type->value) }}</span>
                </div>
            </div>
            @else
            <p class="text-gray-500">No vehicle assigned</p>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-start">
        <a href="{{ route('admin.trips.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Trips
        </a>
    </div>
</div>
@endsection 