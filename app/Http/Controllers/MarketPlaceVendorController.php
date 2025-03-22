<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketPlaceVendorController extends Controller
{
    public function index()
    {
        $vendorProducts = Product::where('vendor_id', Auth::user()->vendor->id)->get();
        $allOrders = Order::with(['products.vendor.user', 'purchaseOrders'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('marketplace.vendor.index', compact('vendorProducts', 'allOrders'));
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

    public function vendorOrders()
    {
        $vendorId = Auth::user()->vendor->id;
        $purchaseOrders = PurchaseOrder::where('vendor_id', $vendorId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Manually enrich purchase orders with product data from description
        foreach ($purchaseOrders as $purchaseOrder) {
            $productNames = collect(explode(', ', $purchaseOrder->description))
                ->map(fn($desc) => explode(' (Qty: ', $desc)[0])
                ->map(fn($name) => str_replace('Order for ', '', $name))
                ->toArray();

            $quantity = (int) filter_var($purchaseOrder->description, FILTER_SANITIZE_NUMBER_INT);

            $products = Product::whereIn('name', $productNames)
                ->where('vendor_id', $vendorId)
                ->get()
                ->map(function ($product) use ($quantity) {
                    return [
                        'name' => $product->name,
                        'price' => $product->price, // Assuming price comes from Product model
                        'quantity' => $quantity,    // Assuming single quantity for simplicity
                        'total' => $product->price * $quantity
                    ];
                });

            $purchaseOrder->products = $products;
        }

        return view('marketplace.vendor.orders', compact('purchaseOrders'));
    }

    public function updateOrderStatus(Request $request, $purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::where('vendor_id', Auth::user()->vendor->id)
            ->findOrFail($purchaseOrderId);

        $newStatus = $request->input('status');
        $validStatuses = ['Pending', 'Approved', 'Rejected', 'Completed', 'Canceled'];

        if (!in_array($newStatus, $validStatuses)) {
            flash()->error('Invalid status selected.');
            return redirect()->route('marketplace.vendor.orders');
        }

        // Stock handling
        $productNames = collect(explode(', ', $purchaseOrder->description))
            ->map(fn($desc) => explode(' (Qty: ', $desc)[0])
            ->map(fn($name) => str_replace('Order for ', '', $name))
            ->toArray();

        foreach ($productNames as $productName) {
            $product = Product::where('name', $productName)
                ->where('vendor_id', $purchaseOrder->vendor_id)
                ->first();

            if ($product && $product->type === 'items') {
                $quantity = (int) filter_var($purchaseOrder->description, FILTER_SANITIZE_NUMBER_INT);

                if ($newStatus === 'Approved' && $purchaseOrder->status === 'Pending') {
                    if ($product->stock < $quantity) {
                        flash()->error("Insufficient stock for {$product->name} to approve this order.");
                        return redirect()->route('marketplace.vendor.orders');
                    }
                    $product->decrement('stock', $quantity);
                } elseif ($newStatus === 'Rejected' && $purchaseOrder->status === 'Pending') {
                    $product->increment('stock', $quantity);
                }
            }
        }

        $purchaseOrder->update(['status' => $newStatus]);
        flash()->success("Purchase Order #{$purchaseOrder->po_number} status updated to {$newStatus}.");
        return redirect()->route('marketplace.vendor.orders');
    }

    public function approveOrder(Request $request, $purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::where('vendor_id', Auth::user()->vendor->id)
            ->findOrFail($purchaseOrderId);

        if ($purchaseOrder->status !== 'Pending') {
            flash()->error('Only pending orders can be approved.');
            return redirect()->route('marketplace.vendor.orders');
        }

        $productNames = collect(explode(', ', $purchaseOrder->description))
            ->map(fn($desc) => explode(' (Qty: ', $desc)[0])
            ->map(fn($name) => str_replace('Order for ', '', $name))
            ->toArray();

        foreach ($productNames as $productName) {
            $product = Product::where('name', $productName)
                ->where('vendor_id', $purchaseOrder->vendor_id)
                ->first();
            if ($product && $product->type === 'items') {
                $quantity = (int) filter_var($purchaseOrder->description, FILTER_SANITIZE_NUMBER_INT);
                if ($product->stock < $quantity) {
                    flash()->error("Insufficient stock for {$product->name} to approve this order.");
                    return redirect()->route('marketplace.vendor.orders');
                }
            }
        }

        $purchaseOrder->update(['status' => 'Approved']);

        flash()->success("Purchase Order #{$purchaseOrder->po_number} has been approved.");
        return redirect()->route('marketplace.vendor.orders');
    }

    public function rejectOrder(Request $request, $purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::where('vendor_id', Auth::user()->vendor->id)
            ->findOrFail($purchaseOrderId);

        if ($purchaseOrder->status !== 'Pending') {
            flash()->error('Only pending orders can be rejected.');
            return redirect()->route('marketplace.vendor.orders');
        }

        $productNames = collect(explode(', ', $purchaseOrder->description))
            ->map(fn($desc) => explode(' (Qty: ', $desc)[0])
            ->map(fn($name) => str_replace('Order for ', '', $name))
            ->toArray();

        foreach ($productNames as $productName) {
            $product = Product::where('name', $productName)
                ->where('vendor_id', $purchaseOrder->vendor_id)
                ->first();
            if ($product && $product->type === 'items') {
                $quantity = (int) filter_var($purchaseOrder->description, FILTER_SANITIZE_NUMBER_INT);
                $product->increment('stock', $quantity);
            }
        }

        $purchaseOrder->update(['status' => 'Rejected']);

        flash()->success("Purchase Order #{$purchaseOrder->po_number} has been rejected.");
        return redirect()->route('marketplace.vendor.orders');
    }
}
