<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
                'email' => $this->user->email ?? null,
            ],
            'driver' => [
                'id' => $this->driver->id ?? null,
                'name' => $this->driver->user->name ?? null,
                'license_number' => $this->driver->license_number ?? null,
            ],
            'vehicle' => [
                'id' => $this->vehicle->id ?? null,
                'model' => $this->vehicle->model ?? null,
                'plate_number' => $this->vehicle->plate_number ?? null,
            ],
            'origin' => [
                'latitude' => $this->origin_latitude,
                'longitude' => $this->origin_longitude,
            ],
            'destination' => [
                'latitude' => $this->destination_latitude,
                'longitude' => $this->destination_longitude,
            ],
            'fare' => $this->fare,
            'status' => $this->status,
            'pickup_time' => $this->pickup_time,
            'dropoff_time' => $this->dropoff_time,
            'distance' => $this->distance,
            'duration' => $this->duration,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 