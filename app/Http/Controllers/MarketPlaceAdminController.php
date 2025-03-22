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
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('products.vendor.user') // Eager load products and their vendors
            ->orderBy('created_at', 'desc')
            ->get();

        return view('marketplace.admin.orders', compact('orders'));
    }
    public function cancelOrder(Request $request, $orderId)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($orderId);

        if ($order->status !== 'Pending') {
            flash()->error('Only pending orders can be canceled.');
            return redirect()->route('marketplace.admin.orders');
        }

        // Restore stock for items
        foreach ($order->products as $product) {
            if ($product->type === 'items') {
                $product->increment('stock', $product->pivot->quantity);
            }
        }

        // Update related purchase orders to "Canceled"
        PurchaseOrder::whereIn('description', $order->products->map(fn($p) => "Order for {$p->name} (Qty: {$p->pivot->quantity})"))
            ->where('status', 'Pending')
            ->update(['status' => 'Canceled']);

        // Update order status
        $order->update(['status' => 'Canceled']);

        flash()->success("Order #{$order->id} has been canceled.");
        return redirect()->route('marketplace.admin.orders');
    }
    public function cart()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product.vendor.user')->get();
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
            // flash()->success('Item removed from cart!');
        }

        return redirect()->route('marketplace.admin.cart');
    }

    public function removeSelected(Request $request)
    {
        $selectedItems = $request->input('selected_items', []);
        if (empty($selectedItems)) {
            flash()->error('No items selected for removal.');
            return redirect()->route('marketplace.admin.cart');
        }

        $cartItems = Cart::where('user_id', Auth::id())->whereIn('id', $selectedItems)->get();
        foreach ($cartItems as $cartItem) {
            if ($cartItem->product->type === 'items') {
                $cartItem->product->increment('stock', $cartItem->quantity);
            }
            $cartItem->delete();
        }

        // flash()->success('Selected items removed from your cart.');
        return redirect()->route('marketplace.admin.cart');
    }

    public function updateQuantity(Request $request)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($request->cart_item_id);
        $change = $request->change;

        $newQuantity = $cartItem->quantity + $change;
        if ($newQuantity < 1) {
            $newQuantity = 1; // Minimum quantity
        } elseif ($cartItem->product->type === 'items' && $newQuantity > $cartItem->product->stock) {
            $newQuantity = $cartItem->product->stock; // Max stock limit
        }

        $quantityDifference = $newQuantity - $cartItem->quantity;
        if ($quantityDifference !== 0 && $cartItem->product->type === 'items') {
            $cartItem->product->increment('stock', -$quantityDifference); // Adjust stock
        }

        $cartItem->update(['quantity' => $newQuantity]);

        return response()->json(['success' => true, 'new_quantity' => $newQuantity]);
    }

    public function checkout(Request $request)
    {
        $selectedIds = $request->input('selected', []);
        if (empty($selectedIds)) {
            flash()->error('Please select at least one item to checkout.');
            return redirect()->route('marketplace.admin.cart');
        }

        $cartItems = Cart::where('user_id', Auth::id())->whereIn('id', $selectedIds)->get();
        if ($cartItems->isEmpty()) {
            flash()->error('No valid items selected for checkout.');
            return redirect()->route('marketplace.admin.cart');
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $cartItems->sum(fn($item) => $item->product->price * $item->quantity),
            'status' => 'Pending',
        ]);

        foreach ($cartItems->groupBy('product.vendor_id') as $vendorId => $items) {
            $vendorTotal = $items->sum(fn($item) => $item->product->price * $item->quantity);
            $poNumber = 'PO-' . strtoupper(uniqid());
            $description = $items->map(fn($item) => "Order for {$item->product->name} (Qty: {$item->quantity})")->implode(', ');

            PurchaseOrder::create([
                'order_id' => $order->id, // Link to the order
                'vendor_id' => $vendorId,
                'po_number' => $poNumber,
                'description' => $description,
                'amount' => $vendorTotal,
                'status' => 'Pending',
            ]);
        }

        $order->products()->attach(
            $cartItems->mapWithKeys(fn($item) => [
                $item->product_id => ['quantity' => $item->quantity, 'price' => $item->product->price]
            ])->toArray()
        );

        Cart::whereIn('id', $selectedIds)->delete();

        flash()->success('Checkout successful! Your order has been placed.');
        return redirect()->route('marketplace.admin.orders');
    }

    public function buyNow(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        if ($product->type === 'items' && $product->stock <= 0) {
            flash()->error('Product out of stock!');
            return redirect()->route('marketplace.admin.store');
        }

        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $product->id)->first();
        $quantity = 1;

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($product->type === 'items' && $newQuantity > $product->stock) {
                flash()->error('Cannot add more than available stock!');
                return redirect()->route('marketplace.admin.store');
            }
            $cartItem->update(['quantity' => $newQuantity]);
            // flash()->success("Increased quantity of {$product->name} in your cart.");
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
            // flash()->success("Added {$product->name} to your cart.");
        }

        if ($product->type === 'items') {
            $product->decrement('stock', $quantity);
        }

        return redirect()->route('marketplace.admin.cart');
    }
}
