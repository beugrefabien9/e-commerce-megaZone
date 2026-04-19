@extends('layouts.app')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
            <!-- Image gallery -->
            <div class="w-full">
                @if($product->images->count() > 0)
                    <div class="aspect-w-1 aspect-h-1">
                        @php
                            $primaryImagePath = $product->primaryImage->image_path;
                            if(str_starts_with($primaryImagePath, 'http://') || str_starts_with($primaryImagePath, 'https://')) {
                                $mainImageSrc = $primaryImagePath;
                            } else {
                                $mainImageSrc = asset('storage/' . $primaryImagePath);
                            }
                        @endphp
                        <img id="main-image" src="{{ $mainImageSrc }}" alt="{{ $product->primaryImage->alt_text }}" class="w-full h-full object-center object-cover rounded-lg">
                    </div>
                    @if($product->images->count() > 1)
                        <div class="grid grid-cols-4 gap-4 mt-4">
                            @foreach($product->images as $image)
                            @php
                                $imagePath = $image->image_path;
                                if(str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
                                    $imageSrc = $imagePath;
                                } else {
                                    $imageSrc = asset('storage/' . $imagePath);
                                }
                            @endphp
                            <button onclick="changeImage('{{ $imageSrc }}')" class="aspect-w-1 aspect-h-1 border-2 {{ $image->is_primary ? 'border-indigo-500' : 'border-gray-200' }} rounded-lg overflow-hidden hover:border-indigo-300">
                                <img src="{{ $imageSrc }}" alt="{{ $image->alt_text }}" class="w-full h-full object-center object-cover">
                            </button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-gray-500">Pas d'image disponible</span>
                    </div>
                @endif
            </div>

            <!-- Product info -->
            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $product->name }}</h1>

                <div class="mt-3">
                    <h2 class="sr-only">Informations sur le produit</h2>
                    <p class="text-3xl text-gray-900">
                        @if($product->isOnSale())
                            <span class="line-through text-gray-500 mr-2">{{ number_format($product->price, 2) }}  FCFA</span>
                            <span class="text-red-600">{{ number_format($product->sale_price, 2) }}  FCFA</span>
                        @else
                            {{ number_format($product->price, 2) }}  FCFA
                        @endif
                    </p>
                </div>

                <div class="mt-6">
                    <h3 class="sr-only">Description</h3>
                    <div class="text-base text-gray-700 space-y-6">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <div class="mt-8">
                    <div class="flex items-center">
                        <span class="text-gray-600">Stock:</span>
                        @if($product->isInStock())
                            <span class="ml-2 text-green-600">{{ $product->stock_quantity }} disponibles</span>
                        @else
                            <span class="ml-2 text-red-600">Rupture de stock</span>
                        @endif
                    </div>
                </div>

                <div class="mt-8">
                    @if($product->isInStock())
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex items-center">
                            @csrf
                            <div class="flex items-center border border-gray-300 rounded-md">
                                <button type="button" onclick="decrementQuantity()" class="p-2 text-gray-600 hover:text-gray-800">-</button>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-16 text-center border-0 focus:ring-0">
                                <button type="button" onclick="incrementQuantity()" class="p-2 text-gray-600 hover:text-gray-800">+</button>
                            </div>
                            <button type="submit" class="ml-4 bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Ajouter au panier
                            </button>
                        </form>
                    @else
                        <button disabled class="w-full bg-gray-400 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white cursor-not-allowed">
                            Rupture de stock
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<div class="bg-gray-50">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">Produits similaires</h2>
        <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            @foreach($relatedProducts as $relatedProduct)
            <div class="group relative">
                <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                    @if($relatedProduct->primaryImage)
                        @php
                            $relatedImagePath = $relatedProduct->primaryImage->image_path;
                            if(str_starts_with($relatedImagePath, 'http://') || str_starts_with($relatedImagePath, 'https://')) {
                                $relatedImageSrc = $relatedImagePath;
                            } else {
                                $relatedImageSrc = asset('storage/' . $relatedImagePath);
                            }
                        @endphp
                        <img src="{{ $relatedImageSrc }}" alt="{{ $relatedProduct->primaryImage->alt_text }}" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                    @else
                        <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                            <span class="text-gray-500">Pas d'image</span>
                        </div>
                    @endif
                </div>
                <div class="mt-4 flex justify-between">
                    <div>
                        <h3 class="text-sm text-gray-700">
                            <a href="{{ route('store.product', $relatedProduct) }}">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                {{ $relatedProduct->name }}
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">{{ $relatedProduct->category->name }}</p>
                    </div>
                    <p class="text-sm font-medium text-gray-900">
                        @if($relatedProduct->isOnSale())
                            <span class="line-through text-gray-500">{{ number_format($relatedProduct->price, 2) }} FCFA</span>
                            <span class="text-red-600">{{ number_format($relatedProduct->sale_price, 2) }} FCFA</span>
                        @else
                            {{ number_format($relatedProduct->price, 2) }} FCFA
                        @endif
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<script>
function changeImage(src) {
    document.getElementById('main-image').src = src;
}

function incrementQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
    }
}

function decrementQuantity() {
    const input = document.getElementById('quantity');
    const min = parseInt(input.getAttribute('min'));
    const current = parseInt(input.value);
    if (current > min) {
        input.value = current - 1;
    }
}
</script>
@endsection