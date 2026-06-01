<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);
        $categories = Category::all();
        $brands = Brand::all();

        return view('front.products.index', compact('products', 'categories', 'brands'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'images']);
        $relatedProducts = Product::where('category_id', $product->category_id)
                                  ->where('id', '!=', $product->id)
                                  ->with('images')
                                  ->take(4)->get();

        return view('front.products.show', compact('product', 'relatedProducts'));
    }
}
