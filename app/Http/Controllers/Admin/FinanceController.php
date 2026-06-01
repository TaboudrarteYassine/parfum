<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index()
    {
        // Revenue totals
        $totalRevenue = Order::whereNotIn('status', ['cancelled'])->sum('total');
        $todayRevenue = Order::whereNotIn('status', ['cancelled'])
            ->whereDate('created_at', today())->sum('total');
        $weekRevenue = Order::whereNotIn('status', ['cancelled'])
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total');
        $monthRevenue = Order::whereNotIn('status', ['cancelled'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->sum('total');
        $yearRevenue = Order::whereNotIn('status', ['cancelled'])
            ->whereYear('created_at', now()->year)->sum('total');

        // Order stats
        $totalOrders = Order::count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        $cancelledRevenue = Order::where('status', 'cancelled')->sum('total');

        // Revenue by month (current year)
        $revenueByMonth = Order::whereNotIn('status', ['cancelled'])
            ->whereYear('created_at', now()->year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total) as total'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $months = collect();
        for ($m = 1; $m <= 12; $m++) {
            $months->push([
                'label' => date('M', mktime(0, 0, 0, $m, 1)),
                'total' => (float) ($revenueByMonth->get($m)->total ?? 0),
            ]);
        }

        // Revenue by day (last 30 days)
        $revenueByDay = Order::whereNotIn('status', ['cancelled'])
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $last30Days = collect();
        for ($d = 29; $d >= 0; $d--) {
            $date = now()->subDays($d)->format('Y-m-d');
            $last30Days->push([
                'date' => now()->subDays($d)->format('d/m'),
                'total' => (float) ($revenueByDay->get($date)->total ?? 0),
            ]);
        }

        // Revenue by product category
        $revenueByCategory = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotIn('orders.status', ['cancelled'])
            ->whereNotNull('order_items.product_id')
            ->select('categories.name', DB::raw('SUM(order_items.price * order_items.quantity) as total'))
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        // Average order value
        $avgOrderValue = Order::whereNotIn('status', ['cancelled'])->avg('total') ?? 0;

        // Top products by revenue
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotIn('orders.status', ['cancelled'])
            ->whereNotNull('order_items.product_id')
            ->select('products.name', DB::raw('SUM(order_items.price * order_items.quantity) as revenue'), DB::raw('SUM(order_items.quantity) as sold'))
            ->groupBy('products.name')
            ->orderByDesc('revenue')
            ->take(10)
            ->get();

        return view('admin.finance.index', compact(
            'totalRevenue', 'todayRevenue', 'weekRevenue', 'monthRevenue', 'yearRevenue',
            'totalOrders', 'cancelledOrders', 'cancelledRevenue',
            'months', 'last30Days', 'revenueByCategory', 'avgOrderValue', 'topProducts'
        ));
    }
}
