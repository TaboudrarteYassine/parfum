@extends('layouts.admin')
@section('header', 'Finances')
@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
            <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Revenu Total</p>
            <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalRevenue, 2) }} MAD</p>
        </div>
        <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
            <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Aujourd'hui</p>
            <p class="text-3xl font-bold text-green-400 mt-1">{{ number_format($todayRevenue, 2) }} MAD</p>
        </div>
        <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
            <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Cette Semaine</p>
            <p class="text-3xl font-bold text-blue-400 mt-1">{{ number_format($weekRevenue, 2) }} MAD</p>
        </div>
        <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
            <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Ce Mois</p>
            <p class="text-3xl font-bold text-purple-400 mt-1">{{ number_format($monthRevenue, 2) }} MAD</p>
        </div>
        <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
            <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Cette Année</p>
            <p class="text-3xl font-bold text-yellow-400 mt-1">{{ number_format($yearRevenue, 2) }} MAD</p>
        </div>
        <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
            <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Prix Moyen / Commande</p>
            <p class="text-3xl font-bold text-white mt-1">{{ number_format($avgOrderValue, 2) }} MAD</p>
            <p class="text-xs text-gray-500 mt-1">{{ $totalOrders }} commandes ({{ $cancelledOrders }} annulées)</p>
        </div>
        <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
            <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Revenu Annulé</p>
            <p class="text-3xl font-bold text-red-400 mt-1">{{ number_format($cancelledRevenue, 2) }} MAD</p>
        </div>
        <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
            <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Commandes Traitées</p>
            <p class="text-3xl font-bold text-white mt-1">{{ $totalOrders - $cancelledOrders }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Revenue by Month -->
        <div class="bg-card rounded-lg border border-border shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-white">Revenu par Mois ({{ now()->year }})</h3>
            @php $maxMonth = $months->max('total'); @endphp
            <!-- Desktop: line chart -->
            <div class="hidden sm:block">
                @php
                    $maxM = max(1, $months->max('total'));
                    $svgW = 600; $svgH = 200; $pad = 35;
                    $cnt = count($months);
                    $lnPts = '';
                    $fillPts = '';
                    foreach ($months as $i => $m) {
                        $x = $pad + ($i / max(1, $cnt - 1)) * ($svgW - 2 * $pad);
                        $y = $svgH - $pad - (($m['total'] / $maxM) * ($svgH - 2 * $pad));
                        $lnPts .= "$x,$y ";
                        $fillPts .= "$x,$y ";
                    }
                    $lx = $pad + (($cnt - 1) / max(1, $cnt - 1)) * ($svgW - 2 * $pad);
                    $fx = $pad;
                    $by = $svgH - $pad;
                    $fillPts .= "$lx,$by $fx,$by";
                @endphp
                <svg viewBox="0 0 {{ $svgW }} {{ $svgH }}" class="w-full h-52">
                    <defs>
                        <linearGradient id="monthGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.3"/>
                            <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    @for($g = 0; $g <= 4; $g++)
                        @php $gy = $pad + ($g / 4) * ($svgH - 2 * $pad); @endphp
                        <line x1="{{ $pad }}" y1="{{ $gy }}" x2="{{ $svgW - $pad }}" y2="{{ $gy }}" stroke="#2a2a2a" stroke-width="1"/>
                    @endfor
                    <polygon points="{{ $fillPts }}" fill="url(#monthGrad)"/>
                    <polyline points="{{ $lnPts }}" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round"/>
                    @foreach($months as $i => $m)
                        @php
                            $x = $pad + ($i / max(1, $cnt - 1)) * ($svgW - 2 * $pad);
                            $y = $svgH - $pad - (($m['total'] / $maxM) * ($svgH - 2 * $pad));
                        @endphp
                        <circle cx="{{ $x }}" cy="{{ $y }}" r="3.5" fill="#3b82f6" stroke="#1a1a1a" stroke-width="2"/>
                        <text x="{{ $x }}" y="{{ $y - 10 }}" text-anchor="middle" fill="#9ca3af" font-size="10" font-weight="bold">{{ number_format($m['total'], 0) }}</text>
                        <text x="{{ $x }}" y="{{ $svgH - 8 }}" text-anchor="middle" fill="#6b7280" font-size="10">{{ $m['label'] }}</text>
                    @endforeach
                </svg>
            </div>
            <!-- Mobile: donut chart by quarter -->
            <div class="sm:hidden">
                @php
                    $qColors = ['#3b82f6', '#22c55e', '#a855f7', '#f59e0b'];
                    $quarters = [];
                    $qLabels = ['T1', 'T2', 'T3', 'T4'];
                    $qMonthRanges = [['Jan','Feb','Mar'], ['Apr','May','Jun'], ['Jul','Aug','Sep'], ['Oct','Nov','Dec']];
                    $qTotal = 0;
                    foreach ($qLabels as $i => $label) {
                        $total = collect($months)->filter(fn($m) => in_array($m['label'], $qMonthRanges[$i]))->sum('total');
                        $quarters[] = ['label' => $label, 'total' => $total, 'color' => $qColors[$i]];
                        $qTotal += $total;
                    }
                    $qTotal = max(1, $qTotal);
                    $conicParts = '';
                    $cumAngle = 0;
                    foreach ($quarters as $i => $q) {
                        $angle = ($q['total'] / $qTotal) * 360;
                        if ($q['total'] > 0) {
                            $conicParts .= ($i > 0 ? ', ' : '') . $q['color'] . ' ' . round($cumAngle) . 'deg ' . round($cumAngle + $angle) . 'deg';
                            $cumAngle += $angle;
                        }
                    }
                @endphp
                <div class="flex items-center gap-6">
                    <div class="relative w-36 h-36 flex-shrink-0">
                        <div class="w-full h-full rounded-full" style="background: conic-gradient({{ $conicParts }})"></div>
                        <div class="absolute inset-3 bg-card rounded-full flex items-center justify-center flex-col">
                            <span class="text-xs text-gray-500">Total</span>
                            <span class="text-lg font-bold text-white">{{ number_format($qTotal, 0) }}</span>
                        </div>
                    </div>
                    <div class="flex-1 space-y-2">
                        @foreach($quarters as $q)
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full flex-shrink-0" style="background: {{ $q['color'] }}"></span>
                                <span class="text-xs text-gray-400 w-6">{{ $q['label'] }}</span>
                                <span class="text-xs text-gray-300 font-bold flex-1 text-right">{{ number_format($q['total'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue by day (last 30 days) -->
        <div class="bg-card rounded-lg border border-border shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-white">Revenu (30 derniers jours)</h3>
            @php $maxDay = max(1, $last30Days->max('total')); @endphp
            <!-- Desktop: line chart -->
            <div class="hidden sm:block">
                @php
                    $maxD = max(1, $last30Days->max('total'));
                    $svgW = 700; $svgH = 200; $pad = 40;
                    $cnt = count($last30Days);
                    $lnPts = '';
                    $fillPts = '';
                    foreach ($last30Days as $i => $d) {
                        $x = $pad + ($i / max(1, $cnt - 1)) * ($svgW - 2 * $pad);
                        $y = $svgH - $pad - (($d['total'] / $maxD) * ($svgH - 2 * $pad));
                        $lnPts .= "$x,$y ";
                        $fillPts .= "$x,$y ";
                    }
                    $lx = $pad + (($cnt - 1) / max(1, $cnt - 1)) * ($svgW - 2 * $pad);
                    $fx = $pad;
                    $by = $svgH - $pad;
                    $fillPts .= "$lx,$by $fx,$by";
                @endphp
                <svg viewBox="0 0 {{ $svgW }} {{ $svgH }}" class="w-full h-52">
                    <defs>
                        <linearGradient id="dayGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#22c55e" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#22c55e" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    @for($g = 0; $g <= 4; $g++)
                        @php $gy = $pad + ($g / 4) * ($svgH - 2 * $pad); @endphp
                        <line x1="{{ $pad }}" y1="{{ $gy }}" x2="{{ $svgW - $pad }}" y2="{{ $gy }}" stroke="#2a2a2a" stroke-width="1"/>
                    @endfor
                    <polygon points="{{ $fillPts }}" fill="url(#dayGrad)"/>
                    <polyline points="{{ $lnPts }}" fill="none" stroke="#22c55e" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>
                    @foreach($last30Days as $i => $d)
                        @php
                            $x = $pad + ($i / max(1, $cnt - 1)) * ($svgW - 2 * $pad);
                            $y = $svgH - $pad - (($d['total'] / $maxD) * ($svgH - 2 * $pad));
                        @endphp
                        <circle cx="{{ $x }}" cy="{{ $y }}" r="2" fill="#22c55e" stroke="#1a1a1a" stroke-width="1.5"/>
                        @if($i % 5 == 0)
                            <text x="{{ $x }}" y="{{ $svgH - 8 }}" text-anchor="middle" fill="#6b7280" font-size="9">{{ $d['date'] }}</text>
                        @endif
                    @endforeach
                </svg>
            </div>
            <!-- Mobile: weekly summary -->
            <div class="sm:hidden space-y-3">
                @php
                    $weeks = collect();
                    $weekTotal = 0;
                    $weekCount = 0;
                    $weekLabel = '';
                    foreach ($last30Days as $i => $day) {
                        $weekTotal += $day['total'];
                        $weekCount++;
                        if ($weekCount == 1) { $weekLabel = $day['date']; }
                        if ($weekCount == 7 || $i == count($last30Days) - 1) {
                            $weeks->push(['label' => $weekLabel, 'total' => $weekTotal]);
                            $weekTotal = 0;
                            $weekCount = 0;
                        }
                    }
                    $weekMax = max(1, $weeks->max('total'));
                @endphp
                @foreach($weeks as $w)
                    @php $wpct = max(5, ($w['total'] / $weekMax) * 100); @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-500 w-16">Sem. {{ $loop->index + 1 }}</span>
                        <div class="flex-1 bg-dark rounded-full h-6 overflow-hidden">
                            <div class="bg-green-500 h-full rounded-full flex items-center justify-end px-2 transition-all"
                                 style="width: {{ $wpct }}%">
                                <span class="text-xs text-white font-bold {{ $wpct < 15 ? 'hidden' : '' }}">
                                    {{ number_format($w['total'], 0) }}
                                </span>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400 w-20 text-right">{{ number_format($w['total'], 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue by Category -->
        <div class="bg-card rounded-lg border border-border shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-white">Revenu par Catégorie</h3>
            @if($revenueByCategory->isNotEmpty())
                @php $maxCat = $revenueByCategory->max('total'); @endphp
                <div class="space-y-2">
                    @foreach($revenueByCategory as $cat)
                        @php $pct = $maxCat > 0 ? max(4, ($cat->total / $maxCat) * 100) : 0; @endphp
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-300 w-32 truncate">{{ $cat->name }}</span>
                            <div class="flex-1 bg-dark rounded-full h-5 overflow-hidden">
                                <div class="bg-purple-500 h-full rounded-full flex items-center justify-end px-2"
                                     style="width: {{ $pct }}%">
                                    <span class="text-xs text-white font-bold {{ $pct < 15 ? 'hidden' : '' }}">
                                        {{ number_format($cat->total, 0) }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 w-24 text-right">{{ number_format($cat->total, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">Aucune donnée.</p>
            @endif
        </div>

        <!-- Top Products by Revenue -->
        <div class="bg-card rounded-lg border border-border shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-white">Top 10 Produits (Revenu)</h3>
            @if($topProducts->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-border">
                        <thead>
                            <tr>
                                <th class="py-2 text-left text-xs font-medium text-gray-400 uppercase">Produit</th>
                                <th class="py-2 text-right text-xs font-medium text-gray-400 uppercase">Vendus</th>
                                <th class="py-2 text-right text-xs font-medium text-gray-400 uppercase">Revenu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach($topProducts as $p)
                            <tr>
                                <td class="py-2 text-sm text-gray-300 truncate max-w-48">{{ $p->name }}</td>
                                <td class="py-2 text-sm text-gray-400 text-right">{{ $p->sold }}</td>
                                <td class="py-2 text-sm text-white font-bold text-right">{{ number_format($p->revenue, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500">Aucune vente pour le moment.</p>
            @endif
        </div>
    </div>
@endsection