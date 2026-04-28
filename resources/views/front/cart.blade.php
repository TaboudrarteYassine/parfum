@extends('layouts.app')
@section('title', 'Mon Panier')
@section('content')

@php
    $total = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart));
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-white mb-8">Mon Panier
        @if(!empty($cart))
        <span class="text-lg text-gray-500 font-normal ml-2">({{ array_sum(array_column($cart, 'qty')) }} article{{ array_sum(array_column($cart, 'qty')) > 1 ? 's' : '' }})</span>
        @endif
    </h1>

    @if(empty($cart))
        <div class="bg-card rounded-2xl border border-border p-16 text-center">
            <i class="fas fa-shopping-cart text-6xl text-gray-700 mb-6 block"></i>
            <p class="text-xl text-gray-400 mb-6">Votre panier est vide.</p>
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center gap-2 bg-white text-black px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                <i class="fas fa-arrow-left"></i> Continuer vos achats
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4" id="cart-items">
                @foreach($cart as $key => $item)
                <div class="flex gap-5 bg-card rounded-2xl border border-border p-5 hover:border-gray-600 transition-colors" id="row-{{ $key }}">
                    <!-- Image -->
                    <div class="w-24 h-24 rounded-xl overflow-hidden bg-gray-900 flex-shrink-0">
                        @if(!empty($item['image']) && !str_ends_with($item['image'], 'no-image.png'))
                            <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-{{ $item['type'] === 'pack' ? 'gift' : 'spray-can' }} text-3xl text-gray-700"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-2">
                            <div>
                                <span class="inline-block text-xs bg-dark border border-border text-gray-500 px-2 py-0.5 rounded-full mb-1 uppercase">{{ $item['type'] }}</span>
                                <h3 class="text-white font-semibold text-lg truncate">{{ $item['name'] }}</h3>
                            </div>
                            <p class="text-xl font-bold text-white whitespace-nowrap">{{ number_format($item['price'] * $item['qty'], 2) }} MAD</p>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ number_format($item['price'], 2) }} MAD × {{ $item['qty'] }}</p>

                        <!-- Controls -->
                        <div class="flex items-center gap-4 mt-4">
                            <div class="flex items-center gap-2 bg-dark border border-border rounded-full px-3 py-1.5">
                                <button onclick="updateQty('{{ $item['id'] }}', '{{ $item['type'] }}', {{ $item['qty'] - 1 }})"
                                        class="w-6 h-6 flex items-center justify-center hover:text-white text-gray-400 transition-colors">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <span class="w-8 text-center font-bold text-white">{{ $item['qty'] }}</span>
                                <button onclick="updateQty('{{ $item['id'] }}', '{{ $item['type'] }}', {{ $item['qty'] + 1 }})"
                                        class="w-6 h-6 flex items-center justify-center hover:text-white text-gray-400 transition-colors">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                            <button onclick="removeItem('{{ $item['id'] }}', '{{ $item['type'] }}')"
                                    class="text-gray-600 hover:text-red-500 transition-colors text-sm flex items-center gap-1">
                                <i class="fas fa-trash-alt"></i> Retirer
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Summary -->
            <div>
                <div class="bg-card rounded-2xl border border-border p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-white mb-6">Récapitulatif</h3>
                    <div class="space-y-3 mb-6">
                        @foreach($cart as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400 truncate mr-4">{{ $item['name'] }} ×{{ $item['qty'] }}</span>
                            <span class="text-gray-300 whitespace-nowrap">{{ number_format($item['price'] * $item['qty'], 2) }} MAD</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="border-t border-border pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-white">Total</span>
                            <span class="text-2xl font-bold text-white" id="cart-total">{{ number_format($total, 2) }} MAD</span>
                        </div>
                    </div>
                    <a href="{{ route('checkout.index') }}"
                       class="block w-full text-center bg-white text-black font-bold py-4 rounded-xl hover:bg-gray-100 transition-colors">
                        <i class="fas fa-lock mr-2"></i>Passer la commande
                    </a>
                    <a href="{{ route('products.index') }}"
                       class="block w-full text-center text-gray-500 hover:text-white py-3 text-sm transition-colors mt-2">
                        <i class="fas fa-arrow-left mr-1"></i> Continuer vos achats
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function updateQty(id, type, qty) {
        if (qty < 1) { removeItem(id, type); return; }
        axios.post('/cart/update', { id, type, qty }).then(() => location.reload());
    }
    function removeItem(id, type) {
        axios.post('/cart/remove', { id, type }).then(() => location.reload());
    }
</script>
@endpush
