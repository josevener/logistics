<?php

namespace App\Http\Controllers;

use App\Models\VehicleInventory;
use App\Http\Requests\StoreVehicleInventoryRequest;
use App\Http\Requests\UpdateVehicleInventoryRequest;

class VehicleInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('shipments.index');
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
    public function show(VehicleInventory $vehicleInventory)
    {
        //
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
