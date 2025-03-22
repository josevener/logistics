<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Http\Requests\StoreMaintenanceRequest;
use App\Http\Requests\UpdateMaintenanceRequest;
use App\Models\VehicleInventory;
use Illuminate\Http\Request;
use App\Models\Vendor;

use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maintenances = Maintenance::with('vehicle', 'createdBy')->where('status', 'pending')->orderBy('maintenance_date', 'asc')->get();


        // Fetch Maintenance Alerts (Overdue & Due Soon)
        $overdueTasks = Maintenance::where('maintenance_date', '<', now())->where('status', '!=', 'completed')->get();
        $dueSoonTasks = Maintenance::whereBetween('maintenance_date', [now(), now()->addDays(3)])->where('status', '!=', 'completed')->get();

        // Quick Stats
        $pendingTasks = Maintenance::where('status', 'pending')->count();
        $completedThisMonth = Maintenance::where('status', 'completed')->whereMonth('maintenance_date', now()->month)->count();
        $activeVehicles = VehicleInventory::where('status', 'ready')->count();

        $vendorsOptions = Vendor::all();

        return view('maintenance.index', compact(
            'maintenances',
            'overdueTasks',
            'dueSoonTasks',
            'pendingTasks',
            'completedThisMonth',
            'activeVehicles',
            'vendorsOptions'
        ));
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
    public function store(Request $request)
    {
        $maintenance = Maintenance::create([
            'vehicle_id' => $request->vehicle_id,
            'description' => $request->task_desc,
            'maintenance_date' => $request->task_date,
            'cost' => $request->estimated_cost,
            'priority' => $request->priority,
            'assigned_tech' => $request->assigned_tech,
            'notes' => $request->notes,
            'created_by' => Auth::user()->id,
        ]);

        flash()->success('Maintenance task created successfully');
        return redirect()->route('maintenance.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Maintenance $maintenance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maintenance $maintenance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaintenanceRequest $request, Maintenance $maintenance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        //
    }
}
