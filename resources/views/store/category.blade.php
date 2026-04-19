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
    <div style="max-width: 1280px; margin: 0 auto; padding: 32px 16px;">
        @if($products->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 16px;" class="product-grid">
                @foreach($products as $product)
                <div style="position: relative; background: white; border-radius: 8px; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); display: flex; flex-direction: column;">
                    <div style="width: 100%; min-height: 280px; background-color: #e5e7eb; border-radius: 8px 8px 0 0; overflow: hidden; position: relative;" class="product-image">
                        @if($product->primaryImage)
                            @php
                                $imagePath = $product->primaryImage->image_path;
                                if(str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
                                    $imageSrc = $imagePath;
                                } else {
                                    $imageSrc = asset('storage/' . $imagePath);
                                }
                            @endphp
                            <img src="{{ $imageSrc }}" alt="{{ $product->primaryImage->alt_text }}" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                        @else
                            <div style="width: 100%; height: 100%; background: #d1d5db; display: flex; align-items: center; justify-content: center;">
                                <svg style="height: 64px; width: 64px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        @if($product->isOnSale())
                            <span style="position: absolute; top: 8px; right: 8px; background: #ef4444; color: white; font-size: 12px; font-weight: 700; padding: 4px 8px; border-radius: 4px;">
                                Promo
                            </span>
                        @endif
                    </div>
                    <div style="padding: 16px; flex: 1; display: flex; flex-direction: column;">
                        <div style="margin-bottom: 8px; flex: 1;">
                            <h3 style="font-size: 14px; font-weight: 500; color: #111827; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-height: 1.4; max-height: 2.8em;">
                                <a href="{{ route('store.product', $product) }}" style="text-decoration: none; color: inherit;">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            @if($product->subCategory)
                                <p style="margin-top: 4px; font-size: 12px; color: #6b7280; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $product->subCategory->name }}</p>
                            @endif
                        </div>
                        <div style="margin-top: auto;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                                <p style="font-size: 14px; font-weight: 500;">
                                    @if($product->isOnSale())
                                        <span style="text-decoration: line-through; color: #9ca3af; font-size: 12px;">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                                        <span style="color: #dc2626; font-weight: 700; margin-left: 4px;">{{ number_format($product->sale_price, 0, ',', ' ') }} FCFA</span>
                                    @else
                                        <span style="color: #111827;">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                                    @endif
                                </p>
                                @if($product->isInStock())
                                    <span style="font-size: 12px; color: #16a34a; font-weight: 500;">En stock</span>
                                @else
                                    <span style="font-size: 12px; color: #dc2626; font-weight: 500;">Rupture</span>
                                @endif
                            </div>
                            <a href="{{ route('store.product', $product) }}" style="display: block; text-align: center; background: #4f46e5; color: white; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 500; text-decoration: none; transition: background-color 0.2s;">
                                Voir le produit
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div style="margin-top: 32px;">
                {{ $products->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 64px 16px;">
                <svg style="margin: 0 auto; height: 64px; width: 64px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 style="margin-top: 16px; font-size: 18px; font-weight: 500; color: #111827;">Aucun produit trouvé dans cette catégorie</h3>
                <p style="margin-top: 8px; font-size: 14px; color: #6b7280;">Revenez bientôt pour découvrir nos produits !</p>
            </div>
        @endif
    </div>
</div>

<!-- Responsive styles -->
<style>
    @media (min-width: 640px) {
        .product-grid { grid-template-columns: repeat(2, 1fr) !important; }
    }
    @media (min-width: 768px) {
        .product-grid { grid-template-columns: repeat(3, 1fr) !important; }
    }
    @media (min-width: 1024px) {
        .product-grid { grid-template-columns: repeat(4, 1fr) !important; }
    }
    @media (max-width: 639px) {
        .product-image { min-height: 240px !important; }
    }
</style>
@endsection