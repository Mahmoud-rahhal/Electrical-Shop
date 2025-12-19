<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        } else {
            $cartItems = Cart::with('product')->where('session_id', session()->getId())->get();
        }

        return view('cart.index', compact('cartItems'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        $cartItem = Cart::where('product_id', $product->id);

        if (Auth::check()) {
            $cartItem = $cartItem->where('user_id', Auth::id());
        } else {
            $cartItem = $cartItem->where('session_id', session()->getId());
        }

        $cartItem = $cartItem->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'session_id' => Auth::check() ? null : session()->getId(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully.');
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cart->product->stock,
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Product removed from cart successfully.');
    }

    public function clear()
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        } else {
            Cart::where('session_id', session()->getId())->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully.');
    }
}