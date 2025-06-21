@extends('layouts.dashboard')

@section('title', 'Driver Details - RideShare')
@section('page-title', 'Driver Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $driver->name }}</h1>
            <p class="text-gray-600">Driver details and information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.drivers.edit', $driver) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Edit Driver
            </a>
            <form action="{{ route('admin.drivers.destroy', $driver) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this driver?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Driver
                </button>
            </form>
        </div>
    </div>

    <!-- Driver Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Name:</span>
                    <span class="font-medium">{{ $driver->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium">{{ $driver->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Phone:</span>
                    <span class="font-medium">{{ $driver->phone }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">License Number:</span>
                    <span class="font-medium">{{ $driver->license_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Experience Years:</span>
                    <span class="font-medium">{{ $driver->experience_years ?? 'N/A' }} years</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="px-2 py-1 rounded-full text-sm font-medium
                        @if($driver->is_available) bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $driver->is_available ? 'Available' : 'Unavailable' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Created:</span>
                    <span class="font-medium">{{ $driver->created_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Vehicle Information -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Assigned Vehicle</h3>
            @if($driver->vehicle)
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Model:</span>
                    <span class="font-medium">{{ $driver->vehicle->model }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Plate Number:</span>
                    <span class="font-medium">{{ $driver->vehicle->plate_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Year:</span>
                    <span class="font-medium">{{ $driver->vehicle->year }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Color:</span>
                    <span class="font-medium">{{ $driver->vehicle->color }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Type:</span>
                    <span class="font-medium">{{ ucfirst($driver->vehicle->type->value) }}</span>
                </div>
                @if($driver->vehicle->current_latitude && $driver->vehicle->current_longitude)
                <div class="mt-4">
                    <a href="https://maps.google.com/?q={{ $driver->vehicle->current_latitude }},{{ $driver->vehicle->current_longitude }}" target="_blank" class="text-blue-500 hover:text-blue-600 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        View Current Location
                    </a>
                </div>
                @endif
            </div>
            @else
            <p class="text-gray-500">No vehicle assigned</p>
            @endif
        </div>
    </div>

    <!-- Trip History -->
    <div class="card bg-white rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Trips</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trip ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pickup</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dropoff</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fare</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($driver->trips()->with('user')->latest()->take(5)->get() as $trip)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $trip->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ Str::limit($trip->pickup_location, 30) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ Str::limit($trip->dropoff_location, 30) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($trip->status->value === 'completed') bg-green-100 text-green-800
                                @elseif($trip->status->value === 'in_progress') bg-blue-100 text-blue-800
                                @elseif($trip->status->value === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($trip->status->value === 'accepted') bg-purple-100 text-purple-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $trip->status->value)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ number_format($trip->fare, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $trip->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No trips found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-start">
        <a href="{{ route('admin.drivers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Drivers
        </a>
    </div>
</div>
@endsection 