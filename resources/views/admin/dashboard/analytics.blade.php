@extends('layouts.dashboard')

@section('title', 'Analytics - RideShare Dashboard')
@section('page-title', 'Analytics & Reports')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Analytics</h1>
            <p class="text-gray-600">Comprehensive insights and performance metrics</p>
        </div>
        <div class="flex space-x-3">
            <select class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="7">Last 7 days</option>
                <option value="30">Last 30 days</option>
                <option value="90">Last 90 days</option>
                <option value="365">Last year</option>
            </select>
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-download mr-2"></i>
                Export Report
            </button>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card bg-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($monthly_revenue->sum('total_revenue'), 2) }}</p>
                    <p class="text-sm text-green-600">+12.5% from last month</p>
                </div>
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card bg-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Trips</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $trip_status_stats->sum('count') }}</p>
                    <p class="text-sm text-blue-600">+8.2% from last month</p>
                </div>
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-route text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card bg-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                    <p class="text-2xl font-bold text-gray-900">94.2%</p>
                    <p class="text-sm text-green-600">+2.1% from last month</p>
                </div>
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card bg-white rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Average Rating</p>
                    <p class="text-2xl font-bold text-gray-900">4.6</p>
                    <p class="text-sm text-green-600">+0.2 from last month</p>
                </div>
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-star text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Trip Status Distribution -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Trip Status Distribution</h3>
            <div style="height: 300px;">
                <canvas id="tripStatusChart"></canvas>
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Revenue</h3>
            <div style="height: 300px;">
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Peak Hours -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Peak Hours</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Morning (7-9 AM)</span>
                    <span class="text-sm font-medium text-gray-900">45%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 45%"></div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Evening (5-7 PM)</span>
                    <span class="text-sm font-medium text-gray-900">38%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: 38%"></div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Night (9-11 PM)</span>
                    <span class="text-sm font-medium text-gray-900">17%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: 17%"></div>
                </div>
            </div>
        </div>

        <!-- Popular Routes -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Popular Routes</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <div class="text-sm font-medium text-gray-900">Downtown → Airport</div>
                        <div class="text-xs text-gray-500">Most frequent route</div>
                    </div>
                    <span class="text-sm font-medium text-blue-600">156 trips</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <div class="text-sm font-medium text-gray-900">Mall → University</div>
                        <div class="text-xs text-gray-500">Student route</div>
                    </div>
                    <span class="text-sm font-medium text-blue-600">89 trips</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <div class="text-sm font-medium text-gray-900">Hospital → Downtown</div>
                        <div class="text-xs text-gray-500">Medical route</div>
                    </div>
                    <span class="text-sm font-medium text-blue-600">67 trips</span>
                </div>
            </div>
        </div>

        <!-- Vehicle Utilization -->
        <div class="card bg-white rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Vehicle Utilization</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Sedan</span>
                    <span class="text-sm font-medium text-gray-900">78%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 78%"></div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">SUV</span>
                    <span class="text-sm font-medium text-gray-900">65%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: 65%"></div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Luxury</span>
                    <span class="text-sm font-medium text-gray-900">92%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: 92%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Trip Status Distribution Chart
const tripStatusCtx = document.getElementById('tripStatusChart').getContext('2d');
const tripStatusChart = new Chart(tripStatusCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($trip_status_stats->pluck('status')->map(function($status) {
            $statusValue = is_object($status) && property_exists($status, 'value') ? $status->value : $status;
            return ucfirst(str_replace('_', ' ', $statusValue));
        })) !!},
        datasets: [{
            data: {!! json_encode($trip_status_stats->pluck('count')) !!},
            backgroundColor: [
                '#10B981', // Green for completed
                '#3B82F6', // Blue for in progress
                '#F59E0B', // Yellow for pending
                '#8B5CF6', // Purple for accepted
                '#EF4444'  // Red for cancelled
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Monthly Revenue Chart
const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
const monthlyRevenueChart = new Chart(monthlyRevenueCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($monthly_revenue->pluck('month')->map(function($month) {
            return date('F', mktime(0, 0, 0, $month, 1));
        })) !!},
        datasets: [{
            label: 'Revenue ($)',
            data: {!! json_encode($monthly_revenue->pluck('total_revenue')) !!},
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: '#3B82F6',
            borderWidth: 1
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