<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProduitImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand'])->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $product = Product::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProduitImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_featured' => $index === 0
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $product->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProduitImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_featured' => false
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé.');
    }

    public function deleteImage(ProduitImage $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();
        return response()->json(['success' => true]);
    }

    public function setMainImage(ProduitImage $image)
    {
        ProduitImage::where('product_id', $image->product_id)->update(['is_featured' => false]);
        $image->update(['is_featured' => true]);
        return response()->json(['success' => true]);
    }
}
