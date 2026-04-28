<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Retour;

class AdminController extends Controller
{
    public function dashboard()
    {
        $productsCount = Product::count();
        $ordersCount = Order::count();
        $usersCount = User::where('role', 'user')->count();
        $returnsCount = Retour::where('status', 'pending')->count();
        
        $recentOrders = Order::latest()->take(5)->get();
        
        return view('admin.dashboard', compact('productsCount', 'ordersCount', 'usersCount', 'returnsCount', 'recentOrders'));
    }
}
