@extends('layouts.admin')
@section('header', 'Dashboard')
@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Revenu Total</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ number_format($totalRevenue, 2) }} MAD</p>
                </div>
                <div class="w-12 h-12 bg-green-900/30 rounded-full flex items-center justify-center">
                    <i class="fas fa-euro-sign text-green-400 text-xl"></i>
                </div>
            </div>
            <div class="mt-3 flex gap-4 text-xs text-gray-500">
                <span>Aujourd'hui: <strong class="text-white">{{ number_format($todayRevenue, 2) }}</strong></span>
                <span>Ce mois: <strong class="text-white">{{ number_format($monthRevenue, 2) }}</strong></span>
            </div>
        </div>
        <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Commandes</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $ordersCount }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-900/30 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-bag text-blue-400 text-xl"></i>
                </div>
            </div>
            <div class="mt-3 flex gap-3 text-xs text-gray-500 flex-wrap">
                <span class="text-yellow-400">{{ $pendingOrders }} en attente</span>
                <span class="text-blue-400">{{ $confirmedOrders }} confirmée</span>
                <span class="text-purple-400">{{ $shippedOrders }} expédiée</span>
                <span class="text-green-400">{{ $deliveredOrders }} livrée</span>
            </div>
        </div>
        <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Produits</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $productsCount }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-900/30 rounded-full flex items-center justify-center">
                    <i class="fas fa-box text-purple-400 text-xl"></i>
                </div>
            </div>
            <div class="mt-3 flex gap-4 text-xs text-gray-500">
                @php $availableStock = $productsCount - $outOfStockProducts->count(); @endphp
                <span>En stock: <strong class="text-white">{{ $availableStock }}</strong></span>
                <span class="text-red-400">Rupture: {{ $outOfStockProducts->count() }}</span>
            </div>
        </div>
        <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">Clients</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $usersCount }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-900/30 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-yellow-400 text-xl"></i>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-500">
                <span>Retours en attente:
                    <strong class="{{ $returnsPending > 0 ? 'text-red-400' : 'text-green-400' }}">
                        {{ $returnsPending }}
                    </strong>
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Orders Chart (last 7 days) -->
        <div class="bg-card rounded-lg border border-border shadow-sm p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold mb-4 text-white">Commandes (7 derniers jours)</h3>
            @php
                $maxVal = max(1, $last7Days->max('total'));
                $svgW = 600; $svgH = 180; $pad = 30;
                $count = count($last7Days);
                $points = '';
                $fillPoints = '';
                foreach ($last7Days as $i => $day) {
                    $x = $pad + ($i / max(1, $count - 1)) * ($svgW - 2 * $pad);
                    $y = $svgH - $pad - (($day['total'] / $maxVal) * ($svgH - 2 * $pad));
                    $points .= "$x,$y ";
                    $fillPoints .= "$x,$y ";
                }
                // Close fill path to bottom
                $lastX = $pad + (($count - 1) / max(1, $count - 1)) * ($svgW - 2 * $pad);
                $firstX = $pad;
                $bottomY = $svgH - $pad;
                $fillPoints .= "$lastX,$bottomY $firstX,$bottomY";
            @endphp
            <svg viewBox="0 0 {{ $svgW }} {{ $svgH }}" class="w-full h-48">
                <defs>
                    <linearGradient id="lineGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.3"/>
                        <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <!-- Grid lines -->
                @for($g = 0; $g <= 4; $g++)
                    @php $gy = $pad + ($g / 4) * ($svgH - 2 * $pad); @endphp
                    <line x1="{{ $pad }}" y1="{{ $gy }}" x2="{{ $svgW - $pad }}" y2="{{ $gy }}" stroke="#2a2a2a" stroke-width="1"/>
                @endfor
                <!-- Fill area -->
                <polygon points="{{ $fillPoints }}" fill="url(#lineGrad)"/>
                <!-- Line -->
                <polyline points="{{ $points }}" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round"/>
                <!-- Data points -->
                @foreach($last7Days as $i => $day)
                    @php
                        $x = $pad + ($i / max(1, $count - 1)) * ($svgW - 2 * $pad);
                        $y = $svgH - $pad - (($day['total'] / $maxVal) * ($svgH - 2 * $pad));
                    @endphp
                    <circle cx="{{ $x }}" cy="{{ $y }}" r="3.5" fill="#3b82f6" stroke="#1a1a1a" stroke-width="2"/>
                    <text x="{{ $x }}" y="{{ $y - 10 }}" text-anchor="middle" fill="#9ca3af" font-size="11" font-weight="bold">{{ $day['total'] }}</text>
                    <text x="{{ $x }}" y="{{ $svgH - 8 }}" text-anchor="middle" fill="#6b7280" font-size="10">{{ $day['date'] }}</text>
                @endforeach
            </svg>
        </div>

        <!-- Top Selling Products -->
        <div class="bg-card rounded-lg border border-border shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-white">Top 5 Produits</h3>
            @if($topProducts->isNotEmpty())
                <div class="space-y-3">
                    @foreach($topProducts as $index => $item)
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-dark flex items-center justify-center text-xs font-bold text-gray-400">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-white truncate">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-500">{{ $item->total_sold }} vendu(s)</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">Aucune vente pour le moment.</p>
            @endif
        </div>
    </div>

    <!-- Inventory Alerts -->
    @if($lowStockProducts->isNotEmpty() || $outOfStockProducts->isNotEmpty())
    <div class="bg-card rounded-lg border border-orange-900/50 shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-orange-400">
            <i class="fas fa-exclamation-triangle"></i>
            Alertes Stock
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($lowStockProducts->isNotEmpty())
            <div>
                <p class="text-sm text-yellow-400 font-medium mb-2">
                    <i class="fas fa-boxes mr-1"></i> Stock Faible (≤ 5)
                </p>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($lowStockProducts as $product)
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="flex items-center justify-between p-2 rounded bg-dark hover:bg-gray-800 transition-colors">
                            <span class="text-sm text-gray-300 truncate">{{ $product->name }}</span>
                            <span class="text-sm font-bold text-yellow-400 ml-2">{{ $product->stock }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
            @if($outOfStockProducts->isNotEmpty())
            <div>
                <p class="text-sm text-red-400 font-medium mb-2">
                    <i class="fas fa-times-circle mr-1"></i> En Rupture
                </p>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($outOfStockProducts as $product)
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="flex items-center justify-between p-2 rounded bg-dark hover:bg-gray-800 transition-colors">
                            <span class="text-sm text-gray-300 truncate">{{ $product->name }}</span>
                            <span class="text-sm font-bold text-red-400 ml-2">{{ $product->stock }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Recent Orders -->
    <div class="bg-card rounded-lg border border-border shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Commandes Récentes</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-500 hover:text-blue-400">Voir tout</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Articles</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Statut</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($recentOrders as $order)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">#{{ $order->id }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">{{ $order->guest_name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $order->items_count }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">{{ number_format($order->total, 2) }} MAD</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $badges = ['pending' => 'bg-yellow-900/50 text-yellow-400', 'confirmed' => 'bg-blue-900/50 text-blue-400', 'shipped' => 'bg-purple-900/50 text-purple-400', 'delivered' => 'bg-green-900/50 text-green-400', 'cancelled' => 'bg-red-900/50 text-red-400'];
                                    $labels = ['pending' => 'En attente', 'confirmed' => 'Confirmée', 'shipped' => 'Expédiée', 'delivered' => 'Livrée', 'cancelled' => 'Annulée'];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $badges[$order->status] ?? 'bg-gray-900/50 text-gray-400' }}">
                                    {{ $labels[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Aucune commande pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection