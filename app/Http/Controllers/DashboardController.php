<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'Admin') {
            return $this->admin();
        } elseif (Auth::user()->role === 'Vendor') {
            return $this->vendor();
        } else {
            return view('welcome');
        }
    }

    protected function admin()
    {
        // Fetch registered vendors count and change
        $registeredVendorsCount = User::where('role', 'Vendor')->count();
        $vendorChange = User::where('role', 'Vendor')
            ->where('created_at', '>=', now()->subDays(30))
            ->count(); // New vendors in the last 30 days

        // Fetch active RFPs count

        $activeRfpsCount = Proposal::count();
        $newRfpsCount = Proposal::where('created_at', '>=', now()->subDays(7))
            ->count(); // New RFPs in the last 7 days

        // // Fetch proposals submitted count
        // $proposalsSubmittedCount = Proposal::count();
        // $proposalsChange = Proposal::where('created_at', '>=', now()->subDays(30))
        //     ->count(); // New proposals in the last 30 days

        // // Fetch contracts awarded count
        // $contractsAwardedCount = Contract::where('status', 'awarded')->count();
        // $contractsChange = Contract::where('status', 'awarded')
        //     ->where('updated_at', '<', now()->subDays(30))
        //     ->count(); // Assuming a negative change if fewer awarded recently

        return view('dashboard.admin', [
            'registeredVendorsCount' => $registeredVendorsCount,
            'vendorChange' => $vendorChange,
            'activeRfpsCount' => $activeRfpsCount,
            'newRfpsCount' => $newRfpsCount,
            // 'proposalsSubmittedCount' => $proposalsSubmittedCount,
            // 'proposalsChange' => $proposalsChange,
            // 'contractsAwardedCount' => $contractsAwardedCount,
            // 'contractsChange' => $contractsAwardedCount - $contractsChange, // Negative if fewer awarded
        ]);
    }

    public function vendor()
    {
        $contracts = Contract::where('vendor_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Adjust per-page limit as needed
        return view('dashboard.vendor', compact('contracts'));
    }
}
