@extends('layouts.app')
@section('title', 'Tous les Parfums')
@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-10">
        <p class="text-xs tracking-[0.4em] uppercase text-gray-500 mb-2">Notre Collection</p>
        <h1 class="text-4xl font-bold text-white">Tous les Parfums</h1>
    </div>

    <div class="flex flex-col lg:flex-row gap-10">
        <!-- Sidebar Filters -->
        <aside class="w-full lg:w-64 flex-shrink-0">
            <form action="{{ route('products.index') }}" method="GET" id="filter-form">
                <!-- Search -->
                <div class="bg-card rounded-xl border border-border p-5 mb-4">
                    <h3 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Rechercher</h3>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Nom du parfum..."
                               class="w-full bg-dark border border-border rounded-lg px-4 py-2.5 pl-10 text-white text-sm placeholder-gray-600 focus:outline-none focus:border-gray-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-600 text-sm"></i>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="bg-card rounded-xl border border-border p-5 mb-4">
                    <h3 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Catégorie</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }}
                                   class="text-white accent-white" onchange="this.form.submit()">
                            <span class="text-sm text-gray-400 hover:text-white transition-colors">Toutes</span>
                        </label>
                        @foreach($categories as $category)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="category" value="{{ $category->id }}"
                                   {{ request('category') == $category->id ? 'checked' : '' }}
                                   class="accent-white" onchange="this.form.submit()">
                            <span class="text-sm text-gray-400 hover:text-white transition-colors">{{ $category->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Brand Filter -->
                <div class="bg-card rounded-xl border border-border p-5 mb-4">
                    <h3 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Marque</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="brand" value="" {{ !request('brand') ? 'checked' : '' }}
                                   class="accent-white" onchange="this.form.submit()">
                            <span class="text-sm text-gray-400 hover:text-white transition-colors">Toutes</span>
                        </label>
                        @foreach($brands as $brand)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="brand" value="{{ $brand->id }}"
                                   {{ request('brand') == $brand->id ? 'checked' : '' }}
                                   class="accent-white" onchange="this.form.submit()">
                            <span class="text-sm text-gray-400 hover:text-white transition-colors">{{ $brand->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="w-full bg-white text-black font-semibold py-2.5 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                    Appliquer les filtres
                </button>
                @if(request()->hasAny(['search','category','brand']))
                <a href="{{ route('products.index') }}" class="block text-center text-gray-500 hover:text-white text-sm mt-2 py-2">
                    Réinitialiser
                </a>
                @endif
            </form>
        </aside>

        <!-- Products Grid -->
        <div class="flex-1">
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500">{{ $products->total() }} résultat{{ $products->total() > 1 ? 's' : '' }}</p>
            </div>

            @if($products->isEmpty())
            <div class="bg-card rounded-2xl border border-border p-16 text-center">
                <i class="fas fa-search text-5xl text-gray-700 mb-4 block"></i>
                <p class="text-xl text-gray-400">Aucun produit trouvé.</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block text-white hover:underline">Voir tous les produits</a>
            </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($products as $product)
                <div class="group bg-card rounded-2xl overflow-hidden border border-border hover:border-gray-600 transition-all duration-300 hover:-translate-y-1">
                    <a href="{{ route('products.show', $product) }}" class="block relative h-56 overflow-hidden bg-gray-900">
                        @if($product->main_image_url && !str_ends_with($product->main_image_url, 'no-image.png'))
                            <img src="{{ $product->main_image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-900">
                                <i class="fas fa-spray-can text-5xl text-gray-700"></i>
                            </div>
                        @endif
                        @if($product->stock == 0)
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                            <span class="bg-red-900/80 text-red-400 text-sm px-4 py-2 rounded-full">Rupture de stock</span>
                        </div>
                        @endif
                    </a>
                    <div class="p-5">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">{{ $product->brand->name ?? '' }}</p>
                        <h3 class="text-white font-semibold mb-1">
                            <a href="{{ route('products.show', $product) }}" class="hover:text-gray-300 transition-colors">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <div class="flex justify-between items-center mt-4">
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
                @endforeach
            </div>
            <div class="mt-10">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</div>

<!-- Toast -->
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

    function addToCart(id, type) {
        axios.post('/cart/add', { id, type, qty: 1 })
            .then(res => {
                document.getElementById('cart-count').innerText = res.data.cartCount;
                showToast('Ajouté au panier !');
            })
            .catch(err => showToast(err.response?.data?.message || 'Erreur'));
    }
</script>
@endpush
