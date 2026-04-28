@extends('layouts.admin')
@section('header', 'Packs')
@section('content')
<div class="mb-4 flex justify-between">
    <a href="{{ route('admin.packs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Créer un Pack</a>
</div>
<div class="bg-card rounded-lg border border-border shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-border">
        <thead class="bg-dark">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Prix</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Produits inclus</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border">
            @foreach($packs as $pack)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $pack->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $pack->price }} MAD</td>
                <td class="px-6 py-4 text-sm text-gray-300">
                    <ul class="list-disc pl-4">
                    @foreach($pack->products as $prod)
                        <li>{{ $prod->name }} (x{{ $prod->pivot->quantity }})</li>
                    @endforeach
                    </ul>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('admin.packs.edit', $pack) }}" class="text-blue-500 hover:text-blue-400 mr-3">Modifier</a>
                    <form action="{{ route('admin.packs.destroy', $pack) }}" method="POST" class="inline" onsubmit="return confirm('Sûr ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-400">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $packs->links() }}</div>
</div>
@endsection
