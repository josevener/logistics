<?php

namespace Database\Seeders;

use App\Models\VehicleInventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleInventory::create([
            'vehicle_number' => '#000001',
            'truck_type' => 'Freightliner',
            'route_from' => 'Manila',
            'route_to' => 'Cebu',
            'total_capacity' => 200,
            'available_capacity' => 20,
            'status' => 'ready',
            'last_updated' => now(),
            'available_parts' => 'Tires, Engine Oil',
            'maintenance_record' => 'Last serviced: March 10, 2025',
            'fuel_consumption' => '10L/100km',
        ]);

        VehicleInventory::create([
            'vehicle_number' => '#000002',
            'truck_type' => 'Volvo',
            'route_from' => 'Davao',
            'route_to' => 'Quezon',
            'total_capacity' => 400,
            'available_capacity' => 200,
            'status' => 'maintenance',
            'last_updated' => now()->subDays(2),
            'available_parts' => 'Brake Pads',
            'maintenance_record' => 'Under repair since March 12, 2025',
            'fuel_consumption' => '12L/100km',
        ]);
    }
}
