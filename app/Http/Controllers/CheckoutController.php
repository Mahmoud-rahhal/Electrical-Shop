<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        } else {
            $cartItems = Cart::with('product')->where('session_id', session()->getId())->get();
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('checkout.index', compact('cartItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'post_code' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'payment_method' => 'required|string|in:cod,card',
        ]);

        if (Auth::check()) {
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        } else {
            $cartItems = Cart::with('product')->where('session_id', session()->getId())->get();
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Check product quantities
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')->with('error', 'Product ' . $item->product->name . ' has only ' . $item->product->quantity . ' items available.');
            }
        }

        // Calculate total
        $grandTotal = 0;
        foreach ($cartItems as $item) {
            $grandTotal += $item->product->sale_price ? $item->product->sale_price * $item->quantity : $item->product->price * $item->quantity;
        }

        // Create order
        $order = Order::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'status' => 'pending',
            'total_amount' => $grandTotal,
            'item_count' => $cartItems->count(),
            'payment_status' => $request->payment_method === 'cod' ? false : true,
            'payment_method' => $request->payment_method,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'post_code' => $request->post_code,
            'phone_number' => $request->phone_number,
            'notes' => $request->notes,
        ]);

        // Create order items and update product quantities
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->sale_price ? $item->product->sale_price : $item->product->price,
            ]);

            // Update product quantity
            $item->product->decrement('stock', $item->quantity);
        }

        // Clear cart
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        } else {
            Cart::where('session_id', session()->getId())->delete();
        }

        return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully.');
    }
}