<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Retour;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $productsCount = Product::count();
        $ordersCount = Order::count();
        $usersCount = User::where('role', 'user')->count();
        $returnsPending = Retour::where('status', 'pending')->count();

        $recentOrders = Order::withCount('items')->latest()->take(5)->get();

        // Revenue stats
        $totalRevenue = Order::whereNotIn('status', ['cancelled'])->sum('total');
        $todayRevenue = Order::whereNotIn('status', ['cancelled'])
            ->whereDate('created_at', today())->sum('total');
        $monthRevenue = Order::whereNotIn('status', ['cancelled'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->sum('total');

        // Order status breakdown
        $pendingOrders = Order::where('status', 'pending')->count();
        $confirmedOrders = Order::where('status', 'confirmed')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        // Low stock products
        $lowStockProducts = Product::where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->get();
        $outOfStockProducts = Product::where('stock', '<=', 0)->get();

        // Top selling products (by order items)
        $topProducts = DB::table('order_items')
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->product = Product::find($item->product_id);
                return $item;
            })
            ->filter(fn($item) => $item->product !== null);

        // Orders per day (last 7 days)
        $ordersPerDay = Order::where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $last7Days->push([
                'date' => now()->subDays($i)->format('d/m'),
                'total' => $ordersPerDay->get($date)->total ?? 0,
            ]);
        }

        return view('admin.dashboard', compact(
            'productsCount', 'ordersCount', 'usersCount', 'returnsPending',
            'recentOrders', 'totalRevenue', 'todayRevenue', 'monthRevenue',
            'pendingOrders', 'confirmedOrders', 'shippedOrders', 'deliveredOrders', 'cancelledOrders',
            'lowStockProducts', 'outOfStockProducts', 'topProducts', 'last7Days'
        ));
    }
}
