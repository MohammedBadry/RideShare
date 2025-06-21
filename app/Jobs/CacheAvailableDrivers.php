<?php

namespace App\Jobs;

use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CacheAvailableDrivers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all available vehicles with their drivers and users
        $vehicles = Vehicle::where('is_available', true)
            ->with(['driver.user'])
            ->get(['id', 'driver_id', 'type', 'latitude', 'longitude']);

        // Cache the results for quick access
        Cache::put('available_drivers_list', $vehicles, 60); // Cache for 1 minute

        // Also cache individual vehicle locations for real-time access
        foreach ($vehicles as $vehicle) {
            $location = [
                'lat' => $vehicle->latitude,
                'lng' => $vehicle->longitude,
                'driver_id' => $vehicle->driver_id,
                'vehicle_type' => $vehicle->type->value,
                'driver_name' => $vehicle->driver->user->name ?? 'Unknown',
                'updated_at' => now()->toISOString()
            ];
            
            Cache::put("vehicle_location:{$vehicle->id}", $location, 60);
        }

        // Log the number of cached drivers for monitoring
        \Log::info("Cached {$vehicles->count()} available drivers");
    }
}
