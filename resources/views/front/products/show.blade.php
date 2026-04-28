@extends('layouts.app')
@section('title', $product->name . ' - Parfum Store')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Accueil</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-white transition-colors">Parfums</a>
        <span>/</span>
        <span class="text-gray-300">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
        <!-- Image Gallery -->
        <div class="space-y-4">
            <div class="aspect-square rounded-2xl overflow-hidden bg-gray-900 border border-border">
                <img id="main-image"
                     src="{{ $product->main_image_url && !str_ends_with($product->main_image_url, 'no-image.png') ? $product->main_image_url : '' }}"
                     class="w-full h-full object-cover transition-opacity duration-300"
                     onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center\'><i class=\'fas fa-spray-can text-8xl text-gray-700\'></i></div>'">
            </div>
            @if($product->images->count() > 1)
            <div class="grid grid-cols-4 gap-3">
                @foreach($product->images as $image)
                <button onclick="switchImage('{{ $image->url }}')"
                        class="aspect-square rounded-xl overflow-hidden border border-border hover:border-white focus:border-white transition-colors">
                    <img src="{{ $image->url }}" class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Details -->
        <div class="flex flex-col">
            <p class="text-xs tracking-[0.4em] uppercase text-gray-500 mb-2">{{ $product->brand->name ?? 'Marque' }}</p>
            <h1 class="text-4xl font-bold text-white mb-2">{{ $product->name }}</h1>
            <p class="text-sm text-gray-500 mb-6">{{ $product->category->name ?? '' }}</p>

            <div class="text-4xl font-bold text-white mb-8">{{ number_format($product->price, 2) }} MAD</div>

            <!-- Stock Status -->
            <div class="flex items-center gap-2 mb-8">
                @if($product->stock > 0)
                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                <span class="text-sm text-green-400">En stock — {{ $product->stock }} disponible{{ $product->stock > 1 ? 's' : '' }}</span>
                @else
                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                <span class="text-sm text-red-400">Rupture de stock</span>
                @endif
            </div>

            <!-- Description -->
            <div class="prose-sm text-gray-400 leading-relaxed mb-10 border-t border-border pt-6">
                <p>{{ $product->description }}</p>
            </div>

            <!-- Add to Cart -->
            @if($product->stock > 0)
            <div class="flex gap-4 mb-6">
                <div class="flex items-center gap-2 bg-dark border border-border rounded-full px-4 py-2">
                    <button onclick="changeQty(-1)" class="w-8 h-8 flex items-center justify-center hover:text-white transition-colors text-gray-400">
                        <i class="fas fa-minus text-xs"></i>
                    </button>
                    <input type="number" id="qty" value="1" min="1" max="{{ $product->stock }}"
                           class="w-12 text-center bg-transparent text-white font-bold focus:outline-none">
                    <button onclick="changeQty(1)" class="w-8 h-8 flex items-center justify-center hover:text-white transition-colors text-gray-400">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                </div>
                <button onclick="addToCart({{ $product->id }})"
                        class="flex-1 bg-white text-black font-bold py-3 rounded-full hover:bg-gray-100 transition-colors">
                    <i class="fas fa-shopping-bag mr-2"></i>Ajouter au panier
                </button>
            </div>
            @else
            <button disabled class="w-full bg-gray-800 text-gray-500 font-bold py-4 rounded-full cursor-not-allowed">
                Produit indisponible
            </button>
            @endif

            <!-- Meta -->
            <div class="border-t border-border pt-6 space-y-2 text-sm text-gray-500">
                @if($product->brand)
                <div class="flex gap-2"><span class="text-gray-600">Marque :</span><span class="text-gray-300">{{ $product->brand->name }}</span></div>
                @endif
                @if($product->category)
                <div class="flex gap-2"><span class="text-gray-600">Catégorie :</span><span class="text-gray-300">{{ $product->category->name }}</span></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
    <div class="mt-24">
        <h2 class="text-2xl font-bold text-white mb-8">Vous aimerez aussi</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
            <div class="group bg-card rounded-2xl overflow-hidden border border-border hover:border-gray-600 transition-all duration-300">
                <a href="{{ route('products.show', $related) }}" class="block relative h-48 overflow-hidden bg-gray-900">
                    @if($related->main_image_url && !str_ends_with($related->main_image_url, 'no-image.png'))
                        <img src="{{ $related->main_image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-spray-can text-4xl text-gray-700"></i></div>
                    @endif
                </a>
                <div class="p-4">
                    <h3 class="text-white text-sm font-semibold truncate">{{ $related->name }}</h3>
                    <p class="text-lg font-bold text-white mt-1">{{ number_format($related->price, 2) }} MAD</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
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
    function switchImage(src) {
        const img = document.getElementById('main-image');
        img.style.opacity = '0';
        setTimeout(() => { img.src = src; img.style.opacity = '1'; }, 200);
    }

    function changeQty(delta) {
        const input = document.getElementById('qty');
        const max = parseInt(input.getAttribute('max'));
        const newVal = Math.min(max, Math.max(1, parseInt(input.value) + delta));
        input.value = newVal;
    }

    function showToast(msg) {
        const toast = document.getElementById('toast');
        document.getElementById('toast-msg').innerText = msg;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    function addToCart(id) {
        const qty = parseInt(document.getElementById('qty').value);
        axios.post('/cart/add', { id, type: 'product', qty })
            .then(res => {
                document.getElementById('cart-count').innerText = res.data.cartCount;
                showToast('Ajouté au panier !');
            })
            .catch(err => showToast(err.response?.data?.message || 'Erreur'));
    }
</script>
@endpush
