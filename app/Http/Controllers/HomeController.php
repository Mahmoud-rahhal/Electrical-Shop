<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::withCount('reviews') // This gives reviews_count
    ->with('reviews') // Load reviews to calculate average_rating manually
    ->where('featured', true)
    ->where('status', true)
    ->take(8)
    ->get()
    ->map(function ($product) {
        $product->average_rating = round($product->reviews->avg('rating'), 1); // Manually add attribute
        return $product;
    });

$newArrivals = Product::withCount('reviews')
    ->with('reviews')
    ->where('status', true)
    ->latest()
    ->take(8)
    ->get()
    ->map(function ($product) {
        $product->average_rating = round($product->reviews->avg('rating'), 1);
        return $product;
    });

        $categories = Category::withCount('products')->having('products_count', '>', 0)->take(6)->get();

        return view('home', compact('featuredProducts', 'newArrivals', 'categories'));
    }

    public function shop(Request $request)
    {
        $query = Product::query()->where('status', true);

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'popular':
                    // You might want to implement a popularity metric
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
        $categories = Category::withCount('products')->having('products_count', '>', 0)->get();

        return view('shop', compact('products', 'categories'));
    }

    public function product(Product $product)
    {
        if (!$product->status) {
            abort(404);
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->take(4)
            ->get();

        $product->load('category', 'reviews.user');

        return view('product', compact('product', 'relatedProducts'));
    }

    public function category(Category $category)
    {
        $products = $category->products()->where('status', true)->paginate(12);
        return view('category', compact('category', 'products'));
    }
}