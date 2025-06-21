@extends('layouts.dashboard')

@section('title', 'Vehicle Details - RideShare Dashboard')
@section('page-title', 'Vehicle Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Vehicle Details</h1>
            <p class="text-gray-600">Complete information about this vehicle</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                <i class="fas fa-edit mr-2"></i>
                Edit Vehicle
            </a>
            <a href="{{ route('admin.vehicles.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Vehicles
            </a>
        </div>
    </div>

    <!-- Vehicle Information Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Vehicle Info -->
        <div class="lg:col-span-2">
            <div class="card bg-white rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Vehicle Information</h2>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full 
                        @if($vehicle->is_available) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                        @if($vehicle->is_available)
                            <i class="fas fa-circle text-green-500 mr-1"></i> Available
                        @else
                            <i class="fas fa-circle text-red-500 mr-1"></i> In Use
                        @endif
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Model</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $vehicle->model ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Plate Number</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $vehicle->plate_number ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Year</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $vehicle->year ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Color</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $vehicle->color ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vehicle Type</label>
                            <span class="mt-1 inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                @if($vehicle->type?->value === 'luxury') bg-purple-100 text-purple-800
                                @elseif($vehicle->type?->value === 'suv') bg-blue-100 text-blue-800
                                @elseif($vehicle->type?->value === 'van') bg-orange-100 text-orange-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($vehicle->type?->value ?? 'N/A') }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Created</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $vehicle->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $vehicle->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total Trips</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $vehicle->trips->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Driver Information -->
        <div class="lg:col-span-1">
            <div class="card bg-white rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Assigned Driver</h3>
                
                @if($vehicle->driver)
                <div class="text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($vehicle->driver->user->name ?? 'Driver') }}&background=fa709a&color=fff&size=80" 
                         alt="Driver" class="w-20 h-20 rounded-full mx-auto mb-4">
                    
                    <h4 class="text-lg font-semibold text-gray-900">{{ $vehicle->driver->user->name ?? 'N/A' }}</h4>
                    <p class="text-sm text-gray-600">{{ $vehicle->driver->user->email ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ $vehicle->driver->user->phone ?? 'N/A' }}</p>
                    
                    <div class="mt-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">License:</span>
                            <span class="text-sm font-medium">{{ $vehicle->driver->license_number ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Experience:</span>
                            <span class="text-sm font-medium">{{ $vehicle->driver->experience_years ?? 'N/A' }} years</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Status:</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($vehicle->driver->is_available) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $vehicle->driver->is_available ? 'Available' : 'Busy' }}
                            </span>
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.drivers.show', $vehicle->driver) }}" 
                       class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        View Driver Details
                    </a>
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-user-slash text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 mb-4">No driver assigned</p>
                    <a href="{{ route('admin.vehicles.edit', $vehicle) }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        Assign Driver
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Location Information -->
    @if($vehicle->latitude && $vehicle->longitude)
    <div class="card bg-white rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Location</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="flex items-center mb-4">
                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                    <span class="text-lg font-semibold text-gray-900">GPS Coordinates</span>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Latitude:</span>
                        <span class="text-sm font-medium">{{ number_format($vehicle->latitude, 6) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Longitude:</span>
                        <span class="text-sm font-medium">{{ number_format($vehicle->longitude, 6) }}</span>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="https://maps.google.com/?q={{ $vehicle->latitude }},{{ $vehicle->longitude }}" 
                       target="_blank" 
                       class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        View on Google Maps
                    </a>
                </div>
            </div>
            
            <div class="bg-gray-100 rounded-lg p-4">
                <div class="text-center">
                    <i class="fas fa-map text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">Interactive map preview would be displayed here</p>
                    <p class="text-sm text-gray-500 mt-2">Coordinates: {{ $vehicle->latitude }}, {{ $vehicle->longitude }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Trips -->
    @if($vehicle->trips->count() > 0)
    <div class="card bg-white rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Trips</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trip ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fare</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($vehicle->trips->take(5) as $trip)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $trip->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $trip->user->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($trip->status->value === 'completed') bg-green-100 text-green-800
                                @elseif($trip->status->value === 'in_progress') bg-blue-100 text-blue-800
                                @elseif($trip->status->value === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $trip->status->value)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($trip->fare, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $trip->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($vehicle->trips->count() > 5)
        <div class="mt-4 text-center">
            <a href="#" class="text-blue-500 hover:text-blue-600 text-sm">
                View all {{ $vehicle->trips->count() }} trips →
            </a>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection 