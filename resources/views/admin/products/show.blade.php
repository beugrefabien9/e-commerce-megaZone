@extends('layouts.admin')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $product->name }}</h1>
                    <p class="mt-2 text-gray-600">Détails du produit</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Modifier
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour aux produits
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Images et informations principales -->
            <div class="lg:col-span-2">
                <!-- Images -->
                <div class="bg-white shadow rounded-lg mb-8">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Images</h3>
                        @if($product->images->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($product->images as $image)
                                <div class="relative">
                                    @php
                                        $imagePath = $image->image_path;
                                        if(str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
                                            $imageSrc = $imagePath;
                                        } else {
                                            $imageSrc = asset('storage/' . $imagePath);
                                        }
                                    @endphp
                                    <img src="{{ $imageSrc }}" alt="{{ $product->name }}" class="w-full h-32 object-cover rounded-lg">
                                    @if($image->is_primary)
                                        <span class="absolute top-2 left-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            Principale
                                        </span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Aucune image</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Description</h3>
                        <div class="prose max-w-none">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations détaillées -->
            <div class="space-y-6">
                <!-- Prix et stock -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Prix et Stock</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Prix</dt>
                                <dd class="text-sm text-gray-900">{{ number_format($product->price, 2) }} FCFA</dd>
                            </div>
                            @if($product->sale_price)
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Prix soldé</dt>
                                <dd class="text-sm text-green-600">{{ number_format($product->sale_price, 2) }} FCFA</dd>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Stock</dt>
                                <dd class="text-sm text-gray-900">{{ $product->stock_quantity }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Catégorie -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Catégorisation</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Catégorie</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->category->name }}</dd>
                            </div>
                            @if($product->subCategory)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Sous-catégorie</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->subCategory->name }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Statut -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Statut</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @if($product->is_active)
                                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $product->is_active ? 'Actif' : 'Inactif' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $product->is_active ? 'Visible dans la boutique' : 'Masqué de la boutique' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Statistiques</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Créé le</dt>
                                <dd class="text-sm text-gray-900">{{ $product->created_at->format('d/m/Y') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Dernière modification</dt>
                                <dd class="text-sm text-gray-900">{{ $product->updated_at->format('d/m/Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection