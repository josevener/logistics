<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketPlaceAdminController extends Controller
{
    public function store()
    {
        $products = Product::with('vendor.user')->get();
        $cartItems = Auth::check() ? Cart::where('user_id', Auth::id())->with('product')->get() : collect();
        return view('marketplace.admin.store', compact('products', 'cartItems'));
    }

    public function cart()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        return view('marketplace.admin.cart', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $quantity = $request->validate([
            'quantity' => 'required|integer|min:1' . ($product->type === 'items' ? "|max:{$product->stock}" : ''),
        ])['quantity'];

        if ($product->type === 'items' && $product->stock < $quantity) {
            flash()->error('Not enough stock available!');
            return redirect()->route('marketplace.admin.store');
        }

        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($product->type === 'items' && $newQuantity > $product->stock) {
                flash()->error('Cannot add more than available stock!');
                return redirect()->route('marketplace.admin.store');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        if ($product->type === 'items') {
            $product->decrement('stock', $quantity);
        }

        flash()->success("Added $quantity x {$product->name} to cart!");
        return redirect()->route('marketplace.admin.store');
    }

    public function removeFromCart(Request $request)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $request->product_id)->first();

        if ($cartItem) {
            $product = $cartItem->product;
            if ($product->type === 'items') {
                $product->increment('stock', $cartItem->quantity);
            }
            $cartItem->delete();
            flash()->success('Item removed from cart!');
        }

        return redirect()->route('marketplace.admin.cart');
    }

    public function checkout(Request $request)
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            flash()->error('Cart is empty!');
            return redirect()->route('marketplace.admin.store');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'status' => 'Pending',
        ]);

        foreach ($cartItems as $item) {
            $order->products()->attach($item->product_id, [
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);

            PurchaseOrder::create([
                'po_number' => 'PO' . now()->format('YmdHis') . rand(100, 999),
                'vendor_id' => $item->product->vendor_id,
                'type' => $item->product->type,
                'description' => "Order for {$item->product->name} (Qty: {$item->quantity})",
                'amount' => $item->product->price * $item->quantity,
                'status' => 'Pending',
            ]);
        }

        // Clear the cart after checkout
        Cart::where('user_id', Auth::id())->delete();

        flash()->success('Order placed successfully!');
        return redirect()->route('marketplace.admin.store');
    }
}
