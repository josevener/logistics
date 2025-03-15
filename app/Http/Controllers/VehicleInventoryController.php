<?php

namespace App\Http\Controllers;

use App\Models\VehicleInventory;
use App\Http\Requests\StoreVehicleInventoryRequest;
use App\Http\Requests\UpdateVehicleInventoryRequest;
use Illuminate\Http\Request;

class VehicleInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $sort = $request->query('sort', 'created_at-desc');

        [$sortField, $sortDirection] = explode('-', $sort);

        $query = VehicleInventory::query();

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $query->orderBy($sortField, $sortDirection);

        $vehicles = $query->paginate(12);

        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleInventoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $vehicle = VehicleInventory::findOrFail($id);

        return response()->json([
            'available_parts' => $vehicle->available_parts,
            'maintenance_record' => $vehicle->maintenance_record,
            'fuel_consumption' => $vehicle->fuel_consumption,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleInventory $vehicleInventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleInventoryRequest $request, VehicleInventory $vehicleInventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleInventory $vehicleInventory)
    {
        //
    }
}
