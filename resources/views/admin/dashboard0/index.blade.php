@extends('layouts.admin')

@section('title', 'Dashboard - RideShare Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Message -->
    <div class="bg-white rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome back, {{ auth('admin')->user()->name }}!</h2>
        <p class="text-gray-600">Here's what's happening with your RideShare platform today.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card bg-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-route text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Trips</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_trips'] }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-play-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Trips</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_trips'] }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-user-tie text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Drivers</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_drivers'] }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-car text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Vehicles</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_vehicles'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Trips Chart -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Trips</h3>
            <canvas id="monthlyTripsChart" width="400" height="300"></canvas>
        </div>

        <!-- Quick Stats -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Stats</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Available Drivers</p>
                            <p class="text-xs text-gray-500">Ready for trips</p>
                        </div>
                    </div>
                    <span class="text-lg font-semibold text-gray-900">{{ $stats['available_drivers'] }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                            <i class="fas fa-car"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Available Vehicles</p>
                            <p class="text-xs text-gray-500">Ready for service</p>
                        </div>
                    </div>
                    <span class="text-lg font-semibold text-gray-900">{{ $stats['available_vehicles'] }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-purple-100 text-purple-600 mr-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Total Users</p>
                            <p class="text-xs text-gray-500">Registered customers</p>
                        </div>
                    </div>
                    <span class="text-lg font-semibold text-gray-900">{{ $stats['total_users'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Trips -->
    <div class="card bg-white rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Recent Trips</h3>
            <a href="{{ route('admin.trips.index') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium">View All</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trip ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fare</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recent_trips as $trip)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $trip->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($trip->user->name ?? 'User') }}&background=667eea&color=fff" alt="User" class="w-8 h-8 rounded-full mr-3">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $trip->user->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $trip->user->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($trip->driver->user->name ?? 'Driver') }}&background=fa709a&color=fff" alt="Driver" class="w-8 h-8 rounded-full mr-3">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $trip->driver->user->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $trip->driver->license_number ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($trip->status?->value === 'completed') bg-green-100 text-green-800
                                @elseif($trip->status?->value === 'in_progress') bg-blue-100 text-blue-800
                                @elseif($trip->status?->value === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($trip->status?->value === 'accepted') bg-indigo-100 text-indigo-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $trip->status?->value ?? 'N/A')) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ number_format($trip->fare, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $trip->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            <div class="flex flex-col items-center py-8">
                                <i class="fas fa-route text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-900 mb-2">No trips found</p>
                                <p class="text-gray-500">Trips will appear here once they are created</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Monthly Trips Chart
const monthlyTripsCtx = document.getElementById('monthlyTripsChart').getContext('2d');
const monthlyTripsChart = new Chart(monthlyTripsCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthly_trips->pluck('month')->map(function($month) {
            return date('F', mktime(0, 0, 0, $month, 1));
        })) !!},
        datasets: [{
            label: 'Trips',
            data: {!! json_encode($monthly_trips->pluck('count')) !!},
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>
@endsection 