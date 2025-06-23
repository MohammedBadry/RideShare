<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Enums\TripStatus;
use App\Enums\VehicleType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DriverSelectionService
{
    /**
     * Select the best available driver for a trip
     */
    public function selectDriverForTrip(Trip $trip): ?Driver
    {
        // Get available drivers near the pickup location
        $availableDrivers = $this->getAvailableDriversNearLocation(
            $trip->origin_latitude,
            $trip->origin_longitude,
            $trip->vehicle_type ?? VehicleType::SEDAN
        );

        if ($availableDrivers->isEmpty()) {
            Log::info("No available drivers found for trip {$trip->id}");
            return null;
        }

        // Apply selection criteria and rank drivers
        $rankedDrivers = $this->rankDrivers($availableDrivers, $trip);

        // Select the best driver
        $selectedDriver = $rankedDrivers->first();

        if ($selectedDriver) {
            Log::info("Selected driver {$selectedDriver->id} for trip {$trip->id}");
        }

        return $selectedDriver;
    }

    /**
     * Get available drivers near a specific location
     */
    private function getAvailableDriversNearLocation(
        float $latitude,
        float $longitude,
        VehicleType $vehicleType,
        int $radius = 5
    ) {
        $cacheKey = "available_drivers:{$latitude}:{$longitude}:{$vehicleType->value}";

        return Cache::remember($cacheKey, 30, function() use ($latitude, $longitude, $vehicleType, $radius) {
            return Vehicle::where('is_available', true)
                ->where('type', $vehicleType->value)
                ->whereHas('driver', function($query) {
                    $query->where('is_available', true);
                })
                ->selectRaw("
                    vehicles.*,
                    drivers.id as driver_id,
                    drivers.license_number,
                    drivers.experience_years,
                    users.name as driver_name,
                    users.email as driver_email,
                    (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance
                ", [$latitude, $longitude, $latitude])
                ->join('drivers', 'vehicles.driver_id', '=', 'drivers.id')
                ->join('users', 'drivers.user_id', '=', 'users.id')
                ->having('distance', '<=', $radius)
                ->orderBy('distance')
                ->get();
        });
    }

    /**
     * Rank drivers based on multiple criteria
     */
    private function rankDrivers($drivers, Trip $trip)
    {
        return $drivers->map(function($vehicle) use ($trip) {
            $score = $this->calculateDriverScore($vehicle, $trip);
            $vehicle->selection_score = $score;
            return $vehicle;
        })->sortByDesc('selection_score');
    }

    /**
     * Calculate driver selection score
     */
    private function calculateDriverScore($vehicle, Trip $trip): float
    {
        $score = 0;

        // Distance score (closer is better) - 40% weight
        $distanceScore = max(0, 100 - ($vehicle->distance * 10));
        $score += $distanceScore * 0.4;

        // Experience score - 20% weight
        $experienceScore = min(100, $vehicle->experience_years * 10);
        $score += $experienceScore * 0.2;

        // Rating score (if available) - 20% weight
        $ratingScore = $this->getDriverRating($vehicle->driver_id) ?? 75;
        $score += $ratingScore * 0.2;

        // Availability time score (longer available = better) - 10% weight
        $availabilityScore = $this->getAvailabilityScore($vehicle->driver_id);
        $score += $availabilityScore * 0.1;

        // Vehicle condition score - 10% weight
        $vehicleScore = $this->getVehicleConditionScore($vehicle->id);
        $score += $vehicleScore * 0.1;

        return round($score, 2);
    }

    /**
     * Get driver rating (placeholder for future implementation)
     */
    private function getDriverRating(int $driverId): ?float
    {
        // This would typically query a ratings table
        // For now, return a default rating
        return 85.0;
    }

    /**
     * Get driver availability score
     */
    private function getAvailabilityScore(int $driverId): float
    {
        // Calculate how long driver has been available
        $driver = Driver::find($driverId);
        if (!$driver) return 0;

        // This could be based on last trip completion time
        $lastTrip = Trip::where('driver_id', $driverId)
            ->where('status', TripStatus::COMPLETED)
            ->latest()
            ->first();

        if (!$lastTrip) return 100; // New driver

        $hoursSinceLastTrip = now()->diffInHours($lastTrip->end_time);
        return min(100, $hoursSinceLastTrip * 10);
    }

    /**
     * Get vehicle condition score
     */
    private function getVehicleConditionScore(int $vehicleId): float
    {
        // This could be based on vehicle maintenance records
        // For now, return a default score
        return 90.0;
    }

    /**
     * Auto-assign driver to trip
     */
    public function autoAssignDriver(Trip $trip): bool
    {
        $selectedDriver = $this->selectDriverForTrip($trip);

        if (!$selectedDriver) {
            // No driver available, keep trip as pending
            $trip->update(['status' => TripStatus::PENDING]);
            return false;
        }

        // Assign driver to trip
        $trip->update([
            'driver_id' => $selectedDriver->driver_id,
            'vehicle_id' => $selectedDriver->id,
            'status' => TripStatus::ASSIGNED
        ]);

        // Update driver and vehicle availability
        Driver::where('id', $selectedDriver->driver_id)->update(['is_available' => false]);
        Vehicle::where('id', $selectedDriver->id)->update(['is_available' => false]);

        // Clear relevant caches
        $this->clearDriverCaches($selectedDriver->driver_id, $trip);

        //here we can send fcm notifiactions to both user and driver
        Log::info("Auto-assigned driver {$selectedDriver->driver_id} to trip {$trip->id}");
        return true;
    }

    /**
     * Clear relevant caches after driver assignment
     */
    private function clearDriverCaches(int $driverId, Trip $trip): void
    {
        Cache::forget("driver:{$driverId}:active_trips");
        Cache::forget("available_drivers:{$trip->origin_latitude}:{$trip->origin_longitude}");

        // Clear vehicle type specific cache
        $vehicleType = Vehicle::find($trip->vehicle_id)->type ?? 'sedan';
        Cache::forget("available_drivers:{$trip->origin_latitude}:{$trip->origin_longitude}:{$vehicleType}");
    }

    /**
     * Get drivers for manual selection (admin interface)
     */
    public function getDriversForManualSelection(Trip $trip): array
    {
        $availableDrivers = $this->getAvailableDriversNearLocation(
            $trip->origin_latitude,
            $trip->origin_longitude,
            $trip->vehicle_type ?? VehicleType::SEDAN,
            10 // Larger radius for manual selection
        );

        $rankedDrivers = $this->rankDrivers($availableDrivers, $trip);

        return [
            'drivers' => $rankedDrivers->take(10), // Top 10 drivers
            'total_available' => $availableDrivers->count(),
            'selection_criteria' => [
                'distance_weight' => 40,
                'experience_weight' => 20,
                'rating_weight' => 20,
                'availability_weight' => 10,
                'vehicle_condition_weight' => 10
            ]
        ];
    }
}
