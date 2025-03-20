<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketPlaceAdminController extends Controller
{
    public function store()
    {
        $products = Product::with('vendor.user')->get();
        return view('marketplace.admin.store', compact('products'));
    }

    public function cart()
    {
        return view('marketplace.admin.cart');
    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        if ($product->type === 'items' && $product->stock <= 0) {
            return redirect()->route('marketplace.admin.store')->with('error', 'Product out of stock!');
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'type' => $product->type,
                'vendor_id' => $product->vendor_id,
            ];
        }

        if ($product->type === 'items') {
            $product->decrement('stock');
        }

        session()->put('cart', $cart);
        return redirect()->route('marketplace.admin.store')->with('success', 'Added to cart!');
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $product = Product::find($productId);
            if ($product && $product->type === 'items') {
                $product->increment('stock', $cart[$productId]['quantity']);
            }
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->route('marketplace.admin.cart')->with('success', 'Item removed from cart!');
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('marketplace.admin.store')->with('error', 'Cart is empty!');
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'status' => 'Pending',
        ]);

        foreach ($cart as $id => $item) {
            $order->products()->attach($id, [
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Create PO for vendor
            \App\Models\PurchaseOrder::create([
                'po_number' => 'PO' . now()->format('YmdHis') . rand(100, 999),
                'vendor_id' => $item['vendor_id'],
                'type' => $item['type'],
                'description' => "Order for {$item['name']} (Qty: {$item['quantity']})",
                'amount' => $item['price'] * $item['quantity'],
                'status' => 'Pending',
            ]);
        }

        session()->forget('cart');
        return redirect()->route('marketplace.admin.store')->with('success', 'Order placed successfully!');
    }
}
