<?php

namespace App\Http\Controllers;

use App\Models\VehicleInventory;
use App\Http\Requests\StoreVehicleInventoryRequest;
use App\Http\Requests\UpdateVehicleInventoryRequest;
use Illuminate\Http\Request;

class VehicleInventoryController extends Controller
{
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

        $vehicles = $query->paginate(6);

        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(StoreVehicleInventoryRequest $request)
    {
        $vehicle = VehicleInventory::create($request->validated());
        flash()->success('Vehicle added successfully!');
        return response()->json([
            'redirect' => route('vehicles.index')
        ], 201);
    }

    public function show($id)
    {
        try {
            $vehicle = VehicleInventory::findOrFail($id);
            return response()->json([
                'available_parts' => $vehicle->available_parts,
                'maintenance_record' => $vehicle->maintenance_record,
                'fuel_consumption' => $vehicle->fuel_consumption,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }
    }

    public function edit($id)
    {
        try {
            $vehicle = VehicleInventory::findOrFail($id);
            return response()->json($vehicle);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }
    }

    public function update(UpdateVehicleInventoryRequest $request, $id)
    {
        try {
            $vehicle = VehicleInventory::findOrFail($id);
            $vehicle->update($request->validated());
            flash()->success('Vehicle updated successfully!');
            return response()->json([
                'redirect' => route('vehicles.index')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update vehicle'], 400);
        }
    }

    public function destroy(VehicleInventory $vehicle)
    {
        $vehicle->delete();
        flash()->success('Vehicle deleted successfully!');
        return response()->json([
            'redirect' => route('vehicles.index')
        ]);
    }
}
