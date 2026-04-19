@extends('layouts.app')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Mon panier</h1>
            <span class="text-sm text-gray-500">{{ count($cartItems) }} article(s)</span>
        </div>

        @if(count($cartItems) > 0)
            <div class="mt-12 lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start xl:gap-x-16">
                <section aria-labelledby="cart-heading" class="lg:col-span-7">
                    <h2 id="cart-heading" class="sr-only">Articles dans votre panier</h2>

                    <ul role="list" class="border-t border-b border-gray-200 divide-y divide-gray-200">
                        @foreach($cartItems as $item)
                        <li class="flex py-6 sm:py-10">
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
                                    <img src="{{ $cartImageSrc }}" alt="{{ $item['product']->primaryImage->alt_text }}" class="w-24 h-24 rounded-md object-center object-cover sm:w-32 sm:h-32">
                                @else
                                    <div class="w-24 h-24 bg-gray-200 rounded-md flex items-center justify-center sm:w-32 sm:h-32">
                                        <span class="text-gray-500 text-sm">Pas d'image</span>
                                    </div>
                                @endif
                            </div>

                            <div class="ml-4 flex-1 flex flex-col justify-between sm:ml-6">
                                <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
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

                                    <div class="mt-4 sm:mt-0 sm:pr-9">
                                        <div class="flex items-center">
                                            <form action="{{ route('cart.update', $item['product']) }}" method="POST" id="update-form-{{ $item['product']->id }}">
                                                @csrf
                                                @method('PATCH')
                                            </form>
                                            <label for="quantity-{{ $item['product']->id }}" class="sr-only">Quantité</label>
                                            <select id="quantity-{{ $item['product']->id }}" name="quantity" form="update-form-{{ $item['product']->id }}" onchange="this.form.submit()" class="max-w-full rounded-md border border-gray-300 py-1.5 text-base leading-5 font-medium text-gray-700 text-left shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                @for($i = 1; $i <= min($item['product']->stock_quantity, 10); $i++)
                                                    <option value="{{ $i }}" {{ $item['quantity'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="absolute top-0 right-0">
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
                <section aria-labelledby="summary-heading" class="mt-16 bg-gray-50 rounded-lg px-4 py-6 sm:p-6 lg:p-8 lg:mt-0 lg:col-span-5">
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
@endsection