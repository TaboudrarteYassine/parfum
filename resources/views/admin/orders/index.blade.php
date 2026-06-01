@extends('layouts.admin')
@section('header', 'Commandes')
@section('content')
<div class="bg-card rounded-lg border border-border shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-border">
        <thead class="bg-dark">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID / Suivi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Client</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border">
            @foreach($orders as $order)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">#{{ $order->id }}<br><span class="text-xs text-gray-500">{{ $order->tracking_code }}</span></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $order->guest_name }}<br><span class="text-xs text-gray-500">{{ $order->guest_email }}</span></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $order->total }} MAD</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                        @csrf
                        <select name="status" onchange="this.form.submit()" class="bg-dark border border-border rounded px-2 py-1 text-white text-xs">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Livrée</option>
                        </select>
                    </form>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank" class="text-gray-400 hover:text-white" title="Imprimer le reçu">
                        <i class="fas fa-receipt"></i>
                    </a>
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-500 hover:text-blue-400">Voir détails</a>
                </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $orders->links() }}</div>
</div>
@endsection
