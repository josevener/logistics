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
        // Sample vehicle records
        $vehicleInventory = [
            [
                'vehicle_number' => 'TRK-001',
                'truck_type' => 'Cargo Truck',
                'route_from' => 'Manila',
                'route_to' => 'Cebu',
                'total_capacity' => 10000,
                'available_capacity' => 8000,
                'status' => 'ready',
                'last_updated' => Carbon::now(),
                'available_parts' => 'Brake pads, Oil filter',
                'maintenance_record' => 'Oil changed on 2025-03-10',
                'fuel_consumption' => '15 km/l',
                'isDeleted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vehicle_number' => 'TRK-002',
                'truck_type' => 'Refrigerated Truck',
                'route_from' => 'Cebu',
                'route_to' => 'Davao',
                'total_capacity' => 8000,
                'available_capacity' => 5000,
                'status' => 'maintenance',
                'last_updated' => Carbon::now(),
                'available_parts' => 'Coolant, Tires',
                'maintenance_record' => 'Brake check on 2025-03-15',
                'fuel_consumption' => '12 km/l',
                'isDeleted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vehicle_number' => 'TRK-003',
                'truck_type' => 'Tanker Truck',
                'route_from' => 'Davao',
                'route_to' => 'Baguio',
                'total_capacity' => 12000,
                'available_capacity' => 10000,
                'status' => 'ready',
                'last_updated' => Carbon::now(),
                'available_parts' => 'Fuel pump, Radiator',
                'maintenance_record' => 'Tire replacement on 2025-03-18',
                'fuel_consumption' => '10 km/l',
                'isDeleted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

        ];

        DB::table('vehicle_inventories')->insert($vehicleInventory);
    }
}
