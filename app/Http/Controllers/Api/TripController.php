<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTripRequest;
use App\Http\Requests\Api\UpdateTripStatusRequest;
use App\Http\Requests\Api\UserHistoryRequest;
use App\Http\Requests\Api\TripIndexRequest;
use App\Http\Resources\TripResource;
use App\Http\Resources\TripCollection;
use App\Models\Trip;
use App\Enums\TripStatus;
use App\Services\DriverSelectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TripController extends Controller
{
    protected $driverSelectionService;

    public function __construct(DriverSelectionService $driverSelectionService)
    {
        $this->driverSelectionService = $driverSelectionService;
    }

    public function store(StoreTripRequest $request)
    {
        // Create trip without driver assignment initially
        $tripData = $request->validated();
        $tripData['status'] = TripStatus::PENDING; // Start as pending

        $trip = Trip::create($tripData);

        // Attempt to auto-assign a driver
        $driverAssigned = $this->driverSelectionService->autoAssignDriver($trip);

        // Reload trip with relationships
        $trip->load(['user', 'driver', 'vehicle']);

        $response = [
            'trip' => new TripResource($trip),
            'driver_assigned' => $driverAssigned,
            'message' => $driverAssigned
                ? 'Trip created and driver assigned successfully'
                : 'Trip created successfully. Driver will be assigned shortly.'
        ];

        return response()->json($response, 201);
    }

    public function userHistory(UserHistoryRequest $request)
    {
        $trips = Trip::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return new TripCollection($trips);
    }

    public function driverActiveTrips($driverId)
    {
        $trips = Cache::remember("driver:{$driverId}:active_trips", 30, function() use ($driverId) {
            return Trip::where('driver_id', $driverId)
                ->whereIn('status', [TripStatus::ASSIGNED, TripStatus::IN_PROGRESS])
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
        });

        return new TripCollection($trips);
    }
    public function updateStatus(UpdateTripStatusRequest $request, $tripId)
    {
        $trip = Trip::findOrFail($tripId);
        $oldStatus = $trip->status;

        $trip->update(['status' => $request->status]);

        // Handle status-specific logic
        if ($request->status === TripStatus::COMPLETED->value) {
            // Make driver and vehicle available again
            if ($trip->driver_id) {
                \App\Models\Driver::where('id', $trip->driver_id)->update(['is_available' => true]);
            }
            if ($trip->vehicle_id) {
                \App\Models\Vehicle::where('id', $trip->vehicle_id)->update(['is_available' => true]);
            }
        }

        // Invalidate driver's active trips cache
        if ($trip->driver_id) {
            Cache::forget("driver:{$trip->driver_id}:active_trips");
        }

        return new TripResource($trip);
    }

    public function index(TripIndexRequest $request)
    {
        $trips = Trip::with(['user', 'driver', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return new TripCollection($trips);
    }

    public function show($tripId)
    {
        $trip = Trip::with(['user', 'driver', 'vehicle'])->findOrFail($tripId);

        return new TripResource($trip);
    }

    /**
     * Retry driver assignment for pending trips
     */
    public function retryDriverAssignment($tripId)
    {
        $trip = Trip::findOrFail($tripId);

        if ($trip->status !== TripStatus::PENDING) {
            return response()->json([
                'message' => 'Trip is not in pending status'
            ], 400);
        }

        $driverAssigned = $this->driverSelectionService->autoAssignDriver($trip);

        $trip->load(['user', 'driver', 'vehicle']);

        return response()->json([
            'trip' => new TripResource($trip),
            'driver_assigned' => $driverAssigned,
            'message' => $driverAssigned
                ? 'Driver assigned successfully'
                : 'No suitable drivers available at the moment'
        ]);
    }
}
