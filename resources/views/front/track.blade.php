@extends('layouts.app')
@section('title', 'Suivre ma Commande')
@section('content')
<div class="max-w-2xl mx-auto px-4 py-16">
    <h1 class="text-3xl font-bold mb-8 text-center">Suivre ma Commande</h1>

    <div class="bg-card rounded-xl border border-border p-8 mb-8">
        <form method="POST" action="{{ route('track.order') }}" class="flex gap-4">
            @csrf
            <input type="text" name="tracking_code"
                   value="{{ old('tracking_code', request('tracking_code')) }}"
                   placeholder="Ex: ABCDE12345"
                   class="flex-1 bg-dark border border-border rounded-lg px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-gray-500 font-mono uppercase"
                   required>
            <button type="submit" class="bg-white text-black font-bold px-6 py-3 rounded-lg hover:bg-gray-200 transition whitespace-nowrap">
                <i class="fas fa-search mr-2"></i>Rechercher
            </button>
        </form>
    </div>

    @if(isset($order))
        @if($order)
        <div class="bg-card rounded-xl border border-border overflow-hidden">
            <div class="p-6 border-b border-border">
                <div class="flex justify-between items-start flex-wrap gap-4">
                    <div>
                        <p class="text-sm text-gray-400 mb-1">Numéro de commande</p>
                        <p class="text-xl font-bold text-white">#{{ $order->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 mb-1">Date de commande</p>
                        <p class="font-medium">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div>
                        <span class="px-4 py-2 rounded-full text-sm font-bold uppercase {{ $order->status_badge }}">
                            @php
                                $labels = ['pending'=>'En attente','confirmed'=>'Confirmée','shipped'=>'Expédiée','delivered'=>'Livrée','cancelled'=>'Annulée'];
                            @endphp
                            {{ $labels[$order->status] ?? $order->status }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="p-6 border-b border-border">
                <h3 class="font-bold mb-6 text-white">Progression</h3>
                <div class="flex items-center justify-between relative">
                    <div class="absolute top-4 left-0 right-0 h-0.5 bg-border z-0"></div>
                    @php
                        $steps = ['pending','confirmed','shipped','delivered'];
                        $stepLabels = ['En attente','Confirmée','Expédiée','Livrée'];
                        $stepIcons = ['fa-clock','fa-check','fa-truck','fa-home'];
                        $currentIndex = array_search($order->status, $steps);
                        $currentIndex = $currentIndex === false ? 0 : $currentIndex;
                    @endphp
                    @foreach($steps as $i => $step)
                    <div class="flex flex-col items-center z-10">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $i <= $currentIndex ? 'bg-white text-black' : 'bg-dark border border-border text-gray-500' }}">
                            <i class="fas {{ $stepIcons[$i] }}"></i>
                        </div>
                        <span class="text-xs mt-2 {{ $i <= $currentIndex ? 'text-white' : 'text-gray-500' }}">{{ $stepLabels[$i] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Delivery Info -->
            <div class="p-6 border-b border-border">
                <h3 class="font-bold mb-4 text-white">Adresse de livraison</h3>
                <p class="text-gray-300">{{ $order->guest_name }}</p>
                <p class="text-gray-400 text-sm mt-1">{{ $order->guest_address }}</p>
                <p class="text-gray-400 text-sm">{{ $order->guest_phone }}</p>
            </div>

            <!-- Total -->
            <div class="p-6 flex justify-between items-center">
                <span class="text-gray-400">Total payé</span>
                <span class="text-2xl font-bold text-white">{{ $order->total }} MAD</span>
            </div>
        </div>
        @else
        <div class="bg-card rounded-xl border border-red-900 p-8 text-center">
            <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
            <p class="text-lg text-gray-300">Aucune commande trouvée pour ce numéro de suivi.</p>
            <p class="text-sm text-gray-500 mt-2">Vérifiez le code et réessayez.</p>
        </div>
        @endif
    @endif
</div>
@endsection

