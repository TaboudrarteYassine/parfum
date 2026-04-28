<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Retour;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'items.pack', 'user', 'retour');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,delivered',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Statut de la commande mis à jour.');
    }

    /**
     * Admin manually registers a physical return (client refused at door or returned to livreur).
     * This restores stock and creates a retour record marked as accepted immediately.
     */
    public function manualReturn(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        // Prevent duplicate returns
        if ($order->retour) {
            return redirect()->back()->with('error', 'Un retour existe déjà pour cette commande.');
        }

        // Create the retour record — accepted immediately since admin confirmed it physically
        Retour::create([
            'user_id'      => $order->user_id,
            'commande_id'  => $order->id,
            'reason'       => $request->reason,
            'status'       => 'accepted', // auto-accepted: livreur already brought it back
        ]);

        // Restore stock for every item in the order
        foreach ($order->items as $item) {
            if ($item->product_id && $item->product) {
                $item->product->increment('stock', $item->quantity);
            } elseif ($item->pack_id && $item->pack) {
                foreach ($item->pack->products as $packProduct) {
                    $packProduct->increment('stock', $item->quantity * $packProduct->pivot->quantity);
                }
            }
        }

        // Set order status back to "cancelled" — it was never truly delivered
        $order->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Retour manuel enregistré et stock restauré.');
    }
}
