<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - RideShare')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('styles')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out" id="sidebar">
            <div class="flex items-center space-x-2 px-4">
                <div class="flex items-center">
                    <i class="fas fa-car text-blue-400 text-2xl mr-2"></i>
                    <span class="text-2xl font-extrabold">RideShare</span>
                </div>
            </div>
            
            <nav class="space-y-2 px-4">
                <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Dashboard
                </a>
                
                @if(auth('admin')->user()->hasPermission('view_trips'))
                <a href="{{ route('admin.trips.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.trips.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-route mr-2"></i>
                    Trips
                </a>
                @endif
                
                @if(auth('admin')->user()->hasPermission('view_drivers'))
                <a href="{{ route('admin.drivers.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.drivers.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-user-tie mr-2"></i>
                    Drivers
                </a>
                @endif
                
                @if(auth('admin')->user()->hasPermission('view_vehicles'))
                <a href="{{ route('admin.vehicles.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.vehicles.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-car mr-2"></i>
                    Vehicles
                </a>
                @endif
                
                @if(auth('admin')->user()->hasPermission('view_analytics'))
                <a href="{{ route('admin.analytics.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.analytics.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Analytics
                </a>
                @endif
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-6">
                    <div class="flex items-center">
                        <button class="md:hidden rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" id="sidebar-toggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-900 ml-4">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth('admin')->user()->name) }}&background=667eea&color=fff" alt="Admin" class="w-8 h-8 rounded-full">
                            <div class="hidden md:block">
                                <div class="text-sm font-medium text-gray-900">{{ auth('admin')->user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ ucfirst(auth('admin')->user()->role) }}</div>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        });
    </script>
    
    @yield('scripts')
</body>
</html> 