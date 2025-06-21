@extends('layouts.dashboard')

@section('title', 'Vehicles - RideShare Dashboard')
@section('page-title', 'Vehicles Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Vehicles</h1>
            <p class="text-gray-600">Manage fleet vehicles and their status</p>
        </div>
        <a href="{{ route('admin.vehicles.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add Vehicle
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card bg-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-car text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Vehicles</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $vehicles->total() }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Available</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $vehicles->where('is_available', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">In Use</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $vehicles->where('is_available', false)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-tools text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Maintenance</p>
                    <p class="text-2xl font-semibold text-gray-900">3</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card bg-white rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="1">Available</option>
                    <option value="0">In Use</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    <option value="sedan">Sedan</option>
                    <option value="suv">SUV</option>
                    <option value="luxury">Luxury</option>
                    <option value="van">Van</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Years</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                    <option value="2021">2021</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" placeholder="Search vehicles..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <!-- Vehicles Table -->
    <div class="card bg-white rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trips</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($vehicles as $vehicle)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-car text-gray-500 text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $vehicle->model ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $vehicle->plate_number ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $vehicle->year ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($vehicle->driver)
                            <div class="flex items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($vehicle->driver->user->name ?? 'Driver') }}&background=fa709a&color=fff" alt="Driver" class="w-8 h-8 rounded-full mr-3">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $vehicle->driver->user->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $vehicle->driver->license_number ?? 'N/A' }}</div>
                                </div>
                            </div>
                            @else
                            <span class="text-sm text-gray-500">No driver assigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($vehicle->type?->value === 'luxury') bg-purple-100 text-purple-800
                                @elseif($vehicle->type?->value === 'suv') bg-blue-100 text-blue-800
                                @elseif($vehicle->type?->value === 'van') bg-orange-100 text-orange-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($vehicle->type?->value ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($vehicle->is_available) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                @if($vehicle->is_available)
                                    <i class="fas fa-circle text-green-500 mr-1"></i> Available
                                @else
                                    <i class="fas fa-circle text-red-500 mr-1"></i> In Use
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($vehicle->latitude && $vehicle->longitude)
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                <span>{{ number_format($vehicle->latitude, 4) }}, {{ number_format($vehicle->longitude, 4) }}</span>
                            </div>
                            @else
                            <span class="text-gray-500">Location unavailable</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="text-center">
                                <div class="font-semibold">{{ $vehicle->trips_count ?? 0 }}</div>
                                <div class="text-xs text-gray-500">completed</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $vehicle->updated_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.vehicles.show', $vehicle) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.vehicles.edit', $vehicle) }}" 
                                   class="text-green-600 hover:text-green-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($vehicle->latitude && $vehicle->longitude)
                                <a href="https://maps.google.com/?q={{ $vehicle->latitude }},{{ $vehicle->longitude }}" 
                                   target="_blank" 
                                   class="text-yellow-600 hover:text-yellow-900" title="View on Map">
                                    <i class="fas fa-map-marker-alt"></i>
                                </a>
                                @endif
                                <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this vehicle?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                            <div class="flex flex-col items-center py-8">
                                <i class="fas fa-car text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-900 mb-2">No vehicles found</p>
                                <p class="text-gray-500">Get started by adding your first vehicle</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($vehicles->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    @if($vehicles->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 cursor-not-allowed opacity-50">
                            Previous
                        </span>
                    @else
                        <a href="{{ $vehicles->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </a>
                    @endif

                    @if($vehicles->hasMorePages())
                        <a href="{{ $vehicles->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </a>
                    @else
                        <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 cursor-not-allowed opacity-50">
                            Next
                        </span>
                    @endif
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ $vehicles->firstItem() }}</span> to <span class="font-medium">{{ $vehicles->lastItem() }}</span> of <span class="font-medium">{{ $vehicles->total() }}</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                            {{ $vehicles->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Location Preview Modal -->
<div id="locationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Vehicle Location</h3>
                <button onclick="closeLocationModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="locationContent" class="text-center">
                <!-- Location content will be loaded here -->
            </div>
            <div class="flex justify-end mt-4">
                <button onclick="closeLocationModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showLocationModal(vehicleId, latitude, longitude, vehicleName) {
    const modal = document.getElementById('locationModal');
    const content = document.getElementById('locationContent');
    
    content.innerHTML = `
        <div class="mb-4">
            <h4 class="font-semibold text-gray-900">${vehicleName}</h4>
            <p class="text-sm text-gray-600">Coordinates: ${latitude}, ${longitude}</p>
        </div>
        <div class="bg-gray-100 rounded-lg p-4 mb-4">
            <div class="text-center">
                <i class="fas fa-map text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-600">Interactive map would be displayed here</p>
            </div>
        </div>
        <a href="https://maps.google.com/?q=${latitude},${longitude}" 
           target="_blank" 
           class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-external-link-alt mr-2"></i>
            Open in Google Maps
        </a>
    `;
    
    modal.classList.remove('hidden');
}

function closeLocationModal() {
    document.getElementById('locationModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('locationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLocationModal();
    }
});
</script>
@endsection 