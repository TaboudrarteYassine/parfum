@extends('layouts.admin')
@section('header', 'Catégories')
@section('content')
<div class="mb-4">
    <form action="{{ route('admin.categories.store') }}" method="POST" class="bg-card p-4 rounded-lg border border-border flex gap-4 items-end">
        @csrf
        <div class="flex-1">
            <label class="block text-sm text-gray-400 mb-1">Nouvelle catégorie</label>
            <input type="text" name="name" class="w-full bg-dark border border-border rounded px-3 py-2 text-white" required>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Ajouter</button>
    </form>
</div>
<div class="bg-card rounded-lg border border-border shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-border">
        <thead class="bg-dark">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border">
            @foreach($categories as $category)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $category->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Sûr ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-400">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
