<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\User;
use App\Enums\TripStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $stats = [
            'total_trips' => Trip::count(),
            'active_trips' => Trip::where('status', TripStatus::IN_PROGRESS)->count(),
            'total_drivers' => Driver::count(),
            'available_drivers' => Driver::where('is_available', true)->count(),
            'total_vehicles' => Vehicle::count(),
            'available_vehicles' => Vehicle::where('is_available', true)->count(),
            'total_users' => User::count(),
        ];

        // Get recent trips
        $recent_trips = Trip::with(['user', 'driver', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get monthly trip statistics (safe, always 12 months)
        $raw_monthly = Trip::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $monthly_trips = array_fill(1, 12, 0);
        foreach ($raw_monthly as $row) {
            $monthly_trips[(int)$row->month] = (int)$row->count;
        }
        // Re-index to 0-based for JS
        $monthly_trips = array_values($monthly_trips);

        return view('admin.dashboard.index', compact('stats', 'recent_trips', 'monthly_trips'));
    }
}
