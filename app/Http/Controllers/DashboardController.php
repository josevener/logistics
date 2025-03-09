<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function admin()
    {
        return view('dashboard.admin');
    }

    public function vendor()
    {
        $contracts = Contract::where('vendor_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Adjust per-page limit as needed
        return view('dashboard.vendor', compact('contracts'));
    }
}
