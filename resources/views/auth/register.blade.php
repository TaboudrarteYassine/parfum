@extends('layouts.app')
@section('title', 'Inscription')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-white">Créer un compte</h1>
            <p class="text-gray-500 mt-2">Rejoignez-nous pour une expérience parfumée unique</p>
        </div>

        <div class="bg-card rounded-2xl border border-border p-8">
            @if($errors->any())
                <div class="bg-red-900/40 border border-red-800 text-red-400 p-4 rounded-xl mb-6 text-sm">
                    <ul class="space-y-1">@foreach($errors->all() as $error)<li><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name') }}" autofocus
                           class="w-full bg-dark border border-border rounded-xl px-4 py-3 text-white focus:outline-none focus:border-gray-500"
                           placeholder="Jean Dupont" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full bg-dark border border-border rounded-xl px-4 py-3 text-white focus:outline-none focus:border-gray-500"
                           placeholder="votre@email.com" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Mot de passe</label>
                    <input type="password" name="password"
                           class="w-full bg-dark border border-border rounded-xl px-4 py-3 text-white focus:outline-none focus:border-gray-500"
                           placeholder="Minimum 8 caractères" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation"
                           class="w-full bg-dark border border-border rounded-xl px-4 py-3 text-white focus:outline-none focus:border-gray-500"
                           placeholder="••••••••" required>
                </div>
                <button type="submit"
                        class="w-full bg-white text-black font-bold py-3.5 rounded-xl hover:bg-gray-100 transition-colors">
                    Créer mon compte
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-border text-center">
                <p class="text-gray-500 text-sm">
                    Déjà un compte ?
                    <a href="{{ route('login') }}" class="text-white font-medium hover:underline ml-1">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
