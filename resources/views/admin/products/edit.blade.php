@extends('layouts.admin')
@section('header', 'Modifier le Produit')
@section('content')
<div class="bg-card rounded-lg border border-border shadow-sm p-6">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Nom</label>
                <input type="text" name="name" value="{{ $product->name }}" class="w-full bg-dark border border-border rounded px-4 py-2 text-white" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Prix (€)</label>
                <input type="number" step="0.01" name="price" value="{{ $product->price }}" class="w-full bg-dark border border-border rounded px-4 py-2 text-white" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Catégorie</label>
                <select name="category_id" class="w-full bg-dark border border-border rounded px-4 py-2 text-white" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Marque</label>
                <select name="brand_id" class="w-full bg-dark border border-border rounded px-4 py-2 text-white" required>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $brand->id == $product->brand_id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Stock</label>
                <input type="number" name="stock" value="{{ $product->stock }}" class="w-full bg-dark border border-border rounded px-4 py-2 text-white" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Ajouter des Images</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full bg-dark border border-border rounded px-4 py-2 text-white">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full bg-dark border border-border rounded px-4 py-2 text-white" required>{{ $product->description }}</textarea>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Mettre à jour</button>
    </form>
    
    <div class="mt-8 border-t border-border pt-6">
        <h3 class="text-lg font-bold mb-4">Images du produit</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($product->images as $image)
            <div class="relative bg-dark rounded border border-border overflow-hidden group">
                <img src="{{ $image->url }}" class="w-full h-32 object-cover">
                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center gap-2 transition-opacity">
                    @if(!$image->is_featured)
                    <button onclick="setMainImage('{{ $image->id }}')" class="bg-blue-600 text-white px-2 py-1 rounded text-xs">Mettre Principal</button>
                    @else
                    <span class="bg-green-600 text-white px-2 py-1 rounded text-xs">Principal</span>
                    @endif
                    <button onclick="deleteImage('{{ $image->id }}')" class="bg-red-600 text-white px-2 py-1 rounded text-xs"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    function deleteImage(id) {
        if(confirm('Supprimer cette image ?')) {
            axios.post(`/admin/products/image/${id}/delete`).then(() => location.reload());
        }
    }
    function setMainImage(id) {
        axios.post(`/admin/products/image/${id}/main`).then(() => location.reload());
    }
</script>
@endpush
