<?php

namespace App\Helpers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartHelper
{
    public static function countItems()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            return Cart::where('session_id', session()->getId())->sum('quantity');
        }
    }
}