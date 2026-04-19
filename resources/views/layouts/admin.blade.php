<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Mega Zone') }} - Administration</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased" style="background-color: #f9fafb;">
    <div style="min-height: 100vh; display: flex; flex-direction: column;">
        <!-- Navigation Admin -->
        <nav style="background: linear-gradient(to right, #1e3a8a, #1e40af, #3730a3); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);" x-data="{ mobileMenu: false }">
            <div style="max-width: 1280px; margin: 0 auto; padding: 0 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; height: 64px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <!-- Mobile menu button -->
                        <button @click="mobileMenu = !mobileMenu" style="display: block; background: none; border: none; color: white; padding: 8px; cursor: pointer;" class="admin-mobile-btn">
                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <!-- Logo -->
                        <div style="flex-shrink: 0; display: flex; align-items: center;">
                            <a href="{{ route('admin.store') }}" style="font-size: 18px; font-weight: 700; color: white; text-decoration: none;">
                                ⚙️ Mega Admin
                            </a>
                        </div>

                        <!-- Desktop Navigation Links -->
                        <div style="display: none; gap: 24px; margin-left: 32px;" class="admin-nav">
                            <a href="{{ route('admin.store') }}" style="border-bottom: 2px solid {{ request()->routeIs('admin.store') ? '#818cf8' : 'transparent' }}; color: {{ request()->routeIs('admin.store') ? 'white' : '#d1d5db' }}; padding: 4px 8px; font-size: 13px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; white-space: nowrap;">
                                📊 Tableau de bord
                            </a>
                            <a href="{{ route('admin.products.index') }}" style="border-bottom: 2px solid {{ request()->routeIs('admin.products.*') ? '#818cf8' : 'transparent' }}; color: {{ request()->routeIs('admin.products.*') ? 'white' : '#d1d5db' }}; padding: 4px 8px; font-size: 13px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; white-space: nowrap;">
                                📦 Produits
                            </a>
                            <a href="{{ route('admin.categories.index') }}" style="border-bottom: 2px solid {{ request()->routeIs('admin.categories.*') ? '#818cf8' : 'transparent' }}; color: {{ request()->routeIs('admin.categories.*') ? 'white' : '#d1d5db' }}; padding: 4px 8px; font-size: 13px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; white-space: nowrap;">
                                📁 Catégories
                            </a>
                            <a href="{{ route('admin.sub-categories.index') }}" style="border-bottom: 2px solid {{ request()->routeIs('admin.sub-categories.*') ? '#818cf8' : 'transparent' }}; color: {{ request()->routeIs('admin.sub-categories.*') ? 'white' : '#d1d5db' }}; padding: 4px 8px; font-size: 13px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; white-space: nowrap;">
                                📂 Sous-catégories
                            </a>
                            <a href="{{ route('admin.orders.index') }}" style="border-bottom: 2px solid {{ request()->routeIs('admin.orders.*') ? '#818cf8' : 'transparent' }}; color: {{ request()->routeIs('admin.orders.*') ? 'white' : '#d1d5db' }}; padding: 4px 8px; font-size: 13px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; white-space: nowrap;">
                                🛒 Commandes
                            </a>
                        </div>
                    </div>

                    <div style="display: none; align-items: center; gap: 12px;" class="admin-right">
                        <!-- Store Link -->
                        <a href="{{ route('store.index') }}" style="color: #fde047; text-decoration: none; padding: 6px 12px; font-size: 13px; font-weight: 600; border-radius: 6px; background: rgba(255,255,255,0.1); white-space: nowrap;">
                            🏪 Voir la boutique
                        </a>

                        <!-- Authentication Links -->
                        @auth
                            <div style="position: relative;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="height: 32px; width: 32px; border-radius: 50%; background: linear-gradient(to right, #facc15, #fb923c); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 14px;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span style="color: #e5e7eb; font-size: 14px;">{{ Auth::user()->name }}</span>
                                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="color: #d1d5db; background: none; border: none; cursor: pointer; padding: 8px 12px; font-size: 14px; font-weight: 500; border-radius: 6px; margin-left: 8px;">
                                            🚪 Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenu" @click.away="mobileMenu = false" style="display: none; background: white; border-bottom: 2px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);" class="admin-mobile-menu">
            <div style="max-width: 1280px; margin: 0 auto; padding: 16px;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <a href="{{ route('admin.store') }}" style="padding: 12px; background: #f9fafb; border-radius: 8px; font-size: 14px; font-weight: 600; color: #374151; text-decoration: none;">
                        📊 Tableau de bord
                    </a>
                    <a href="{{ route('admin.products.index') }}" style="padding: 12px; background: #f9fafb; border-radius: 8px; font-size: 14px; font-weight: 600; color: #374151; text-decoration: none;">
                        📦 Produits
                    </a>
                    <a href="{{ route('admin.categories.index') }}" style="padding: 12px; background: #f9fafb; border-radius: 8px; font-size: 14px; font-weight: 600; color: #374151; text-decoration: none;">
                        📁 Catégories
                    </a>
                    <a href="{{ route('admin.sub-categories.index') }}" style="padding: 12px; background: #f9fafb; border-radius: 8px; font-size: 14px; font-weight: 600; color: #374151; text-decoration: none;">
                        📂 Sous-catégories
                    </a>
                    <a href="{{ route('admin.orders.index') }}" style="padding: 12px; background: #f9fafb; border-radius: 8px; font-size: 14px; font-weight: 600; color: #374151; text-decoration: none;">
                        🛒 Commandes
                    </a>
                    <a href="{{ route('store.index') }}" style="padding: 12px; background: #fef3c7; border-radius: 8px; font-size: 14px; font-weight: 600; color: #92400e; text-decoration: none; margin-top: 8px;">
                        🏪 Voir la boutique
                    </a>
                </div>
            </div>
        </div>
        
        <style>
            @media (min-width: 768px) {
                .admin-nav { display: flex !important; }
                .admin-right { display: flex !important; }
                .admin-mobile-btn { display: none !important; }
                .admin-mobile-menu { display: none !important; }
            }
        </style>

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>
    </div>
</body>
</html>