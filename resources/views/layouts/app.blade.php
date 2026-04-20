<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Mega Zone') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col" x-data="{ mobileMenu: false }">
        <!-- Navigation -->
        <nav style="background: linear-gradient(to right, #4f46e5, #9333ea, #4f46e5); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div style="max-width: 1280px; margin: 0 auto; padding: 0 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; height: 64px;">
                    <!-- Left: Hamburger + Logo -->
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <!-- Mobile menu button -->
                        <button @click="mobileMenu = !mobileMenu" style="display: block; background: none; border: none; color: white; padding: 8px; cursor: pointer;" class="mobile-menu-btn">
                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <!-- Logo (visible on mobile only) -->
                        <a href="{{ route('store.index') }}" style="font-size: 18px; font-weight: 800; color: white; text-decoration: none; letter-spacing: 0.05em;" class="mobile-logo">
                            <span style="color: #fde047;">🛍️</span> Mega Zone
                        </a>

                        <!-- Desktop Navigation Links -->
                        <div style="display: none; margin-left: 32px; gap: 24px;" class="desktop-nav">
                            <a href="{{ route('store.index') }}" style="color: {{ request()->routeIs('store.index') ? '#fde047' : 'rgba(255,255,255,0.9)' }}; border-bottom: 2px solid {{ request()->routeIs('store.index') ? '#fde047' : 'transparent' }}; padding: 4px 8px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; white-space: nowrap;">
                                Tous les produits
                            </a>
                            
                            <!-- Categories Dropdown -->
                            @php
                                $navCategories = \App\Models\Category::where('is_active', true)->take(5)->get();
                            @endphp
                            @if($navCategories->count() > 0)
                            <div style="position: relative;" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" style="color: {{ request()->routeIs('store.category') ? '#fde047' : 'rgba(255,255,255,0.9)' }}; border-bottom: 2px solid {{ request()->routeIs('store.category') ? '#fde047' : 'transparent' }}; padding: 4px 8px; font-size: 14px; font-weight: 600; background: none; border-top: none; border-left: none; border-right: none; cursor: pointer; display: inline-flex; align-items: center;">
                                    Catégories
                                    <svg style="margin-left: 4px; height: 16px; width: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" style="display: none; position: absolute; left: 0; top: 40px; width: 256px; background: white; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); z-index: 50; overflow: hidden;">
                                    <div style="padding: 4px 0;">
                                        @foreach($navCategories as $cat)
                                            <a href="{{ route('store.category', $cat) }}" style="display: block; padding: 12px 16px; font-size: 14px; color: #374151; text-decoration: none;" @click="open = false">
                                                📦 {{ $cat->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Cart + Auth (Desktop & Mobile) -->
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <!-- Search Bar (Desktop) -->
                        <form action="{{ route('store.search') }}" method="GET" style="display: none;" class="desktop-search">
                            <div style="position: relative;">
                                <input type="text" name="q" placeholder="Rechercher un produit..." 
                                    style="width: 256px; padding: 8px 12px 8px 40px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; font-size: 14px; color: white;" 
                                    value="{{ request('q') }}">
                                <div style="position: absolute; top: 0; left: 0; bottom: 0; padding-left: 12px; display: flex; align-items: center; pointer-events: none;">
                                    <svg style="height: 18px; width: 18px; color: rgba(255,255,255,0.7);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </form>

                        <!-- Cart -->
                        <a href="{{ route('cart.index') }}" style="position: relative; padding: 8px; color: rgba(255,255,255,0.9); text-decoration: none; flex-shrink: 0;">
                            <svg style="width: 22px; height: 22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13v8a2 2 0 002 2h10a2 2 0 002-2v-3"></path>
                            </svg>
                            @if(Session::has('cart') && count(Session::get('cart', [])) > 0)
                                <span style="position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; font-size: 12px; font-weight: 700; border-radius: 50%; height: 24px; width: 24px; display: flex; align-items: center; justify-content: center;">
                                    {{ count(Session::get('cart', [])) }}
                                </span>
                            @endif
                        </a>

                        <!-- Authentication Links -->
                        @guest
                            <a href="{{ route('login') }}" style="color: rgba(255,255,255,0.9); text-decoration: none; padding: 8px 12px; font-size: 13px; font-weight: 600; white-space: nowrap;" class="auth-link">Connexion</a>
                            <a href="{{ route('register') }}" style="background: #facc15; color: #312e81; padding: 8px 12px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); white-space: nowrap;" class="auth-link">Inscription</a>
                        @else
                            <div style="position: relative;" class="user-menu-desktop">
                                <div style="display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.1); border-radius: 8px; padding: 6px 12px;">
                                    <div style="height: 28px; width: 28px; border-radius: 50%; background: linear-gradient(to right, #facc15, #fb923c); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 12px; flex-shrink: 0;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span style="color: white; font-weight: 600; font-size: 13px; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" class="user-name">{{ Auth::user()->name }}</span>
                                    @if(Auth::user()->is_admin)
                                        <a href="{{ route('admin.store') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; text-decoration: none;" class="admin-badge">
                                            ⚙️ Admin
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="color: rgba(255,255,255,0.8); font-size: 12px; font-weight: 600; background: none; border: none; cursor: pointer; padding: 4px 8px;">Déconnexion</button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenu" @click.away="mobileMenu = false" style="display: none; background: white; border-bottom: 2px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);" class="mobile-menu">
            <div style="max-width: 1280px; margin: 0 auto; padding: 16px;">
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <a href="{{ route('store.index') }}" style="padding: 12px; background: #f9fafb; border-radius: 8px; font-size: 14px; font-weight: 600; color: #374151; text-decoration: none;">
                        🏠 Tous les produits
                    </a>
                    
                    @php
                        $navCategories = \App\Models\Category::where('is_active', true)->take(8)->get();
                    @endphp
                    @if($navCategories->count() > 0)
                    <div x-data="{ categoriesOpen: false }">
                        <button @click="categoriesOpen = !categoriesOpen" style="width: 100%; padding: 12px; background: #f9fafb; border-radius: 8px; font-size: 14px; font-weight: 600; color: #374151; border: none; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                            <span>📁 Catégories</span>
                            <svg :class="{ 'rotate-180': categoriesOpen }" style="height: 16px; width: 16px; transition: transform 0.2s;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div x-show="categoriesOpen" x-collapse style="display: none; margin-top: 8px; padding: 8px; background: #f9fafb; border-radius: 8px;">
                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                @foreach($navCategories as $cat)
                                    <a href="{{ route('store.category', $cat) }}" style="padding: 10px 12px; font-size: 14px; color: #374151; text-decoration: none; border-radius: 6px;" @click="mobileMenu = false">
                                        📦 {{ $cat->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Mobile Auth Links -->
                    @guest
                    <div style="display: flex; flex-direction: column; gap: 8px; padding-top: 8px; border-top: 1px solid #e5e7eb;">
                        <a href="{{ route('login') }}" style="padding: 12px; background: #4f46e5; color: white; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; text-align: center;">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" style="padding: 12px; background: #facc15; color: #312e81; border-radius: 8px; font-size: 14px; font-weight: 700; text-decoration: none; text-align: center;">
                            Inscription
                        </a>
                    </div>
                    @else
                    <div style="padding: 12px; background: #f9fafb; border-radius: 8px; border-top: 1px solid #e5e7eb;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                            <div style="height: 40px; width: 40px; border-radius: 50%; background: linear-gradient(to right, #facc15, #fb923c); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px; flex-shrink: 0;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-size: 14px; font-weight: 600; color: #111827; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Auth::user()->name }}</div>
                                @if(Auth::user()->is_admin)
                                <a href="{{ route('admin.store') }}" style="display: inline-block; margin-top: 4px; background: #4f46e5; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; text-decoration: none;">
                                    ⚙️ Administration
                                </a>
                                @endif
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" style="width: 100%; padding: 10px; background: #ef4444; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                    @endguest
                </div>
            </div>
        </div>

        <!-- Mobile Search -->
        <div style="display: none; background: white; border-bottom: 1px solid #e5e7eb; padding: 12px 16px;" class="mobile-search">
            <form action="{{ route('store.search') }}" method="GET">
                <div style="position: relative;">
                    <input type="text" name="q" placeholder="Rechercher..." 
                        style="width: 100%; padding: 10px 16px 10px 44px; border: 1px solid #d1d5db; border-radius: 12px; font-size: 14px;" 
                        value="{{ request('q') }}">
                    <div style="position: absolute; top: 0; left: 0; bottom: 0; padding-left: 12px; display: flex; align-items: center; pointer-events: none;">
                        <svg style="height: 20px; width: 20px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </form>
        </div>
        
        <style>
            /* Desktop (768px and up) */
            @media (min-width: 768px) {
                .desktop-nav { display: flex !important; }
                .desktop-search { display: block !important; }
                .mobile-menu-btn { display: none !important; }
                .mobile-menu { display: none !important; }
                .user-menu-desktop { display: block !important; }
            }
            
            /* Mobile (below 768px) */
            @media (max-width: 767px) {
                .mobile-search { display: block !important; }
                .user-menu-desktop { display: none !important; }
                .auth-link { display: none !important; }
                .admin-badge { display: none !important; }
                .user-name { display: none !important; }
            }
            
            /* Very small screens */
            @media (max-width: 480px) {
                nav div[style*="gap: 12px"] { gap: 8px !important; }
            }
        </style>

        <!-- Page Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-500 text-sm">
                    © {{ date('Y') }} Mega Zone. Tous droits réservés.
                </div>
            </div>
        </footer>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif
</body>
</html>