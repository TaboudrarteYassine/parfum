@extends('layouts.app')
@section('title', 'Mes Commandes')
@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center gap-4 mb-10">
        <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center">
            <i class="fas fa-user text-white text-lg"></i>
        </div>
        <div>
            <p class="text-gray-500 text-sm">Connecté en tant que</p>
            <h1 class="text-2xl font-bold text-white">{{ auth()->user()->name }}</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-900/40 border border-green-800 text-green-400 p-4 rounded-xl mb-6 flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-900/40 border border-red-800 text-red-400 p-4 rounded-xl mb-6 flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <h2 class="text-xl font-bold text-white mb-6">Mes Commandes</h2>

    @if($orders->isEmpty())
        <div class="bg-card rounded-2xl border border-border p-16 text-center">
            <i class="fas fa-shopping-bag text-6xl text-gray-700 mb-6 block"></i>
            <p class="text-xl text-gray-400 mb-4">Vous n'avez passé aucune commande.</p>
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center gap-2 bg-white text-black px-6 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                <i class="fas fa-shopping-bag"></i> Commencer à acheter
            </a>
        </div>
    @else
        <div class="space-y-5">
            @foreach($orders as $order)
            <div class="bg-card rounded-2xl border border-border overflow-hidden hover:border-gray-600 transition-colors">
                <!-- Order Header -->
                <div class="flex flex-wrap justify-between items-center p-5 gap-4">
                    <div class="flex items-center gap-6">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Commande</p>
                            <p class="font-bold text-white">#{{ $order->id }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Date</p>
                            <p class="font-medium text-gray-300">{{ $order->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Suivi</p>
                            <p class="font-mono text-sm text-gray-300">{{ $order->tracking_code }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Total</p>
                            <p class="font-bold text-white">{{ number_format($order->total, 2) }} MAD</p>
                        </div>
                    </div>
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase {{ $order->status_badge }}">
                        @php $statusLabels = ['pending'=>'En attente','confirmed'=>'Confirmée','shipped'=>'Expédiée','delivered'=>'Livrée','cancelled'=>'Annulée']; @endphp
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>
                </div>

                <!-- Return Section -->
                @if($order->canBeReturned())
                <div class="px-5 pb-5 pt-0 border-t border-border">
                    <p class="text-sm text-gray-500 mb-3 pt-4">
                        <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                        Vous pouvez demander un retour jusqu'au {{ $order->updated_at->addDays(7)->format('d/m/Y') }}
                    </p>
                    <form action="{{ route('user.orders.return', $order) }}" method="POST" class="flex gap-3">
                        @csrf
                        <input type="text" name="reason" placeholder="Raison du retour..."
                               class="flex-1 bg-dark border border-border rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500" required>
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors whitespace-nowrap">
                            <i class="fas fa-undo mr-1"></i> Retour
                        </button>
                    </form>
                </div>
                @elseif($order->retour)
                <div class="px-5 pb-5 border-t border-border">
                    <div class="flex items-center gap-2 pt-4">
                        <i class="fas fa-undo text-gray-500"></i>
                        <span class="text-sm text-gray-400">Retour demandé — Statut :</span>
                        <span class="text-sm font-bold
                            {{ $order->retour->status === 'accepted' ? 'text-green-400' : ($order->retour->status === 'rejected' ? 'text-red-400' : 'text-yellow-400') }}">
                            {{ ucfirst($order->retour->status) }}
                        </span>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
