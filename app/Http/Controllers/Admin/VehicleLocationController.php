<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateVehicleLocationRequest;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;

class VehicleLocationController extends Controller
{
    public function update(UpdateVehicleLocationRequest $request, $vehicleId): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        
        // Update vehicle location
        $vehicle->update([
            'current_latitude' => $request->latitude,
            'current_longitude' => $request->longitude,
        ]);

        return response()->json([
            'message' => 'Vehicle location updated successfully',
            'vehicle_id' => $vehicleId,
            'location' => [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]
        ]);
    }

    public function getLocation($vehicleId): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        
        return response()->json([
            'vehicle_id' => $vehicleId,
            'location' => [
                'latitude' => $vehicle->current_latitude,
                'longitude' => $vehicle->current_longitude,
            ]
        ]);
    }
}
