<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VehicleInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample bus records
        $vehicleInventory = [
            [
                'vehicle_number' => 'BUS-001',
                'driver_name' => 'Juan Dela Cruz',
                'route_from' => 'Manila',
                'route_to' => 'Cebu',
                'total_capacity' => 50, // Seats
                'available_capacity' => 40,
                'status' => 'ready',
                'last_updated' => Carbon::now(),
                'image' => 'vehicles/bus-001.jpg', // Example image path
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vehicle_number' => 'BUS-002',
                'driver_name' => 'Maria Santos',
                'route_from' => 'Cebu',
                'route_to' => 'Davao',
                'total_capacity' => 45,
                'available_capacity' => 20,
                'status' => 'maintenance',
                'last_updated' => Carbon::now(),
                'image' => 'vehicles/bus-002.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vehicle_number' => 'BUS-003',
                'driver_name' => 'Pedro Reyes',
                'route_from' => 'Davao',
                'route_to' => 'Baguio',
                'total_capacity' => 60,
                'available_capacity' => 55,
                'status' => 'ready',
                'last_updated' => Carbon::now(),
                'image' => 'vehicles/bus-003.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('vehicle_inventories')->insert($vehicleInventory);
    }
}
