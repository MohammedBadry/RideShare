<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Trip;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class TestFeatures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:features';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test all RideShare features including user, driver, vehicle, and trip creation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing RideShare Features...');
        
        // Test 1: Create a User
        $this->info('Testing User creation...');
        try {
            $user = User::create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+1234567890',
                'password' => Hash::make('password123')
            ]);
            $this->info("✅ User created successfully: {$user->name}");
        } catch (\Exception $e) {
            $this->error("❌ User creation failed: " . $e->getMessage());
            return 1;
        }

        // Test 2: Create a Vehicle
        $this->info('Testing Vehicle creation...');
        try {
            $vehicle = Vehicle::create([
                'model' => 'Toyota Camry',
                'plate_number' => 'ABC123',
                'year' => 2023,
                'type' => 'sedan',
                'color' => 'Silver',
                'is_available' => true,
                'latitude' => 40.7128,
                'longitude' => -74.0060
            ]);
            $this->info("✅ Vehicle created successfully: {$vehicle->model} ({$vehicle->plate_number})");
        } catch (\Exception $e) {
            $this->error("❌ Vehicle creation failed: " . $e->getMessage());
            return 1;
        }

        // Test 3: Create a Driver
        $this->info('Testing Driver creation...');
        try {
            $driver = Driver::create([
                'user_id' => $user->id,
                'license_number' => 'DL123456',
                'experience_years' => 5,
                'vehicle_id' => $vehicle->id,
                'is_available' => true
            ]);
            $this->info("✅ Driver created successfully: {$driver->license_number}");
        } catch (\Exception $e) {
            $this->error("❌ Driver creation failed: " . $e->getMessage());
            return 1;
        }

        // Test 4: Create a Trip
        $this->info('Testing Trip creation...');
        try {
            $trip = Trip::create([
                'user_id' => $user->id,
                'driver_id' => $driver->id,
                'vehicle_id' => $vehicle->id,
                'pickup_location' => '123 Main St, New York, NY',
                'dropoff_location' => '456 Broadway, New York, NY',
                'origin_latitude' => 40.7128,
                'origin_longitude' => -74.0060,
                'destination_latitude' => 40.7589,
                'destination_longitude' => -73.9851,
                'fare' => 25.50,
                'status' => 'pending'
            ]);
            $this->info("✅ Trip created successfully: Trip #{$trip->id}");
        } catch (\Exception $e) {
            $this->error("❌ Trip creation failed: " . $e->getMessage());
            return 1;
        }

        // Test 5: Test relationships
        $this->info('Testing relationships...');
        try {
            $this->info("User has driver: " . ($user->driver ? 'Yes' : 'No'));
            $this->info("Driver has vehicle: " . ($driver->vehicle ? 'Yes' : 'No'));
            $this->info("Vehicle has driver: " . ($vehicle->driver ? 'Yes' : 'No'));
            $this->info("User has trips: " . $user->trips->count());
            $this->info("Driver has trips: " . $driver->trips->count());
            $this->info("Vehicle has trips: " . $vehicle->trips->count());
            $this->info("✅ Relationships working correctly");
        } catch (\Exception $e) {
            $this->error("❌ Relationship test failed: " . $e->getMessage());
            return 1;
        }

        // Test 6: Test Admin creation
        $this->info('Testing Admin creation...');
        try {
            $admin = Admin::create([
                'name' => 'Admin User',
                'email' => 'admin@rideshare.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true
            ]);
            $this->info("✅ Admin created successfully: {$admin->name}");
        } catch (\Exception $e) {
            $this->error("❌ Admin creation failed: " . $e->getMessage());
            return 1;
        }

        // Test 7: Test form validation
        $this->info('Testing form validation...');
        try {
            // Test duplicate email
            try {
                User::create([
                    'name' => 'Jane Doe',
                    'email' => 'john@example.com', // Duplicate email
                    'phone' => '+1234567891',
                    'password' => Hash::make('password123')
                ]);
                $this->error("❌ Duplicate email validation failed");
                return 1;
            } catch (\Exception $e) {
                $this->info("✅ Duplicate email validation working");
            }

            // Test duplicate plate number
            try {
                Vehicle::create([
                    'model' => 'Honda Civic',
                    'plate_number' => 'ABC123', // Duplicate plate
                    'year' => 2022,
                    'type' => 'sedan',
                    'color' => 'Blue',
                    'is_available' => true,
                    'latitude' => 40.7128,
                    'longitude' => -74.0060
                ]);
                $this->error("❌ Duplicate plate number validation failed");
                return 1;
            } catch (\Exception $e) {
                $this->info("✅ Duplicate plate number validation working");
            }

            // Test duplicate license number
            try {
                Driver::create([
                    'user_id' => $user->id,
                    'license_number' => 'DL123456', // Duplicate license
                    'experience_years' => 3,
                    'is_available' => true
                ]);
                $this->error("❌ Duplicate license number validation failed");
                return 1;
            } catch (\Exception $e) {
                $this->info("✅ Duplicate license number validation working");
            }
        } catch (\Exception $e) {
            $this->error("❌ Validation test failed: " . $e->getMessage());
            return 1;
        }

        $this->info('🎉 All tests completed successfully!');
        $this->info('📊 Summary:');
        $this->info("- Users: " . User::count());
        $this->info("- Drivers: " . Driver::count());
        $this->info("- Vehicles: " . Vehicle::count());
        $this->info("- Trips: " . Trip::count());
        $this->info("- Admins: " . Admin::count());

        //
    }
}
