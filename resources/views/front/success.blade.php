@extends('layouts.app')
@section('title', 'Commande Confirmée')
@section('content')
<div class="max-w-2xl mx-auto px-4 py-24 text-center">
    <!-- Success Icon -->
    <div class="w-24 h-24 bg-green-900/30 border border-green-800 text-green-500 rounded-full flex items-center justify-center mx-auto mb-8">
        <i class="fas fa-check text-4xl"></i>
    </div>

    <h1 class="text-4xl font-bold text-white mb-4">Commande confirmée !</h1>
    <p class="text-xl text-gray-400 mb-12">Merci pour votre achat. Votre commande est en cours de préparation.</p>

    <!-- Tracking Code -->
    <div class="bg-card rounded-2xl border border-border p-8 mb-8">
        <p class="text-sm text-gray-400 uppercase tracking-widest mb-3">Votre numéro de suivi</p>
        <p class="text-4xl font-mono font-bold text-white tracking-widest mb-4">{{ $order->tracking_code }}</p>
        <p class="text-sm text-gray-600">Conservez ce code pour suivre votre livraison</p>
        <button onclick="navigator.clipboard.writeText('{{ $order->tracking_code }}').then(() => this.innerHTML='<i class=\'fas fa-check mr-1\'></i>Copié !')"
                class="mt-4 inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors border border-border rounded-full px-4 py-2 hover:border-gray-500">
            <i class="fas fa-copy"></i> Copier le code
        </button>
    </div>

    <!-- Order Info -->
    <div class="bg-card rounded-2xl border border-border p-6 mb-10 text-left">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500 mb-1">Livraison pour</p>
                <p class="text-white font-medium">{{ $order->guest_name }}</p>
            </div>
            <div>
                <p class="text-gray-500 mb-1">Total payé</p>
                <p class="text-white font-bold text-lg">{{ number_format($order->total, 2) }} MAD</p>
            </div>
            <div class="col-span-2">
                <p class="text-gray-500 mb-1">Adresse de livraison</p>
                <p class="text-white">{{ $order->guest_address }}</p>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('track.order') }}?tracking_code={{ $order->tracking_code }}"
           class="inline-flex items-center gap-2 bg-white text-black px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors">
            <i class="fas fa-map-marker-alt"></i> Suivre ma commande
        </a>
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 border border-border text-white px-8 py-3 rounded-full font-semibold hover:border-gray-500 transition-colors">
            <i class="fas fa-arrow-left"></i> Retour à l'accueil
        </a>
    </div>
</div>
@endsection
