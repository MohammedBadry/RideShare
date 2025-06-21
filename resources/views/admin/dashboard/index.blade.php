@extends('layouts.dashboard')

@section('title', 'Dashboard - RideShare')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card card rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Trips</p>
                    <p class="text-3xl font-bold">{{ $stats['total_trips'] }}</p>
                </div>
                <div class="text-3xl opacity-80">
                    <i class="fas fa-route"></i>
                </div>
            </div>
        </div>

        <div class="stat-card-green card rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Active Trips</p>
                    <p class="text-3xl font-bold">{{ $stats['active_trips'] }}</p>
                </div>
                <div class="text-3xl opacity-80">
                    <i class="fas fa-play-circle"></i>
                </div>
            </div>
        </div>

        <div class="stat-card-orange card rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Available Drivers</p>
                    <p class="text-3xl font-bold">{{ $stats['available_drivers'] }}</p>
                </div>
                <div class="text-3xl opacity-80">
                    <i class="fas fa-user-tie"></i>
                </div>
            </div>
        </div>

        <div class="stat-card-purple card rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Available Vehicles</p>
                    <p class="text-3xl font-bold">{{ $stats['available_vehicles'] }}</p>
                </div>
                <div class="text-3xl opacity-80">
                    <i class="fas fa-car"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Trips -->
        <div class="card bg-white rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Trips</h3>
                <a href="{{ route('admin.trips.index') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium">View All</a>
            </div>
            
            <div class="space-y-4">
                @forelse($recent_trips as $trip)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-route text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $trip->user->name ?? 'Unknown User' }}</p>
                            <p class="text-sm text-gray-600">{{ $trip->pickup_location }} → {{ $trip->dropoff_location }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-800">${{ number_format($trip->fare, 2) }}</p>
                        <p class="text-sm text-gray-600">{{ $trip->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-route text-4xl mb-4"></i>
                    <p>No recent trips</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Monthly Trip Chart -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Trip Statistics</h3>
            <div style="height: 300px;">
                <canvas id="monthlyTripsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card bg-white rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.trips.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-route text-blue-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-gray-800">Manage Trips</p>
                    <p class="text-sm text-gray-600">View and manage all trips</p>
                </div>
            </a>
            
            <a href="{{ route('admin.drivers.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-user-tie text-green-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-gray-800">Manage Drivers</p>
                    <p class="text-sm text-gray-600">View and manage drivers</p>
                </div>
            </a>
            
            <a href="{{ route('admin.vehicles.index') }}" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <i class="fas fa-car text-orange-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-gray-800">Manage Vehicles</p>
                    <p class="text-sm text-gray-600">View and manage vehicles</p>
                </div>
            </a>
            
            <a href="{{ route('admin.analytics.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-chart-bar text-purple-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-gray-800">View Analytics</p>
                    <p class="text-sm text-gray-600">Detailed reports and insights</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Monthly Trips Chart
const monthlyTripsCtx = document.getElementById('monthlyTripsChart').getContext('2d');
const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
const tripCounts = @json($monthly_trips);

new Chart(monthlyTripsCtx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Trips',
            data: tripCounts,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true
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