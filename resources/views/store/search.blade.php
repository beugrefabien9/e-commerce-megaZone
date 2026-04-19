@extends('layouts.app')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Résultats de recherche</h1>
            <p class="mt-2 text-gray-600">
                @if(request('q'))
                    Résultats pour "{{ request('q') }}"
                @else
                    Tous les produits
                @endif
            </p>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                @foreach($products as $product)
                <div class="group">
                    <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-lg bg-gray-200 xl:aspect-w-7 xl:aspect-h-8">
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                 alt="{{ $product->name }}"
                                 class="h-full w-full object-cover object-center group-hover:opacity-75">
                        @else
                            <div class="h-full w-full flex items-center justify-center bg-gray-100">
                                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <h3 class="mt-4 text-sm text-gray-700">
                        <a href="{{ route('store.product', $product) }}">
                            <span aria-hidden="true" class="absolute inset-0"></span>
                            {{ $product->name }}
                        </a>
                    </h3>
                    <p class="mt-1 text-lg font-medium text-gray-900">
                        @if($product->sale_price)
                            <span class="line-through text-gray-500">{{ number_format($product->price, 2) }} FCFA</span>
                            <span class="text-red-600 ml-2">{{ number_format($product->sale_price, 2) }} FCFA</span>
                        @else
                            {{ number_format($product->price, 2) }} FCFA
                        @endif
                    </p>
                    <p class="mt-1 text-sm text-gray-500">{{ $product->category->name }}</p>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun produit trouvé</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request('q'))
                        Aucun produit ne correspond à votre recherche "{{ request('q') }}".
                    @else
                        Aucun produit disponible pour le moment.
                    @endif
                </p>
                <div class="mt-6">
                    <a href="{{ route('store.index') }}" class="text-indigo-600 hover:text-indigo-500">
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection