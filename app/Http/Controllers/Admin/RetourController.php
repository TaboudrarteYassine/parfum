<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Retour;
use Illuminate\Http\Request;

class RetourController extends Controller
{
    public function index()
    {
        $retours = Retour::with(['user', 'order'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.retours.index', compact('retours'));
    }

    public function show(Retour $retour)
    {
        $retour->load(['user', 'order.items']);
        return view('admin.retours.show', compact('retour'));
    }

    public function updateStatus(Request $request, Retour $retour)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $retour->update(['status' => $request->status]);

        // If accepted, restore stock (basic implementation)
        if ($request->status === 'accepted') {
            foreach ($retour->order->items as $item) {
                if ($item->product_id) {
                    $item->product->increment('stock', $item->quantity);
                } elseif ($item->pack_id) {
                    foreach ($item->pack->products as $packProduct) {
                        $packProduct->increment('stock', $item->quantity * $packProduct->pivot->quantity);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Statut du retour mis à jour.');
    }
}
