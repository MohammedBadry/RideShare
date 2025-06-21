<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVehicleRequest;
use App\Http\Requests\Admin\UpdateVehicleRequest;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;

class VehiclesController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with(['driver'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.dashboard.vehicles', compact('vehicles'));
    }

    public function create()
    {
        $drivers = Driver::where('is_available', true)->get();
        return view('admin.dashboard.vehicles.create', compact('drivers'));
    }

    public function store(StoreVehicleRequest $request)
    {
        // Create vehicle
        $vehicle = Vehicle::create([
            'model' => $request->model,
            'plate_number' => $request->plate_number,
            'year' => $request->year,
            'type' => $request->type,
            'color' => $request->color,
            'driver_id' => $request->driver_id,
            'is_available' => $request->boolean('is_available', true),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Update driver availability if assigned
        if ($request->driver_id) {
            Driver::where('id', $request->driver_id)->update(['is_available' => false]);
        }

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle created successfully!');
    }

    public function edit(Vehicle $vehicle)
    {
        $drivers = Driver::where('is_available', true)
            ->orWhere('id', $vehicle->driver_id)
            ->get();
        return view('admin.dashboard.vehicles.edit', compact('vehicle', 'drivers'));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        // Handle driver assignment
        $oldDriverId = $vehicle->driver_id;
        $newDriverId = $request->driver_id;

        // Update vehicle
        $vehicle->update([
            'model' => $request->model,
            'plate_number' => $request->plate_number,
            'year' => $request->year,
            'type' => $request->type,
            'color' => $request->color,
            'driver_id' => $newDriverId,
            'is_available' => $request->boolean('is_available', true),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Update driver availability
        if ($oldDriverId && $oldDriverId != $newDriverId) {
            Driver::where('id', $oldDriverId)->update(['is_available' => true]);
        }
        if ($newDriverId && $newDriverId != $oldDriverId) {
            Driver::where('id', $newDriverId)->update(['is_available' => false]);
        }

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle updated successfully!');
    }

    public function destroy(Vehicle $vehicle)
    {
        // Free up driver if assigned
        if ($vehicle->driver_id) {
            Driver::where('id', $vehicle->driver_id)->update(['is_available' => true]);
        }

        // Delete vehicle
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle deleted successfully!');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['driver.user', 'trips']);
        return view('admin.dashboard.vehicles.show', compact('vehicle'));
    }
} 