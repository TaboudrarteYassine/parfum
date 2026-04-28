@extends('layouts.admin')
@section('header', 'Ajouter un Produit')
@section('content')
<div class="bg-card rounded-lg border border-border shadow-sm p-6">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Nom</label>
                <input type="text" name="name" class="w-full bg-dark border border-border rounded px-4 py-2 text-white" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Prix (€)</label>
                <input type="number" step="0.01" name="price" class="w-full bg-dark border border-border rounded px-4 py-2 text-white" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Catégorie</label>
                <select name="category_id" class="w-full bg-dark border border-border rounded px-4 py-2 text-white" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Marque</label>
                <select name="brand_id" class="w-full bg-dark border border-border rounded px-4 py-2 text-white" required>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Stock</label>
                <input type="number" name="stock" value="0" class="w-full bg-dark border border-border rounded px-4 py-2 text-white" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Images (Multiple)</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full bg-dark border border-border rounded px-4 py-2 text-white">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full bg-dark border border-border rounded px-4 py-2 text-white" required></textarea>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Créer le produit</button>
    </form>
</div>
@endsection
