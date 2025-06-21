<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RideShare - Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <i class="fas fa-car text-blue-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">RideShare</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Admin Login
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6">
                        Welcome to RideShare
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-blue-100">
                        Your modern ride-sharing platform
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('admin.login') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Admin Dashboardnd
                        </a>
                        <a href="/api/documentation" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                            <i class="fas fa-book mr-2"></i>
                            API Documentation
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Platform Features</h2>
                    <p class="text-lg text-gray-600">Comprehensive ride-sharing management system</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-route text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Trip Management</h3>
                        <p class="text-gray-600">Efficiently manage and track all trips in real-time</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user-tie text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Driver Management</h3>
                        <p class="text-gray-600">Manage drivers, their availability, and performance</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-bar text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Analytics & Reports</h3>
                        <p class="text-gray-600">Comprehensive analytics and reporting tools</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Access Section -->
        <div class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Admin Access</h2>
                    <p class="text-lg text-gray-600 mb-8">Access the administration panel to manage your platform</p>
                    
                    <div class="bg-white rounded-lg shadow-md p-8 max-w-md mx-auto">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Login Credentials</h3>
                        <div class="space-y-3 text-left">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Super Admin:</span>
                                <span class="font-medium">admin@rideshare.com</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Manager:</span>
                                <span class="font-medium">manager@rideshare.com</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Viewer:</span>
                                <span class="font-medium">viewer@rideshare.com</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Password:</span>
                                <span class="font-medium">password</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('admin.login') }}" class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold inline-block text-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Go to Admin Panel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p>&copy; 2024 RideShare. All rights reserved.</p>
            </div>
        </footer>
    </div>
    </body>
</html>
