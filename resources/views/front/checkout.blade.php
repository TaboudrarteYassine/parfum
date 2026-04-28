@extends('layouts.app')
@section('title', 'Finaliser la commande')
@section('content')

@php $total = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart)); @endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-white mb-10">
        <i class="fas fa-lock text-gray-500 mr-3 text-2xl"></i>Finaliser la commande
    </h1>

    @if($errors->any())
        <div class="bg-red-900/40 border border-red-800 text-red-400 p-4 rounded-xl mb-8">
            <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-5 gap-10">
        @csrf

        <!-- Delivery Info -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-card rounded-2xl border border-border p-6">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-gray-500"></i> Informations de livraison
                </h2>

                @auth
                <div class="bg-dark rounded-xl border border-border p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <p class="text-white font-medium">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-400">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>
                @endauth

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @guest
                    <div>
                        <label class="block text-sm text-gray-400 mb-1.5">Nom complet *</label>
                        <input type="text" name="guest_name" value="{{ old('guest_name') }}"
                               class="w-full bg-dark border border-border rounded-xl px-4 py-3 text-white focus:outline-none focus:border-gray-500" required>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1.5">Email *</label>
                        <input type="email" name="guest_email" value="{{ old('guest_email') }}"
                               class="w-full bg-dark border border-border rounded-xl px-4 py-3 text-white focus:outline-none focus:border-gray-500" required>
                    </div>
                    @endguest

                    <div class="sm:col-span-2">
                        <label class="block text-sm text-gray-400 mb-1.5">Téléphone *</label>
                        <input type="text" name="guest_phone" value="{{ old('guest_phone', auth()->user()->phone ?? '') }}"
                               class="w-full bg-dark border border-border rounded-xl px-4 py-3 text-white focus:outline-none focus:border-gray-500"
                               @auth @if(auth()->user()->phone) @endif @endauth>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm text-gray-400 mb-1.5">Adresse de livraison *</label>
                        <textarea name="guest_address" rows="3"
                                  class="w-full bg-dark border border-border rounded-xl px-4 py-3 text-white focus:outline-none focus:border-gray-500" required>{{ old('guest_address', auth()->user()->address ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Payment Notice -->
            <div class="bg-dark rounded-2xl border border-border p-6">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-credit-card text-gray-500"></i> Paiement
                </h2>
                <div class="flex items-center gap-3 p-4 bg-card rounded-xl border border-border">
                    <i class="fas fa-handshake text-yellow-500 text-xl"></i>
                    <div>
                        <p class="text-white font-medium">Paiement à la livraison</p>
                        <p class="text-sm text-gray-500">Payez en espèces lors de la réception de votre commande.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-2">
            <div class="bg-card rounded-2xl border border-border p-6 sticky top-24">
                <h2 class="text-xl font-bold text-white mb-6">Votre commande</h2>
                <div class="space-y-4 mb-6">
                    @foreach($cart as $item)
                    <div class="flex gap-3">
                        <div class="w-12 h-12 rounded-lg bg-dark border border-border flex-shrink-0 overflow-hidden">
                            @if(!empty($item['image']) && !str_ends_with($item['image'], 'no-image.png'))
                                <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-spray-can text-gray-700 text-sm"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-white font-medium truncate">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-500">Qté : {{ $item['qty'] }}</p>
                        </div>
                        <span class="text-sm font-bold text-white whitespace-nowrap">{{ number_format($item['price'] * $item['qty'], 2) }} MAD</span>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-border pt-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Sous-total</span>
                        <span class="text-white">{{ number_format($total, 2) }} MAD</span>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-gray-400">Livraison</span>
                        <span class="text-green-400 text-sm">Gratuite</span>
                    </div>
                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-border">
                        <span class="text-xl font-bold text-white">Total</span>
                        <span class="text-2xl font-bold text-white">{{ number_format($total, 2) }} MAD</span>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-white text-black font-bold py-4 rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="fas fa-check mr-2"></i>Confirmer la commande
                </button>

                <p class="text-xs text-gray-600 text-center mt-4">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Vos données sont sécurisées
                </p>
            </div>
        </div>
    </form>
</div>
@endsection
