<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTripRequest;
use App\Http\Requests\Admin\UpdateTripRequest;
use App\Models\Trip;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Enums\TripStatus;
use Illuminate\Http\Request;

class TripsController extends Controller
{
    public function index()
    {
        $trips = Trip::with(['user', 'driver', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.dashboard.trips', compact('trips'));
    }

    public function create()
    {
        $drivers = Driver::all();
        $vehicles = Vehicle::all();
        $users = \App\Models\User::all();
        $statuses = TripStatus::cases();
        
        return view('admin.dashboard.trips.create', compact('drivers', 'vehicles', 'users', 'statuses'));
    }

    public function store(StoreTripRequest $request)
    {
        $trip = Trip::create([
            'user_id' => $request->user_id,
            'driver_id' => $request->driver_id,
            'vehicle_id' => $request->vehicle_id,
            'pickup_location' => $request->pickup_location,
            'dropoff_location' => $request->dropoff_location,
            'origin_latitude' => $request->pickup_latitude,
            'origin_longitude' => $request->pickup_longitude,
            'destination_latitude' => $request->dropoff_latitude,
            'destination_longitude' => $request->dropoff_longitude,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'fare' => $request->fare,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.trips.index')
            ->with('success', 'Trip created successfully.');
    }

    public function show(Trip $trip)
    {
        $trip->load(['user', 'driver', 'vehicle']);
        return view('admin.dashboard.trips.show', compact('trip'));
    }

    public function edit(Trip $trip)
    {
        $drivers = Driver::all();
        $vehicles = Vehicle::all();
        $statuses = TripStatus::cases();
        
        return view('admin.dashboard.trips.edit', compact('trip', 'drivers', 'vehicles', 'statuses'));
    }

    public function update(UpdateTripRequest $request, Trip $trip)
    {
        $trip->update($request->all());

        return redirect()->route('admin.trips.index')
            ->with('success', 'Trip updated successfully.');
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();
        return redirect()->route('admin.trips.index')
            ->with('success', 'Trip deleted successfully.');
    }
} 