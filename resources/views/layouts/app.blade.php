<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Parfum Store')</title>
    <!-- Tailwind CSS -->
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
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0f0f0f;
            color: #e5e5e5;
        }
    </style>
</head>
<body class="antialiased flex flex-col min-h-screen">

    <nav class="bg-card border-b border-border sticky top-0 z-50 backdrop-blur-sm bg-card/95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left: Logo + Hamburger -->
                <div class="flex items-center gap-4">
                    <!-- Mobile hamburger -->
                    <button id="menu-toggle" class="md:hidden flex items-center justify-center w-10 h-10 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
                        <i id="menu-icon" class="fas fa-bars text-lg"></i>
                    </button>
                    <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-xl tracking-widest uppercase text-white">
                        @if(setting()->logo)
                            <img src="{{ asset('storage/' . setting()->logo) }}" class="h-8">
                        @else
                            <i class="fas fa-spray-can text-gray-400"></i>
                        @endif
                        {{ setting()->site_name ?? 'Parfum' }}
                    </a>
                </div>

                <!-- Desktop nav links -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white transition-colors {{ request()->routeIs('home') ? 'text-white' : '' }}">Accueil</a>
                    <a href="{{ route('products.index') }}" class="text-sm text-gray-400 hover:text-white transition-colors {{ request()->routeIs('products.*') ? 'text-white' : '' }}">Parfums</a>
                    <a href="{{ route('track.order') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Suivre</a>
                </div>

                <!-- Right: Search + Cart + User -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Search -->
                    <div class="hidden sm:block relative">
                        <input type="text" id="search-input" placeholder="Rechercher..."
                               class="bg-dark border border-border rounded-full px-4 py-1.5 pl-9 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500 w-36 lg:w-48 transition-all"
                               autocomplete="off">
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-600 text-xs"></i>
                        <div id="search-results" class="absolute top-full mt-2 left-0 w-80 bg-card border border-border rounded-xl shadow-2xl hidden overflow-hidden z-50"></div>
                    </div>

                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="relative flex items-center justify-center w-10 h-10 rounded-full hover:bg-white/10 transition-colors text-gray-300 hover:text-white">
                        <i class="fas fa-shopping-bag text-lg"></i>
                        @php $cartCount = array_sum(array_column(session('cart', []), 'qty')); @endphp
                        <span id="cart-count"
                              class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-white text-black rounded-full text-xs font-bold flex items-center justify-center px-1 {{ $cartCount == 0 ? 'hidden' : '' }}">
                            {{ $cartCount }}
                        </span>
                    </a>

                    <!-- User Menu -->
                    @auth
                    <div class="relative">
                        <button onclick="this.nextElementSibling.classList.toggle('hidden')"
                                class="flex items-center gap-2 text-gray-300 hover:text-white transition-colors">
                            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-xs font-bold text-white">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <i class="fas fa-chevron-down text-xs hidden sm:block"></i>
                        </button>
                        <div class="hidden absolute right-0 top-full mt-2 w-52 bg-card border border-border rounded-xl shadow-2xl overflow-hidden z-50">
                            <div class="p-3 border-b border-border">
                                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                                <i class="fas fa-shield-alt w-4 text-yellow-500"></i> Admin Dashboard
                            </a>
                            @endif
                            <a href="{{ route('user.orders') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                                <i class="fas fa-shopping-bag w-4"></i> Mes commandes
                            </a>
                            <div class="border-t border-border">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-gray-400 hover:bg-white/5 hover:text-white transition-colors">
                                        <i class="fas fa-sign-out-alt w-4"></i> Se déconnecter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}"
                       class="hidden sm:inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors border border-border rounded-full px-4 py-1.5 hover:border-gray-500">
                        <i class="fas fa-user text-xs"></i> Connexion
                    </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-border bg-card">
            <div class="px-4 py-3 space-y-2">
                <a href="{{ route('home') }}" class="block px-4 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-colors {{ request()->routeIs('home') ? 'bg-white/5 text-white' : '' }}">
                    <i class="fas fa-home w-5"></i> Accueil
                </a>
                <a href="{{ route('products.index') }}" class="block px-4 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-colors {{ request()->routeIs('products.*') ? 'bg-white/5 text-white' : '' }}">
                    <i class="fas fa-spray-can w-5"></i> Parfums
                </a>
                <a href="{{ route('track.order') }}" class="block px-4 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                    <i class="fas fa-map-marker-alt w-5"></i> Suivre ma commande
                </a>
                <!-- Mobile search -->
                <div class="relative sm:hidden">
                    <input type="text" id="search-input-mobile" placeholder="Rechercher..."
                           class="w-full bg-dark border border-border rounded-lg px-4 py-2.5 pl-9 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500"
                           autocomplete="off">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-600 text-xs"></i>
                </div>
                @guest
                <a href="{{ route('login') }}" class="block sm:hidden px-4 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                    <i class="fas fa-user w-5"></i> Connexion
                </a>
                @endguest
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-card border-t border-border mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} {{ setting()->site_name ?? 'Parfum Store' }}. Tous droits réservés.</p>
            @if(setting()->email || setting()->phone)
            <p class="mt-2 text-sm">{{ setting()->email }} | {{ setting()->phone }}</p>
            @endif
        </div>
    </footer>

    <!-- Axios for AJAX requests -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Update cart count badge visibility
        function updateCartBadge(count) {
            const badge = document.getElementById('cart-count');
            if (badge) {
                badge.textContent = count;
                badge.classList.toggle('hidden', count == 0);
            }
        }

        // Live AJAX Search
        const searchInput = document.getElementById('search-input');
        const searchResults = document.getElementById('search-results');
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const q = this.value.trim();
                if (q.length < 2) { searchResults.classList.add('hidden'); return; }
                searchTimeout = setTimeout(() => {
                    axios.get('/search?q=' + encodeURIComponent(q))
                        .then(res => {
                            if (!res.data.length) {
                                searchResults.innerHTML = '<p class="p-4 text-sm text-gray-500">Aucun résultat trouvé.</p>';
                            } else {
                                searchResults.innerHTML = res.data.map(p => `
                                    <a href="/products/${p.id}" class="flex items-center gap-3 p-3 hover:bg-white/5 transition-colors border-b border-[#2a2a2a] last:border-b-0">
                                        <div class="w-10 h-10 bg-[#0f0f0f] rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                                            ${p.images && p.images.length ? `<img src="/storage/${p.images[0].path}" class="w-full h-full object-cover">` : '<i class="fas fa-spray-can text-gray-600 text-sm"></i>'}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-white font-medium truncate">${p.name}</p>
                                            <p class="text-xs text-gray-500">${p.price} MAD</p>
                                        </div>
                                    </a>
                                `).join('');
                            }
                            searchResults.classList.remove('hidden');
                        });
                }, 300);
            });
        }

    // Close search on outside click
    document.addEventListener('click', function(e) {
        if (searchInput && !searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });

    // Mobile menu toggle
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            menuIcon.classList.toggle('fa-bars');
            menuIcon.classList.toggle('fa-times');
        });
    }

    // Mobile search (reuse same logic as desktop)
    const searchInputMobile = document.getElementById('search-input-mobile');
    if (searchInputMobile) {
        searchInputMobile.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const q = this.value.trim();
            if (q.length < 2) { return; }
            searchTimeout = setTimeout(() => {
                axios.get('/search?q=' + encodeURIComponent(q))
                    .then(res => {
                        // For mobile, we just redirect to search results page
                        if (res.data.length) {
                            window.location.href = '/search?q=' + encodeURIComponent(q);
                        }
                    });
            }, 400);
        });
    }
</script>
@stack('scripts')
</body>
</html>

