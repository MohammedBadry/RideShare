<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Enums\TripStatus;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Monthly revenue for the current year (one value per month)
        $monthly_revenue = Trip::selectRaw('MONTH(created_at) as month, COALESCE(SUM(fare),0) as total_revenue')
            ->whereYear('created_at', date('Y'))
            ->where('status', TripStatus::COMPLETED->value)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Trip status distribution (count per status)
        $trip_status_stats = Trip::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return view('admin.dashboard.analytics', compact('monthly_revenue', 'trip_status_stats'));
    }
} 