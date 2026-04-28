@extends('layouts.app')
@section('title', 'Connexion')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-white">Bon retour !</h1>
            <p class="text-gray-500 mt-2">Connectez-vous pour accéder à votre compte</p>
        </div>

        <div class="bg-card rounded-2xl border border-border p-8">
            @if($errors->any())
                <div class="bg-red-900/40 border border-red-800 text-red-400 p-4 rounded-xl mb-6 text-sm">
                    <ul class="space-y-1">@foreach($errors->all() as $error)<li><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" autofocus
                           class="w-full bg-dark border border-border rounded-xl px-4 py-3 text-white placeholder-gray-700 focus:outline-none focus:border-gray-500 transition-colors"
                           placeholder="votre@email.com" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Mot de passe</label>
                    <input type="password" name="password"
                           class="w-full bg-dark border border-border rounded-xl px-4 py-3 text-white focus:outline-none focus:border-gray-500 transition-colors"
                           placeholder="••••••••" required>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded accent-white">
                        <span class="text-sm text-gray-400">Se souvenir de moi</span>
                    </label>
                </div>
                <button type="submit"
                        class="w-full bg-white text-black font-bold py-3.5 rounded-xl hover:bg-gray-100 transition-colors">
                    Se connecter
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-border text-center">
                <p class="text-gray-500 text-sm">
                    Pas encore de compte ?
                    <a href="{{ route('register') }}" class="text-white font-medium hover:underline ml-1">S'inscrire</a>
                </p>
            </div>
        </div>

        <!-- Guest checkout hint -->
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">
                <i class="fas fa-info-circle mr-1"></i>
                Vous pouvez aussi <a href="{{ route('checkout.index') }}" class="text-gray-400 hover:text-white">commander sans compte</a>
            </p>
        </div>
    </div>
</div>
@endsection
