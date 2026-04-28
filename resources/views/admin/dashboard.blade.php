@extends('layouts.admin')
@section('header', 'Dashboard')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
        <h3 class="text-gray-400 text-sm font-medium">Produits</h3>
        <p class="text-3xl font-bold text-white mt-2">{{ $productsCount }}</p>
    </div>
    <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
        <h3 class="text-gray-400 text-sm font-medium">Commandes</h3>
        <p class="text-3xl font-bold text-white mt-2">{{ $ordersCount }}</p>
    </div>
    <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
        <h3 class="text-gray-400 text-sm font-medium">Clients</h3>
        <p class="text-3xl font-bold text-white mt-2">{{ $usersCount }}</p>
    </div>
    <div class="bg-card p-6 rounded-lg border border-border shadow-sm">
        <h3 class="text-gray-400 text-sm font-medium">Retours en attente</h3>
        <p class="text-3xl font-bold text-red-500 mt-2">{{ $returnsCount }}</p>
    </div>
</div>
<div class="bg-card rounded-lg border border-border shadow-sm p-6">
    <h3 class="text-lg font-semibold mb-4 text-white">Commandes Récentes</h3>
    <table class="min-w-full divide-y divide-border">
        <thead>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Client</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Statut</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border">
            @foreach($recentOrders as $order)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">#{{ $order->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $order->guest_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $order->total }} MAD</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $order->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
