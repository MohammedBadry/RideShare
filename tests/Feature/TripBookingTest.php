<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Trip;
use App\Enums\VehicleType;
use App\Enums\TripStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TripBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_book_trip_with_valid_data()
    {
        $user = User::factory()->create();
        $driver = Driver::factory()->create(['is_available' => true]);
        $vehicle = Vehicle::factory()->create([
            'driver_id' => $driver->id,
            'type' => VehicleType::SUV,
        ]);

        $response = $this->postJson('/api/trips', [
            'user_id' => $user->id,
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'vehicle_type' => VehicleType::SUV->value,
            'origin_latitude' => 10.0,
            'origin_longitude' => 10.0,
            'destination_latitude' => 20.0,
            'destination_longitude' => 20.0,
            'start_time' => now()->addHour()->toDateTimeString(),
            'end_time' => now()->addHours(2)->toDateTimeString(),
            'fare' => 100.00,
            'status' => TripStatus::PENDING->value,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('trips', [
            'user_id' => $user->id,
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
        ]);
    }

    public function test_validates_vehicle_type_mismatch()
    {
        $user = User::factory()->create();
        $driver = Driver::factory()->create(['is_available' => true]);
        $vehicle = Vehicle::factory()->create([
            'driver_id' => $driver->id,
            'type' => VehicleType::SEDAN,
        ]);

        $response = $this->postJson('/api/trips', [
            'user_id' => $user->id,
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'vehicle_type' => VehicleType::SUV->value, // Mismatch
            'origin_latitude' => 10.0,
            'origin_longitude' => 10.0,
            'destination_latitude' => 20.0,
            'destination_longitude' => 20.0,
            'start_time' => now()->addHour()->toDateTimeString(),
            'end_time' => now()->addHours(2)->toDateTimeString(),
            'fare' => 100.00,
            'status' => TripStatus::PENDING->value,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['vehicle_type']);
    }

    public function test_validates_overlapping_trips()
    {
        $user = User::factory()->create();
        $driver = Driver::factory()->create(['is_available' => true]);
        $vehicle = Vehicle::factory()->create([
            'driver_id' => $driver->id,
            'type' => VehicleType::SUV,
        ]);

        // Create an existing trip
        Trip::factory()->create([
            'driver_id' => $driver->id,
            'status' => TripStatus::IN_PROGRESS,
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(3),
        ]);

        $response = $this->postJson('/api/trips', [
            'user_id' => $user->id,
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'vehicle_type' => VehicleType::SUV->value,
            'origin_latitude' => 10.0,
            'origin_longitude' => 10.0,
            'destination_latitude' => 20.0,
            'destination_longitude' => 20.0,
            'start_time' => now()->addHours(2)->toDateTimeString(), // Overlaps
            'end_time' => now()->addHours(4)->toDateTimeString(),
            'fare' => 100.00,
            'status' => TripStatus::PENDING->value,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['driver_id']);
    }

    public function test_validates_unavailable_driver()
    {
        $user = User::factory()->create();
        $driver = Driver::factory()->create(['is_available' => false]);
        $vehicle = Vehicle::factory()->create([
            'driver_id' => $driver->id,
            'type' => VehicleType::SUV,
        ]);

        $response = $this->postJson('/api/trips', [
            'user_id' => $user->id,
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'vehicle_type' => VehicleType::SUV->value,
            'origin_latitude' => 10.0,
            'origin_longitude' => 10.0,
            'destination_latitude' => 20.0,
            'destination_longitude' => 20.0,
            'start_time' => now()->addHour()->toDateTimeString(),
            'end_time' => now()->addHours(2)->toDateTimeString(),
            'fare' => 100.00,
            'status' => TripStatus::PENDING->value,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['driver_id']);
    }
}
