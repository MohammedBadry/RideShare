<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDriverRequest;
use App\Http\Requests\Admin\UpdateDriverRequest;
use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriversController extends Controller
{
    public function index()
    {
        $drivers = Driver::with(['user', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.dashboard.drivers', compact('drivers'));
    }

    public function create()
    {
        $vehicles = Vehicle::where('is_available', true)->get();
        return view('admin.dashboard.drivers.create', compact('vehicles'));
    }

    public function store(StoreDriverRequest $request)
    {
        // Create user account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make('password123'), // Default password
        ]);

        // Create driver record
        $driver = Driver::create([
            'user_id' => $user->id,
            'license_number' => $request->license_number,
            'experience_years' => $request->experience_years,
            'vehicle_id' => $request->vehicle_id,
            'is_available' => $request->boolean('is_available', true),
        ]);

        // Update vehicle availability if assigned
        if ($request->vehicle_id) {
            Vehicle::where('id', $request->vehicle_id)->update(['is_available' => false]);
        }

        return redirect()->route('admin.drivers.index')->with('success', 'Driver created successfully!');
    }

    public function edit(Driver $driver)
    {
        $vehicles = Vehicle::where('is_available', true)
            ->orWhere('id', $driver->vehicle_id)
            ->get();
        return view('admin.dashboard.drivers.edit', compact('driver', 'vehicles'));
    }

    public function update(UpdateDriverRequest $request, Driver $driver)
    {
        // Update user account
        $driver->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Handle vehicle assignment
        $oldVehicleId = $driver->vehicle_id;
        $newVehicleId = $request->vehicle_id;

        // Update driver record
        $driver->update([
            'license_number' => $request->license_number,
            'experience_years' => $request->experience_years,
            'vehicle_id' => $newVehicleId,
            'is_available' => $request->boolean('is_available', true),
        ]);

        // Update vehicle availability
        if ($oldVehicleId && $oldVehicleId != $newVehicleId) {
            Vehicle::where('id', $oldVehicleId)->update(['is_available' => true]);
        }
        if ($newVehicleId && $newVehicleId != $oldVehicleId) {
            Vehicle::where('id', $newVehicleId)->update(['is_available' => false]);
        }

        return redirect()->route('admin.drivers.index')->with('success', 'Driver updated successfully!');
    }

    public function destroy(Driver $driver)
    {
        // Free up vehicle if assigned
        if ($driver->vehicle_id) {
            Vehicle::where('id', $driver->vehicle_id)->update(['is_available' => true]);
        }

        // Delete user account
        $driver->user->delete();

        // Delete driver record
        $driver->delete();

        return redirect()->route('admin.drivers.index')->with('success', 'Driver deleted successfully!');
    }

    public function show(Driver $driver)
    {
        $driver->load(['vehicle', 'trips']);
        $vehicles = Vehicle::all();
        return view('admin.dashboard.drivers.show', compact('driver', 'vehicles'));
    }
}
