<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreTripRequest;
use App\Models\Trip;
use App\Enums\TripStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TripController extends Controller
{
    public function store(StoreTripRequest $request)
    {
        $trip = Trip::create($request->validated());

        // Invalidate driver's active trips cache
        Cache::forget("driver:{$trip->driver_id}:active_trips");

        return response()->json($trip, 201);
    }

    public function userHistory(Request $request)
    {
        $trips = Trip::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($trips);
    }

    public function driverActiveTrips($driverId)
    {
        $trips = Cache::remember("driver:{$driverId}:active_trips", 30, function() use ($driverId) {
            return Trip::where('driver_id', $driverId)
                ->where('status', TripStatus::IN_PROGRESS)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
        });

        return response()->json($trips);
    }

    public function updateStatus(Request $request, $tripId)
    {
        $trip = Trip::findOrFail($tripId);
        $trip->update(['status' => $request->status]);

        // Invalidate driver's active trips cache
        Cache::forget("driver:{$trip->driver_id}:active_trips");

        return response()->json($trip);
    }
}
