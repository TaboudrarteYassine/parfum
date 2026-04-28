@extends('layouts.admin')
@section('header', 'Modifier la Catégorie')
@section('content')
<div class="bg-card rounded-lg border border-border shadow-sm p-6 max-w-xl">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        @if($errors->any())
            <div class="bg-red-900/50 text-red-400 p-4 rounded-lg border border-red-800">
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-1">Nom</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full bg-dark border border-border rounded px-4 py-2 text-white" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full bg-dark border border-border rounded px-4 py-2 text-white">{{ old('description', $category->description) }}</textarea>
        </div>
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">Mettre à jour</button>
            <a href="{{ route('admin.categories.index') }}" class="text-gray-400 hover:text-white py-2 px-4">Annuler</a>
        </div>
    </form>
</div>
@endsection

