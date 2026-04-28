<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Pack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        return view('front.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'type' => 'required|in:product,pack',
            'qty' => 'required|integer|min:1'
        ]);

        $cart = Session::get('cart', []);
        $itemId = $request->type . '_' . $request->id;

        if ($request->type === 'product') {
            $item = Product::with('images')->findOrFail($request->id);
            if ($item->stock < $request->qty) {
                return response()->json(['success' => false, 'message' => 'Stock insuffisant'], 400);
            }
            $name = $item->name;
            $price = $item->price;
            $image = $item->main_image_url;
        } else {
            $item = Pack::with('products')->findOrFail($request->id);
            // Check stock of all products in pack
            foreach ($item->products as $packProduct) {
                if ($packProduct->stock < ($packProduct->pivot->quantity * $request->qty)) {
                    return response()->json(['success' => false, 'message' => 'Stock insuffisant pour un des produits du pack'], 400);
                }
            }
            $name = $item->name;
            $price = $item->price;
            $image = $item->image_url;
        }

        if (isset($cart[$itemId])) {
            $cart[$itemId]['qty'] += $request->qty;
        } else {
            $cart[$itemId] = [
                'id' => $request->id,
                'type' => $request->type,
                'name' => $name,
                'price' => $price,
                'qty' => $request->qty,
                'image' => $image
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Ajouté au panier',
            'cartCount' => array_sum(array_column($cart, 'qty')),
            'cart' => $cart
        ]);
    }

    public function remove(Request $request)
    {
        $cart = Session::get('cart', []);
        $itemId = $request->type . '_' . $request->id;

        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            Session::put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cartCount' => array_sum(array_column($cart, 'qty')),
            'cart' => $cart,
            'total' => $this->calculateTotal($cart)
        ]);
    }

    public function update(Request $request)
    {
        $cart = Session::get('cart', []);
        $itemId = $request->type . '_' . $request->id;

        if (isset($cart[$itemId])) {
            $cart[$itemId]['qty'] = $request->qty;
            Session::put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cartCount' => array_sum(array_column($cart, 'qty')),
            'cart' => $cart,
            'total' => $this->calculateTotal($cart)
        ]);
    }

    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }
        return $total;
    }
}
