<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Enums\TripStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('now', '+1 hour');
        $endTime = $this->faker->dateTimeBetween($startTime, '+3 hours');

        return [
            'user_id' => User::factory(),
            'driver_id' => Driver::factory(),
            'vehicle_id' => Vehicle::factory(),
            'origin_latitude' => $this->faker->latitude(),
            'origin_longitude' => $this->faker->longitude(),
            'destination_latitude' => $this->faker->latitude(),
            'destination_longitude' => $this->faker->longitude(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'fare' => $this->faker->randomFloat(2, 10, 200),
            'status' => $this->faker->randomElement(TripStatus::cases()),
        ];
    }

    /**
     * Indicate that the trip is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TripStatus::PENDING,
        ]);
    }

    /**
     * Indicate that the trip is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TripStatus::IN_PROGRESS,
        ]);
    }

    /**
     * Indicate that the trip is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TripStatus::COMPLETED,
        ]);
    }
}
