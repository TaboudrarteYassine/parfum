<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Retour;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->orderBy('created_at', 'desc')->paginate(10);
        return view('user.orders', compact('orders'));
    }

    public function returnOrder(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'delivered') {
            return back()->with('error', 'Seules les commandes livrées peuvent être retournées.');
        }

        // Check if within 7 days
        if ($order->updated_at->diffInDays(now()) > 7) {
            return back()->with('error', 'Le délai de 7 jours pour le retour est dépassé.');
        }

        $request->validate([
            'reason' => 'required|string',
        ]);

        Retour::create([
            'user_id' => Auth::id(),
            'commande_id' => $order->id,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Votre demande de retour a été soumise.');
    }
}
