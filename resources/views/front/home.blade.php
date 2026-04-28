@extends('layouts.app')
@section('title', 'Accueil - Parfum Store')
@section('content')
<!-- Hero Section -->
<div class="relative h-[80vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/60 to-transparent z-10"></div>
        <img src="https://images.unsplash.com/photo-1585386959984-a4155224a1ad?q=80&w=2000&auto=format&fit=crop"
             class="w-full h-full object-cover">
    </div>
    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-2xl">
            <p class="text-xs tracking-[0.4em] uppercase text-gray-400 mb-4">Collection Exclusive</p>
            <h1 class="text-5xl md:text-7xl font-bold text-white leading-tight mb-6">
                L'Art du<br><span class="italic font-light">Parfum</span>
            </h1>
            <p class="text-lg text-gray-300 mb-10 max-w-lg leading-relaxed">
                Découvrez notre sélection de fragrances rares et envoûtantes, conçues pour ceux qui ne se contentent que du meilleur.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 bg-white text-black px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                    <i class="fas fa-shopping-bag"></i> Explorer la collection
                </a>
                <a href="{{ route('track.order') }}"
                   class="inline-flex items-center gap-2 border border-gray-600 text-white px-8 py-4 rounded-full font-semibold hover:border-white transition-colors">
                    <i class="fas fa-map-marker-alt"></i> Suivre ma commande
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="flex items-end justify-between mb-12">
        <div>
            <p class="text-xs tracking-[0.4em] uppercase text-gray-500 mb-2">Sélection</p>
            <h2 class="text-3xl font-bold text-white">Parfums Phares</h2>
        </div>
        <a href="{{ route('products.index') }}" class="text-sm text-gray-400 hover:text-white transition-colors">
            Voir tout <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($featuredProducts as $product)
        <div class="group bg-card rounded-2xl overflow-hidden border border-border hover:border-gray-600 transition-all duration-300 hover:-translate-y-1">
            <a href="{{ route('products.show', $product) }}" class="block relative h-64 overflow-hidden bg-gray-900">
                @if($product->main_image_url && !str_ends_with($product->main_image_url, 'no-image.png'))
                    <img src="{{ $product->main_image_url }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-900">
                        <i class="fas fa-spray-can text-5xl text-gray-700"></i>
                    </div>
                @endif
                <div class="absolute top-3 right-3">
                    @if($product->stock > 0)
                        <span class="bg-green-900/80 text-green-400 text-xs px-2 py-1 rounded-full">En stock</span>
                    @else
                        <span class="bg-red-900/80 text-red-400 text-xs px-2 py-1 rounded-full">Rupture</span>
                    @endif
                </div>
            </a>
            <div class="p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">{{ $product->brand->name ?? '' }}</p>
                <h3 class="text-white font-semibold mb-1 truncate">
                    <a href="{{ route('products.show', $product) }}" class="hover:text-gray-300 transition-colors">
                        {{ $product->name }}
                    </a>
                </h3>
                <p class="text-xs text-gray-500 mb-4">{{ $product->category->name ?? '' }}</p>
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-white">{{ number_format($product->price, 2) }} MAD</span>
                    @if($product->stock > 0)
                    <button onclick="addToCart({{ $product->id }}, 'product')"
                            class="h-10 w-10 rounded-full bg-dark border border-border flex items-center justify-center hover:bg-white hover:text-black hover:border-white transition-all duration-200 text-gray-300">
                        <i class="fas fa-plus text-sm"></i>
                    </button>
                    @else
                    <span class="text-sm text-gray-600">Indisponible</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-4 text-center py-12 text-gray-500">
            <i class="fas fa-box-open text-5xl mb-4 block"></i>
            <p>Aucun produit disponible pour l'instant.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Packs Section -->
@if($packs->isNotEmpty())
<div class="bg-card border-y border-border py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-xs tracking-[0.4em] uppercase text-gray-500 mb-2">Offres Spéciales</p>
            <h2 class="text-3xl font-bold text-white">Coffrets Cadeaux</h2>
            <p class="text-gray-400 mt-3 max-w-xl mx-auto">Des assortiments soigneusement composés pour offrir une expérience parfumée inoubliable.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($packs as $pack)
            <div class="relative group bg-dark rounded-2xl overflow-hidden border border-border hover:border-gray-600 transition-all duration-300">
                <div class="h-48 overflow-hidden bg-gray-900 flex items-center justify-center">
                    @if($pack->image)
                        <img src="{{ $pack->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <i class="fas fa-gift text-6xl text-gray-700"></i>
                    @endif
                </div>
                <div class="p-5">
                    <h3 class="text-white font-bold mb-2">{{ $pack->name }}</h3>
                    <p class="text-gray-500 text-sm mb-1">
                        {{ $pack->products->count() }} parfum{{ $pack->products->count() > 1 ? 's' : '' }} inclus
                    </p>
                    <ul class="text-xs text-gray-500 mb-4 space-y-0.5">
                        @foreach($pack->products->take(3) as $prod)
                        <li><i class="fas fa-check text-green-600 mr-1"></i>{{ $prod->name }}</li>
                        @endforeach
                        @if($pack->products->count() > 3)
                        <li class="text-gray-600">+{{ $pack->products->count() - 3 }} autre(s)...</li>
                        @endif
                    </ul>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-white">{{ number_format($pack->price, 2) }} MAD</span>
                        @if($pack->isAvailable())
                        <button onclick="addToCart({{ $pack->id }}, 'pack')"
                                class="h-10 w-10 rounded-full bg-card border border-border flex items-center justify-center hover:bg-white hover:text-black hover:border-white transition-all duration-200 text-gray-300">
                            <i class="fas fa-plus text-sm"></i>
                        </button>
                        @else
                        <span class="text-xs text-red-500">Indisponible</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Why Us Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
        <div class="p-8 rounded-2xl border border-border hover:border-gray-600 transition-colors">
            <div class="w-14 h-14 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-award text-2xl text-white"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Authenticité Garantie</h3>
            <p class="text-gray-500 text-sm">Tous nos parfums sont 100% authentiques, directement des maisons de parfumerie.</p>
        </div>
        <div class="p-8 rounded-2xl border border-border hover:border-gray-600 transition-colors">
            <div class="w-14 h-14 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shipping-fast text-2xl text-white"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Livraison Rapide</h3>
            <p class="text-gray-500 text-sm">Expédition sous 24h. Suivez votre commande en temps réel avec votre code de suivi.</p>
        </div>
        <div class="p-8 rounded-2xl border border-border hover:border-gray-600 transition-colors">
            <div class="w-14 h-14 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-undo text-2xl text-white"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Retours Faciles</h3>
            <p class="text-gray-500 text-sm">Retours acceptés sous 7 jours après réception. Satisfait ou remboursé.</p>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-6 right-6 z-50 hidden">
    <div class="bg-white text-black px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3">
        <i class="fas fa-check-circle text-green-600"></i>
        <span id="toast-msg">Ajouté au panier !</span>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showToast(msg) {
        const toast = document.getElementById('toast');
        document.getElementById('toast-msg').innerText = msg;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    function addToCart(id, type, qty = 1) {
        axios.post('/cart/add', { id, type, qty })
            .then(res => {
                document.getElementById('cart-count').innerText = res.data.cartCount;
                showToast('Ajouté au panier !');
            })
            .catch(err => {
                showToast(err.response?.data?.message || 'Erreur lors de l\'ajout');
            });
    }
</script>
@endpush
