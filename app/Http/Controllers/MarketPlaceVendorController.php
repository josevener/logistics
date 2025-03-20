<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketPlaceVendorController extends Controller
{
    public function index()
    {
        $vendorProducts = Product::where('vendor_id', Auth::user()->vendor->id)->get();
        return view('marketplace.vendor.index', compact('vendorProducts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:service,items',
            'price' => 'required|numeric|min:0',
            'stock' => 'required_if:type,items|integer|min:0|nullable', // Allow null for services
            'description' => 'required|string',
        ]);

        // Explicitly set stock to null for services
        $validated['vendor_id'] = Auth::user()->vendor->id;
        if ($validated['type'] === 'service') {
            $validated['stock'] = null; // Ensure stock is null for services
        }
        Product::create($validated);

        flash()->success('Product/Service added!');
        return redirect()->route('marketplace.vendor.index');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:service,items',
            'price' => 'required|numeric|min:0',
            'stock' => 'required_if:type,items|integer|min:0|nullable', // Allow null for services
            'description' => 'required|string',
        ]);
        $product->update($validated);

        flash()->success('Product/Service updated!');
        return redirect()->route('marketplace.vendor.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        flash()->success('Product/Service deleted!');
        return redirect()->route('marketplace.vendor.index');
    }
}
