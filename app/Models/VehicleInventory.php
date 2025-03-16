<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleInventory extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleInventoryFactory> */
    use HasFactory;

    protected $fillable = [
        'vehicle_number',
        'truck_type',
        'route_from',
        'route_to',
        'total_capacity',
        'available_capacity',
        'status',
        'last_updated',
        'available_parts',
        'maintenance_record',
        'fuel_consumption',
        'image'
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];
}
