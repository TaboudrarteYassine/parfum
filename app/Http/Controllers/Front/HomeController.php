<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Pack;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['category', 'brand', 'images'])->inRandomOrder()->take(8)->get();
        $packs = Pack::with('products')->inRandomOrder()->take(4)->get();
        return view('front.home', compact('featuredProducts', 'packs'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $products = Product::where('name', 'LIKE', "%{$query}%")->with('images')->take(5)->get();
        return response()->json($products);
    }
}
