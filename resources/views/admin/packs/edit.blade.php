@extends('layouts.admin')
@section('header', 'Modifier le Pack: ' . $pack->name)
@section('content')
<div class="bg-card rounded-lg border border-border shadow-sm p-6">
    <form action="{{ route('admin.packs.update', $pack) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        @if($errors->any())
            <div class="bg-red-900/50 text-red-400 p-4 rounded-lg border border-red-800">
                <ul class="list-disc pl-4">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Nom du Pack</label>
                <input type="text" name="name" value="{{ old('name', $pack->name) }}" class="w-full bg-dark border border-border rounded px-4 py-2 text-white focus:outline-none focus:border-gray-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Prix (€)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $pack->price) }}" class="w-full bg-dark border border-border rounded px-4 py-2 text-white focus:outline-none focus:border-gray-500" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-400 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full bg-dark border border-border rounded px-4 py-2 text-white focus:outline-none focus:border-gray-500" required>{{ old('description', $pack->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Image du Pack</label>
                @if($pack->image)
                    <img src="{{ $pack->image_url }}" class="w-24 h-24 object-cover rounded mb-2 border border-border">
                @endif
                <input type="file" name="image" accept="image/*" class="w-full bg-dark border border-border rounded px-4 py-2 text-white">
            </div>
        </div>

        <div>
            <h3 class="text-lg font-bold mb-4 text-white">Produits du Pack</h3>
            <div id="products-container" class="space-y-3">
                @forelse($pack->products as $i => $packProduct)
                <div class="flex gap-4 items-center product-row">
                    <select name="products[]" class="flex-1 bg-dark border border-border rounded px-4 py-2 text-white" required>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ $product->id == $packProduct->id ? 'selected' : '' }}>
                                {{ $product->name }} (Stock: {{ $product->stock }})
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="quantities[]" value="{{ $packProduct->pivot->quantity }}" min="1" placeholder="Qté" class="w-24 bg-dark border border-border rounded px-4 py-2 text-white" required>
                    <button type="button" onclick="this.closest('.product-row').remove()" class="text-red-500 hover:text-red-400 px-2"><i class="fas fa-trash"></i></button>
                </div>
                @empty
                <div class="flex gap-4 items-center product-row">
                    <select name="products[]" class="flex-1 bg-dark border border-border rounded px-4 py-2 text-white" required>
                        <option value="">-- Sélectionner un produit --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="quantities[]" value="1" min="1" placeholder="Qté" class="w-24 bg-dark border border-border rounded px-4 py-2 text-white" required>
                    <button type="button" onclick="this.closest('.product-row').remove()" class="text-red-500 hover:text-red-400 px-2"><i class="fas fa-trash"></i></button>
                </div>
                @endforelse
            </div>
            <button type="button" onclick="addProductRow()" class="mt-3 flex items-center gap-2 text-blue-400 hover:text-blue-300">
                <i class="fas fa-plus"></i> Ajouter un produit
            </button>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">Mettre à jour</button>
            <a href="{{ route('admin.packs.index') }}" class="text-gray-400 hover:text-white py-2 px-4">Annuler</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const products = @json($products);

    function addProductRow() {
        const container = document.getElementById('products-container');
        const options = products.map(p => `<option value="${p.id}">${p.name} (Stock: ${p.stock})</option>`).join('');
        const row = document.createElement('div');
        row.className = 'flex gap-4 items-center product-row';
        row.innerHTML = `
            <select name="products[]" class="flex-1 bg-dark border border-border rounded px-4 py-2 text-white" required>
                <option value="">-- Sélectionner un produit --</option>
                ${options}
            </select>
            <input type="number" name="quantities[]" value="1" min="1" placeholder="Qté" class="w-24 bg-dark border border-border rounded px-4 py-2 text-white" required>
            <button type="button" onclick="this.closest('.product-row').remove()" class="text-red-500 hover:text-red-400 px-2"><i class="fas fa-trash"></i></button>
        `;
        container.appendChild(row);
    }
</script>
@endpush

