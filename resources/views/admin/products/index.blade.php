@extends('layouts.admin')
@section('header', 'Produits')
@section('content')
<div class="mb-4 flex justify-between">
    <a href="{{ route('admin.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter un Produit</a>
</div>
<div class="bg-card rounded-lg border border-border shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-border">
        <thead class="bg-dark">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Image</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Prix</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border">
            @foreach($products as $product)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($product->main_image_url)
                        <img src="{{ $product->main_image_url }}" class="h-10 w-10 rounded object-cover">
                    @else
                        <div class="h-10 w-10 rounded bg-gray-700 flex items-center justify-center text-xs text-gray-500">N/A</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $product->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $product->price }} MAD</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    @if($product->stock <= 0)
                        <span class="text-red-400 font-bold">Rupture</span>
                    @elseif($product->stock <= 5)
                        <span class="text-yellow-400 font-bold">{{ $product->stock }}</span>
                    @else
                        <span class="text-gray-300">{{ $product->stock }}</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-500 hover:text-blue-400 mr-3">Modifier</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Sûr ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-400">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $products->links() }}</div>
</div>
@endsection
