@extends('layouts.app')

@section('content')
<div class="bg-white">
    <div style="max-width: 1280px; margin: 0 auto; padding: 32px 16px;">
        <div style="display: block;">
            <!-- Image gallery -->
            <div style="width: 100%;">
                @if($product->images->count() > 0)
                    <div style="width: 100%; aspect-ratio: 1 / 1;">
                        @php
                            $primaryImage = $product->primaryImage ?? $product->images->first();
                            $primaryImagePath = $primaryImage->image_path;
                            if(str_starts_with($primaryImagePath, 'http://') || str_starts_with($primaryImagePath, 'https://')) {
                                $mainImageSrc = $primaryImagePath;
                            } else {
                                $mainImageSrc = asset('storage/' . $primaryImagePath);
                            }
                        @endphp
                        <img id="main-image" src="{{ $mainImageSrc }}" alt="{{ $primaryImage->alt_text }}" style="width: 100%; height: 100%; object-fit: cover; object-position: center; border-radius: 8px;">
                    </div>
                    @if($product->images->count() > 1)
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-top: 12px;">
                            @foreach($product->images as $image)
                            @php
                                $imagePath = $image->image_path;
                                if(str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
                                    $imageSrc = $imagePath;
                                } else {
                                    $imageSrc = asset('storage/' . $imagePath);
                                }
                            @endphp
                            <button onclick="changeImage('{{ $imageSrc }}')" style="aspect-ratio: 1 / 1; border: 2px solid {{ $image->is_primary ? '#6366f1' : '#e5e7eb' }}; border-radius: 8px; overflow: hidden;">
                                <img src="{{ $imageSrc }}" alt="{{ $image->alt_text }}" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                            </button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div style="width: 100%; aspect-ratio: 1 / 1; background: #e5e7eb; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <span style="color: #6b7280;">Pas d'image disponible</span>
                    </div>
                @endif
            </div>

            <!-- Product info -->
            <div style="margin-top: 24px;">
                <h1 style="font-size: 24px; font-weight: 800; color: #111827; line-height: 1.3;">{{ $product->name }}</h1>

                <div style="margin-top: 12px;">
                    <p style="font-size: 24px; font-weight: 700; color: #111827;">
                        @if($product->isOnSale())
                            <span style="text-decoration: line-through; color: #9ca3af; font-size: 16px; margin-right: 8px;">{{ number_format($product->price, 2) }}  FCFA</span>
                            <span style="color: #dc2626;">{{ number_format($product->sale_price, 2) }}  FCFA</span>
                        @else
                            {{ number_format($product->price, 2) }}  FCFA
                        @endif
                    </p>
                </div>

                <div style="margin-top: 20px;">
                    <div style="font-size: 14px; color: #374151; line-height: 1.7;">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <div style="display: flex; align-items: center;">
                        <span style="color: #6b7280;">Stock:</span>
                        @if($product->isInStock())
                            <span style="margin-left: 8px; color: #16a34a; font-weight: 600;">{{ $product->stock_quantity }} disponibles</span>
                        @else
                            <span style="margin-left: 8px; color: #dc2626; font-weight: 600;">Rupture de stock</span>
                        @endif
                    </div>
                </div>

                <div style="margin-top: 24px;">
                    @if($product->isInStock())
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                <div style="display: flex; align-items: center; border: 1px solid #d1d5db; border-radius: 6px; width: fit-content;">
                                    <button type="button" onclick="decrementQuantity()" style="padding: 8px 12px; color: #6b7280; background: none; border: none; cursor: pointer; font-size: 18px;">-</button>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" style="width: 60px; text-align: center; border: none; font-size: 16px;">
                                    <button type="button" onclick="incrementQuantity()" style="padding: 8px 12px; color: #6b7280; background: none; border: none; cursor: pointer; font-size: 18px;">+</button>
                                </div>
                                <button type="submit" style="width: 100%; background: #4f46e5; border: none; border-radius: 8px; padding: 14px 24px; font-size: 16px; font-weight: 600; color: white; cursor: pointer;">
                                    Ajouter au panier
                                </button>
                            </div>
                        </form>
                    @else
                        <button disabled style="width: 100%; background: #9ca3af; border: none; border-radius: 8px; padding: 14px 24px; font-size: 16px; font-weight: 600; color: white; cursor: not-allowed;">
                            Rupture de stock
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Responsive styles for product page -->
<style>
    @media (min-width: 640px) {
        div[style*="padding: 32px 16px"] { padding: 48px 24px !important; }
        h1[style*="font-size: 24px"] { font-size: 28px !important; }
    }
    @media (min-width: 1024px) {
        div[style*="display: block;"] { display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 48px !important; align-items: start !important; }
        div[style*="padding: 32px 16px"] { padding: 64px 32px !important; }
        h1[style*="font-size: 24px"] { font-size: 32px !important; }
    }
</style>

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