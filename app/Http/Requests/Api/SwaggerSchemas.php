<?php

namespace App\Http\Requests\Api;

/**
 * @OA\Schema(
 *     schema="StoreTripRequest",
 *     required={"user_id", "driver_id", "vehicle_id", "origin_latitude", "origin_longitude", "destination_latitude", "destination_longitude", "fare"},
 *     @OA\Property(property="user_id", type="integer", example=1, description="User ID"),
 *     @OA\Property(property="driver_id", type="integer", example=1, description="Driver ID"),
 *     @OA\Property(property="vehicle_id", type="integer", example=1, description="Vehicle ID"),
 *     @OA\Property(property="origin_latitude", type="number", format="float", example=40.7128, description="Origin latitude"),
 *     @OA\Property(property="origin_longitude", type="number", format="float", example=-74.0060, description="Origin longitude"),
 *     @OA\Property(property="destination_latitude", type="number", format="float", example=40.7589, description="Destination latitude"),
 *     @OA\Property(property="destination_longitude", type="number", format="float", example=-73.9851, description="Destination longitude"),
 *     @OA\Property(property="fare", type="number", format="float", example=25.50, description="Trip fare"),
 *     @OA\Property(property="status", type="string", example="pending", description="Trip status"),
 *     @OA\Property(property="pickup_time", type="string", format="date-time", example="2024-01-15T10:00:00Z", description="Pickup time"),
 *     @OA\Property(property="dropoff_time", type="string", format="date-time", example="2024-01-15T10:30:00Z", description="Dropoff time"),
 *     @OA\Property(property="distance", type="number", format="float", example=5.2, description="Trip distance in km"),
 *     @OA\Property(property="duration", type="integer", example=1800, description="Trip duration in seconds")
 * )
 */

/**
 * @OA\Schema(
 *     schema="UpdateTripStatusRequest",
 *     required={"status"},
 *     @OA\Property(property="status", type="string", example="completed", description="New trip status")
 * )
 */

/**
 * @OA\Schema(
 *     schema="TripResource",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user", type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="email", type="string", example="john@example.com")
 *     ),
 *     @OA\Property(property="driver", type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Jane Smith"),
 *         @OA\Property(property="license_number", type="string", example="DL123456")
 *     ),
 *     @OA\Property(property="vehicle", type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="model", type="string", example="Toyota Camry"),
 *         @OA\Property(property="plate_number", type="string", example="ABC123")
 *     ),
 *     @OA\Property(property="origin", type="object",
 *         @OA\Property(property="latitude", type="number", format="float", example=40.7128),
 *         @OA\Property(property="longitude", type="number", format="float", example=-74.0060)
 *     ),
 *     @OA\Property(property="destination", type="object",
 *         @OA\Property(property="latitude", type="number", format="float", example=40.7589),
 *         @OA\Property(property="longitude", type="number", format="float", example=-73.9851)
 *     ),
 *     @OA\Property(property="fare", type="number", format="float", example=25.50),
 *     @OA\Property(property="status", type="string", example="completed"),
 *     @OA\Property(property="pickup_time", type="string", format="date-time", example="2024-01-15T10:00:00Z"),
 *     @OA\Property(property="dropoff_time", type="string", format="date-time", example="2024-01-15T10:30:00Z"),
 *     @OA\Property(property="distance", type="number", format="float", example=5.2),
 *     @OA\Property(property="duration", type="integer", example=1800),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-15T09:45:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-15T10:30:00Z")
 * )
 */

/**
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     @OA\Property(property="message", type="string", example="The given data was invalid."),
 *     @OA\Property(property="errors", type="object",
 *         @OA\Property(property="field_name", type="array", @OA\Items(type="string", example="The field name field is required."))
 *     )
 * )
 */

/**
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     @OA\Property(property="message", type="string", example="Operation completed successfully"),
 *     @OA\Property(property="data", type="object")
 * )
 */ 