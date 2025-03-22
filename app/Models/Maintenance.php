<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'created_by',
        'description',
        'maintenance_date',
        'maintenance_type',
        'cost',
        'status',
        'isPriority',
        'assigned_tech',
        'notes',


    ];

    // Define relationship with Vehicle
    public function vehicle()
    {
        return $this->belongsTo(VehicleInventory::class);
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_tech');
    }
}
