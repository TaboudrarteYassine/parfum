<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pack;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PackController extends Controller
{
    public function index()
    {
        $packs = Pack::with('products')->paginate(10);
        return view('admin.packs.index', compact('packs'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.packs.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('packs', 'public');
        }

        $pack = Pack::create($validated);

        if (!empty($request->products)) {
            foreach ($request->products as $index => $productId) {
                $qty = $request->quantities[$index] ?? 1;
                $pack->products()->attach($productId, ['quantity' => $qty]);
            }
        }

        return redirect()->route('admin.packs.index')->with('success', 'Pack créé.');
    }

    public function edit(Pack $pack)
    {
        $products = Product::all();
        return view('admin.packs.edit', compact('pack', 'products'));
    }

    public function update(Request $request, Pack $pack)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1'
        ]);

        if ($request->hasFile('image')) {
            if ($pack->image) {
                Storage::disk('public')->delete($pack->image);
            }
            $validated['image'] = $request->file('image')->store('packs', 'public');
        }

        $pack->update($validated);

        $syncData = [];
        if (!empty($request->products)) {
            foreach ($request->products as $index => $productId) {
                $qty = $request->quantities[$index] ?? 1;
                $syncData[$productId] = ['quantity' => $qty];
            }
        }
        $pack->products()->sync($syncData);

        return redirect()->route('admin.packs.index')->with('success', 'Pack mis à jour.');
    }

    public function destroy(Pack $pack)
    {
        if ($pack->image) {
            Storage::disk('public')->delete($pack->image);
        }
        $pack->delete();
        return redirect()->route('admin.packs.index')->with('success', 'Pack supprimé.');
    }
}
