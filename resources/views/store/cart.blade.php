@extends('layouts.app')

@section('content')
<div class="bg-white">
    <div style="max-width: 1280px; margin: 0 auto; padding: 32px 16px;">
        <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 24px;">
            <h1 style="font-size: 24px; font-weight: 800; color: #111827;">Mon panier</h1>
            <span style="font-size: 14px; color: #6b7280;">{{ count($cartItems) }} article(s)</span>
        </div>

        @if(count($cartItems) > 0)
            <div style="display: block; margin-top: 32px;">
                <section aria-labelledby="cart-heading" style="width: 100%;">
                    <h2 id="cart-heading" class="sr-only">Articles dans votre panier</h2>

                    <ul role="list" class="border-t border-b border-gray-200 divide-y divide-gray-200">
                        @foreach($cartItems as $item)
                        <li style="display: flex; padding: 20px 0; gap: 16px;">
                            <div class="flex-shrink-0">
                                @if($item['product']->primaryImage)
                                    @php
                                        $cartImagePath = $item['product']->primaryImage->image_path;
                                        if(str_starts_with($cartImagePath, 'http://') || str_starts_with($cartImagePath, 'https://')) {
                                            $cartImageSrc = $cartImagePath;
                                        } else {
                                            $cartImageSrc = asset('storage/' . $cartImagePath);
                                        }
                                    @endphp
                                    <img src="{{ $cartImageSrc }}" alt="{{ $item['product']->primaryImage->alt_text }}" style="width: 96px; height: 96px; border-radius: 6px; object-fit: cover; object-position: center; flex-shrink: 0;">
                                @else
                                    <div style="width: 96px; height: 96px; background: #e5e7eb; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <span style="color: #6b7280; font-size: 12px;">Pas d'image</span>
                                    </div>
                                @endif
                            </div>

                            <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between; min-width: 0;">
                                <div style="position: relative; padding-right: 24px;">
                                    <div>
                                        <div class="flex justify-between">
                                            <h3 class="text-sm">
                                                <a href="{{ route('store.product', $item['product']) }}" class="font-medium text-gray-700 hover:text-gray-800">
                                                    {{ $item['product']->name }}
                                                </a>
                                            </h3>
                                        </div>
                                        <div class="mt-1 flex text-sm">
                                            <p class="text-gray-500">{{ $item['product']->category->name }}</p>
                                        </div>
                                        <p class="mt-1 text-sm font-medium text-gray-900">{{ number_format($item['price'], 2) }} FCFA</p>
                                    </div>

                                        <div style="margin-top: 12px;">
                                        <div class="flex items-center">
                                            <form action="{{ route('cart.update', $item['product']) }}" method="POST" id="update-form-{{ $item['product']->id }}">
                                                @csrf
                                                @method('PATCH')
                                            </form>
                                            <label for="quantity-{{ $item['product']->id }}" class="sr-only">Quantité</label>
                                            <select id="quantity-{{ $item['product']->id }}" name="quantity" form="update-form-{{ $item['product']->id }}" onchange="this.form.submit()" style="max-width: 100%; border-radius: 6px; border: 1px solid #d1d5db; padding: 6px 10px; font-size: 14px; color: #374151; font-weight: 500; background: white;">
                                                @for($i = 1; $i <= min($item['product']->stock_quantity, 10); $i++)
                                                    <option value="{{ $i }}" {{ $item['quantity'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div style="position: absolute; top: 0; right: 0;">
                                            <form action="{{ route('cart.remove', $item['product']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="-m-2 p-2 inline-flex text-gray-400 hover:text-gray-500">
                                                    <span class="sr-only">Supprimer</span>
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <p class="mt-4 flex text-sm text-gray-700 space-x-2">
                                    <svg class="flex-shrink-0 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>En stock ({{ $item['product']->stock_quantity }} disponibles)</span>
                                </p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </section>

                <!-- Order summary -->
                <section aria-labelledby="summary-heading" style="margin-top: 32px; background: #f9fafb; border-radius: 8px; padding: 20px;">
                    <h2 id="summary-heading" class="text-lg font-medium text-gray-900">Récapitulatif de la commande</h2>

                    <dl class="mt-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Sous-total</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ number_format($total, 2) }} FCFA</dd>
                        </div>
                        <div class="border-t border-gray-200 pt-4 flex items-center justify-between">
                            <dt class="text-base font-medium text-gray-900">Total</dt>
                            <dd class="text-base font-medium text-gray-900">{{ number_format($total, 2) }} FCFA</dd>
                        </div>
                    </dl>

                    <div class="mt-6">
                        <a href="{{ route('checkout.index') }}" class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500">
                            Passer la commande
                        </a>
                    </div>

                    <div class="mt-6 text-center text-sm text-gray-500">
                        <p>
                            ou <a href="{{ route('store.index') }}" class="text-indigo-600 font-medium hover:text-indigo-500">continuer vos achats<span aria-hidden="true"> &rarr;</span></a>
                        </p>
                    </div>
                </section>
            </div>
        @else
            <div class="mt-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13v8a2 2 0 002 2h10a2 2 0 002-2v-3"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Votre panier est vide</h3>
                <p class="mt-1 text-sm text-gray-500">Commencez par ajouter des produits à votre panier.</p>
                <div class="mt-6">
                    <a href="{{ route('store.index') }}" class="text-indigo-600 hover:text-indigo-500">
                        Continuer vos achats<span aria-hidden="true"> &rarr;</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Responsive styles for cart -->
<style>
    @media (min-width: 640px) {
        div[style*="padding: 32px 16px"] { padding: 48px 24px !important; }
        h1[style*="font-size: 24px"] { font-size: 30px !important; }
    }
    @media (min-width: 1024px) {
        div[style*="display: block;"][style*="margin-top: 32px;"] { display: grid !important; grid-template-columns: 7fr 5fr !important; gap: 48px !important; }
        section[style*="width: 100%"] { grid-column: 1 !important; }
        section[style*="margin-top: 32px"] { margin-top: 0 !important; grid-column: 2 !important; }
    }
</style>
@endsection