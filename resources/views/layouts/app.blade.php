<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Teranga Shop') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav style="background: linear-gradient(to right, #4f46e5, #9333ea, #4f46e5); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div style="max-width: 1280px; margin: 0 auto; padding: 0 1rem;">
                <div style="display: flex; justify-content: space-between; height: 80px;">
                    <div style="display: flex; align-items: center;">
                        <!-- Logo -->
                        <div style="flex-shrink: 0; display: flex; align-items: center;">
                            <a href="{{ route('store.index') }}" style="font-size: 24px; font-weight: 800; color: white; text-decoration: none; letter-spacing: 0.05em;">
                                <span style="color: #fde047;">🛍️</span> Teranga Shop
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div style="display: none; margin-left: 48px; gap: 32px;" class="desktop-nav">
                            <a href="{{ route('store.index') }}" style="color: {{ request()->routeIs('store.index') ? '#fde047' : 'rgba(255,255,255,0.9)' }}; border-bottom: 2px solid {{ request()->routeIs('store.index') ? '#fde047' : 'transparent' }}; padding: 4px 8px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;">
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
                                <div x-show="open" style="position: absolute; left: 0; top: 40px; width: 256px; background: white; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); z-index: 50; overflow: hidden;">
                                    <div style="padding: 4px 0;">
                                        @foreach($navCategories as $cat)
                                            <a href="{{ route('store.category', $cat) }}" style="display: block; padding: 12px 16px; font-size: 14px; color: #374151; text-decoration: none;">
                                                📦 {{ $cat->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 16px;">
                        <!-- Search Bar -->
                        <form action="{{ route('store.search') }}" method="GET" style="display: none;" class="desktop-search">
                            <div style="position: relative;">
                                <input type="text" name="q" placeholder="Rechercher un produit..." 
                                    style="width: 288px; padding: 10px 16px 10px 44px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; font-size: 14px; color: white;" 
                                    value="{{ request('q') }}">
                                <div style="position: absolute; top: 0; left: 0; bottom: 0; padding-left: 14px; display: flex; align-items: center; pointer-events: none;">
                                    <svg style="height: 20px; width: 20px; color: rgba(255,255,255,0.7);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </form>

                        <!-- Cart -->
                        <a href="{{ route('cart.index') }}" style="position: relative; padding: 10px; color: rgba(255,255,255,0.9); text-decoration: none;">
                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <a href="{{ route('login') }}" style="color: rgba(255,255,255,0.9); text-decoration: none; padding: 10px 16px; font-size: 14px; font-weight: 600;">Connexion</a>
                            <a href="{{ route('register') }}" style="background: #facc15; color: #312e81; padding: 10px 16px; border-radius: 12px; font-size: 14px; font-weight: 700; text-decoration: none; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">Inscription</a>
                        @else
                            <div style="position: relative;">
                                <div style="display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); border-radius: 12px; padding: 8px 16px;">
                                    <div style="height: 32px; width: 32px; border-radius: 50%; background: linear-gradient(to right, #facc15, #fb923c); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 14px;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span style="color: white; font-weight: 600; font-size: 14px;">{{ Auth::user()->name }}</span>
                                    @if(Auth::user()->is_admin)
                                        <a href="{{ route('admin.store') }}" style="margin-left: 8px; background: rgba(255,255,255,0.2); color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none;">
                                            ⚙️ Admin
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}" style="display: inline; margin-left: 8px;">
                                        @csrf
                                        <button type="submit" style="color: rgba(255,255,255,0.8); font-size: 12px; font-weight: 600; background: none; border: none; cursor: pointer; padding: 6px 8px;">Déconnexion</button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

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
            @media (min-width: 768px) {
                .desktop-nav { display: flex !important; }
                .desktop-search { display: block !important; }
            }
            @media (max-width: 767px) {
                .mobile-search { display: block !important; }
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
                    © {{ date('Y') }} Teranga Shop. Tous droits réservés.
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