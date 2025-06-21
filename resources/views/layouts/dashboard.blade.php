<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RideShare Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stat-card-green {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .stat-card-orange {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        .stat-card-purple {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-white w-64 shadow-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-car text-blue-500 mr-2"></i>
                    RideShare
                </h1>
            </div>
            
            <nav class="mt-6">
                <div class="px-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.trips.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.trips.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-route w-5 h-5 mr-3"></i>
                        Trips
                    </a>
                    
                    <a href="{{ route('admin.drivers.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.drivers.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-user-tie w-5 h-5 mr-3"></i>
                        Drivers
                    </a>
                    
                    <a href="{{ route('admin.vehicles.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.vehicles.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-car w-5 h-5 mr-3"></i>
                        Vehicles
                    </a>
                    
                    <a href="{{ route('admin.analytics.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.analytics.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                        Analytics
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <div class="flex items-center space-x-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth('admin')->user()->name) }}&background=667eea&color=fff" alt="Profile" class="w-8 h-8 rounded-full">
                            <div class="hidden md:block">
                                <div class="text-sm font-medium text-gray-900">{{ auth('admin')->user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ ucfirst(auth('admin')->user()->role) }}</div>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-gray-500 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html> 