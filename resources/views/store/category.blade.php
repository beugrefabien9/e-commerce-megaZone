@extends('layouts.app')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $category->name }}</h1>
        @if($category->description)
            <p class="mt-4 text-gray-500">{{ $category->description }}</p>
        @endif
    </div>
</div>

<!-- Subcategories -->
@if($subCategories->count() > 0)
<div class="bg-gray-50">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Sous-catégories</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($subCategories as $subCategory)
            <a href="#" class="bg-white px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                {{ $subCategory->name }}
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Products -->
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        @if($products->count() > 0)
            <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                @foreach($products as $product)
                <div class="group relative">
                    <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->primaryImage->alt_text }}" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                        @else
                            <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                                <span class="text-gray-500">Pas d'image</span>
                            </div>
                        @endif
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-gray-700">
                                <a href="{{ route('store.product', $product) }}">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    {{ $product->name }}
                                </a>
                            </h3>
                            @if($product->subCategory)
                                <p class="mt-1 text-sm text-gray-500">{{ $product->subCategory->name }}</p>
                            @endif
                        </div>
                        <p class="text-sm font-medium text-gray-900">
                            @if($product->isOnSale())
                                <span class="line-through text-gray-500">{{ number_format($product->price, 2) }} FCFA</span>
                                <span class="text-red-600">{{ number_format($product->sale_price, 2) }} FCFA</span>
                            @else
                                {{ number_format($product->price, 2) }} FCFA
                            @endif
                        </p>
                    </div>
                    @if(!$product->isInStock())
                        <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-xs">
                            Rupture de stock
                        </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-500">Aucun produit trouvé dans cette catégorie.</p>
            </div>
        @endif
    </div>
</div>
@endsection