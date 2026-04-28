<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Parfum</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: '#0f0f0f',
                        card: '#1a1a1a',
                        border: '#2a2a2a',
                        textMain: '#e5e5e5',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0f0f0f; color: #e5e5e5; }
    </style>
</head>
<body class="antialiased flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-card border-r border-border flex flex-col">
        <div class="h-16 flex items-center px-6 border-b border-border">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-xl font-bold tracking-widest uppercase text-white">
                @if(setting()->logo)
                    <img src="{{ asset('storage/' . setting()->logo) }}" class="h-8">
                @endif
                Admin
            </a>
        </div>
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                        <i class="fas fa-tachometer-alt w-6"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                        <i class="fas fa-tags w-6"></i> Catégories
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.brands.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                        <i class="fas fa-copyright w-6"></i> Marques
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                        <i class="fas fa-box w-6"></i> Produits
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.packs.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                        <i class="fas fa-layer-group w-6"></i> Packs
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                        <i class="fas fa-shopping-bag w-6"></i> Commandes
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.retours.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                        <i class="fas fa-undo w-6"></i> Retours
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.settings.edit') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                        <i class="fas fa-cog w-6"></i> Paramètres
                    </a>
                </li>
            </ul>
        </nav>
        <div class="p-4 border-t border-border">
            <a href="{{ route('home') }}" class="flex items-center text-gray-300 hover:text-white">
                <i class="fas fa-external-link-alt w-6"></i> Voir le site
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-card border-b border-border flex items-center justify-between px-6">
            <h2 class="text-xl font-semibold">@yield('header')</h2>
            <div class="flex items-center">
                <span class="mr-4">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-white"><i class="fas fa-sign-out-alt"></i></button>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-dark p-6">
            @if(session('success'))
                <div class="bg-green-800 text-white p-4 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-800 text-white p-4 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
    @stack('scripts')
</body>
</html>

