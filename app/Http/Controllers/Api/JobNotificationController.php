<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\JobNotificationRequest;
use App\Models\Trip;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Enums\TripStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class JobNotificationController extends Controller
{
    /**
     * Get available jobs for a driver
     */
    public function getAvailableJobs($driverId): JsonResponse
    {
        $driver = Driver::findOrFail($driverId);
        
        if (!$driver->is_available) {
            return response()->json([
                'message' => 'Driver is not available for new jobs',
                'jobs' => []
            ]);
        }

        // Get pending trips that match driver's vehicle type and location
        $jobs = Trip::where('status', TripStatus::PENDING)
            ->whereNull('driver_id') // Not assigned yet
            ->whereHas('vehicle', function($query) use ($driver) {
                $query->where('type', $driver->vehicle->type ?? 'sedan');
            })
            ->with(['user', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'driver_id' => $driverId,
            'jobs' => $jobs,
            'total_jobs' => $jobs->count()
        ]);
    }

    /**
     * Driver accepts a job
     */
    public function acceptJob(JobNotificationRequest $request, $driverId): JsonResponse
    {
        $driver = Driver::findOrFail($driverId);
        $tripId = $request->trip_id;

        // Check if driver is available
        if (!$driver->is_available) {
            return response()->json([
                'message' => 'Driver is not available for new jobs'
            ], 400);
        }

        // Check if trip is still available
        $trip = Trip::where('id', $tripId)
            ->where('status', TripStatus::PENDING)
            ->whereNull('driver_id')
            ->first();

        if (!$trip) {
            return response()->json([
                'message' => 'Trip is no longer available'
            ], 400);
        }

        // Assign driver to trip
        $trip->update([
            'driver_id' => $driverId,
            'vehicle_id' => $driver->vehicle_id,
            'status' => TripStatus::ACCEPTED
        ]);

        // Update driver availability
        $driver->update(['is_available' => false]);

        // Update vehicle availability
        if ($driver->vehicle_id) {
            Vehicle::where('id', $driver->vehicle_id)->update(['is_available' => false]);
        }

        // Clear relevant caches
        Cache::forget("driver:{$driverId}:active_trips");
        Cache::forget("available_drivers:{$trip->origin_latitude}:{$trip->origin_longitude}");

        return response()->json([
            'message' => 'Job accepted successfully',
            'trip' => $trip->load(['user', 'vehicle'])
        ]);
    }

    /**
     * Driver rejects a job
     */
    public function rejectJob($driverId, $tripId): JsonResponse
    {
        $driver = Driver::findOrFail($driverId);
        
        // Log rejection for analytics (optional)
        // This could be used to improve job matching algorithms
        
        return response()->json([
            'message' => 'Job rejected successfully'
        ]);
    }

    /**
     * Get driver's current active job
     */
    public function getCurrentJob($driverId): JsonResponse
    {
        $driver = Driver::findOrFail($driverId);
        
        $activeTrip = Trip::where('driver_id', $driverId)
            ->whereIn('status', [TripStatus::ACCEPTED, TripStatus::IN_PROGRESS])
            ->with(['user', 'vehicle'])
            ->first();

        return response()->json([
            'driver_id' => $driverId,
            'current_job' => $activeTrip,
            'is_available' => $driver->is_available
        ]);
    }

    /**
     * Driver completes a job
     */
    public function completeJob($driverId, $tripId): JsonResponse
    {
        $driver = Driver::findOrFail($driverId);
        $trip = Trip::where('id', $tripId)
            ->where('driver_id', $driverId)
            ->first();

        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found or not assigned to driver'
            ], 404);
        }

        // Update trip status
        $trip->update([
            'status' => TripStatus::COMPLETED,
            'end_time' => now()
        ]);

        // Make driver available again
        $driver->update(['is_available' => true]);

        // Make vehicle available again
        if ($driver->vehicle_id) {
            Vehicle::where('id', $driver->vehicle_id)->update(['is_available' => true]);
        }

        // Clear caches
        Cache::forget("driver:{$driverId}:active_trips");

        return response()->json([
            'message' => 'Job completed successfully',
            'trip' => $trip->load(['user', 'vehicle'])
        ]);
    }
} 