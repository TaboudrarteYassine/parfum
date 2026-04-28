@extends('layouts.admin')
@section('header', 'Modifier la Marque')
@section('content')
<div class="bg-card rounded-lg border border-border shadow-sm p-6 max-w-xl">
    <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        @if($errors->any())
            <div class="bg-red-900/50 text-red-400 p-4 rounded-lg border border-red-800">
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-1">Nom</label>
            <input type="text" name="name" value="{{ old('name', $brand->name) }}" class="w-full bg-dark border border-border rounded px-4 py-2 text-white" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-1">Logo</label>
            @if($brand->logo)
                <img src="{{ asset('storage/' . $brand->logo) }}" class="w-20 h-20 object-contain mb-2 rounded border border-border bg-dark p-1">
            @endif
            <input type="file" name="logo" accept="image/*" class="w-full bg-dark border border-border rounded px-4 py-2 text-white">
        </div>
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">Mettre à jour</button>
            <a href="{{ route('admin.brands.index') }}" class="text-gray-400 hover:text-white py-2 px-4">Annuler</a>
        </div>
    </form>
</div>
@endsection

