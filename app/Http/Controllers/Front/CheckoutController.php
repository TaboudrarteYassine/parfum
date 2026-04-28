<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Pack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        return view('front.checkout', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $rules = [
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'guest_address' => 'required|string',
        ];

        if (Auth::check()) {
            $rules = [
                'guest_name' => 'nullable|string|max:255',
                'guest_email' => 'nullable|email|max:255',
                'guest_phone' => 'nullable|string|max:20',
                'guest_address' => 'required|string',
            ];
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['qty'];
            }

            $order = new Order();
            if (Auth::check()) {
                $order->user_id = Auth::id();
                $order->guest_name = Auth::user()->name;
                $order->guest_email = Auth::user()->email;
                $order->guest_phone = Auth::user()->phone ?? $request->guest_phone;
                $order->guest_address = $request->guest_address ?? Auth::user()->address;
            } else {
                $order->guest_name = $validated['guest_name'];
                $order->guest_email = $validated['guest_email'];
                $order->guest_phone = $validated['guest_phone'];
                $order->guest_address = $validated['guest_address'];
            }

            $order->total = $total;
            $order->status = 'pending';
            $order->tracking_code = strtoupper(Str::random(10));
            $order->save();

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['type'] === 'product' ? $item['id'] : null,
                    'pack_id' => $item['type'] === 'pack' ? $item['id'] : null,
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);

                // Update stock
                if ($item['type'] === 'product') {
                    Product::where('id', $item['id'])->decrement('stock', $item['qty']);
                } else {
                    $pack = Pack::with('products')->find($item['id']);
                    foreach ($pack->products as $packProduct) {
                        Product::where('id', $packProduct->id)->decrement('stock', $packProduct->pivot->quantity * $item['qty']);
                    }
                }
            }

            DB::commit();
            Session::forget('cart');

            return redirect()->route('checkout.success', $order->tracking_code);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la commande: ' . $e->getMessage());
        }
    }

    public function success($trackingCode)
    {
        $order = Order::where('tracking_code', $trackingCode)->firstOrFail();
        return view('front.success', compact('order'));
    }

    public function track(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate(['tracking_code' => 'required|string']);
            $order = Order::where('tracking_code', $request->tracking_code)->first();
            return view('front.track', compact('order'));
        }
        return view('front.track');
    }
}
