@extends('layouts.app')

@section('content')
<div class="bg-white">
    <div style="max-width: 1280px; margin: 0 auto; padding: 32px 16px;">
        <h1 style="font-size: 24px; font-weight: 800; color: #111827;">Finaliser la commande</h1>

        <form action="{{ route('checkout.store') }}" method="POST" style="margin-top: 32px; display: block;">
            @csrf

            <section aria-labelledby="cart-heading" style="width: 100%;">
                <h2 id="cart-heading" class="sr-only">Articles dans votre panier</h2>

                <ul role="list" class="border-t border-b border-gray-200 divide-y divide-gray-200">
                    @foreach($cartItems as $item)
                    <li style="display: flex; padding: 20px 0; gap: 16px;">
                        <div class="flex-shrink-0">
                            @if($item['product']->primaryImage)
                                <img src="{{ asset('storage/' . $item['product']->primaryImage->image_path) }}" alt="{{ $item['product']->primaryImage->alt_text }}" style="width: 96px; height: 96px; border-radius: 6px; object-fit: cover; object-position: center; flex-shrink: 0;">
                            @else
                                <div style="width: 96px; height: 96px; background: #e5e7eb; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <span style="color: #6b7280; font-size: 12px;">Pas d'image</span>
                                </div>
                            @endif
                        </div>

                        <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between; min-width: 0;">
                            <div style="position: relative;">
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
                                    <p class="mt-1 text-sm font-medium text-gray-900">{{ number_format($item['price'], 2) }} FCFA x {{ $item['quantity'] }}</p>
                                </div>

                                    <div style="margin-top: 12px;">
                                    <p class="text-sm font-medium text-gray-900">{{ number_format($item['total'], 2) }} FCFA</p>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>

                <!-- Shipping & Billing Address -->
                <div class="mt-10 border-t border-gray-200 pt-10">
                    <h2 class="text-lg font-medium text-gray-900 mb-6">Informations de livraison et de contact</h2>

                    <div class="space-y-6">
                        <!-- Shipping Address - Ville/Quartier -->
                        <div>
                            <h3 class="text-base font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Adresse de livraison
                            </h3>
                            <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <label for="shipping_city" class="block text-sm font-medium text-gray-700">Ville <span class="text-red-500">*</span></label>
                                    <div class="mt-1">
                                        <input type="text" id="shipping_city" name="shipping_city" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Ex: Dakar" required>
                                    </div>
                                </div>

                                <div>
                                    <label for="shipping_quarter" class="block text-sm font-medium text-gray-700">Quartier <span class="text-red-500">*</span></label>
                                    <div class="mt-1">
                                        <input type="text" id="shipping_quarter" name="shipping_quarter" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Ex: Almadies" required>
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="shipping_details" class="block text-sm font-medium text-gray-700">Précisions sur l'adresse (optionnel)</label>
                                    <div class="mt-1">
                                        <textarea id="shipping_details" name="shipping_details" rows="2" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Rue, bâtiment, repère..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Billing - Phone Number -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-base font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                Informations de contact
                            </h3>
                            <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <label for="billing_phone" class="block text-sm font-medium text-gray-700">Numéro de téléphone <span class="text-red-500">*</span></label>
                                    <div class="mt-1">
                                        <input type="tel" id="billing_phone" name="billing_phone" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Ex: +221 77 123 45 67" required>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Nous vous contacterons à ce numéro pour la livraison</p>
                                </div>

                                <div>
                                    <label for="billing_name" class="block text-sm font-medium text-gray-700">Nom complet <span class="text-red-500">*</span></label>
                                    <div class="mt-1">
                                        <input type="text" id="billing_name" name="billing_name" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Votre nom complet" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment -->
                <div class="mt-10 border-t border-gray-200 pt-10">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Méthode de paiement</h2>

                    <fieldset>
                        <legend class="sr-only">Méthode de paiement</legend>
                        <div class="space-y-4">
                            <div class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-indigo-500 cursor-pointer transition-colors" onclick="document.getElementById('cash_on_delivery').checked = true">
                                <input id="cash_on_delivery" name="payment_method" type="radio" value="cash_on_delivery" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" checked>
                                <label for="cash_on_delivery" class="ml-3 flex-1 cursor-pointer">
                                    <span class="block text-sm font-medium text-gray-900">
                                        Paiement à la livraison
                                    </span>
                                    <span class="block text-xs text-gray-500 mt-1">Payez en espèces quand vous recevez votre commande</span>
                                </label>
                                <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>

                            <div class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-indigo-500 cursor-pointer transition-colors" onclick="document.getElementById('card').checked = true">
                                <input id="card" name="payment_method" type="radio" value="card" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                <label for="card" class="ml-3 flex-1 cursor-pointer">
                                    <span class="block text-sm font-medium text-gray-900">
                                        Carte de crédit/débit
                                    </span>
                                    <span class="block text-xs text-gray-500 mt-1">Visa, Mastercard, etc.</span>
                                </label>
                                <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>

                            <div class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-indigo-500 cursor-pointer transition-colors" onclick="document.getElementById('bank_transfer').checked = true">
                                <input id="bank_transfer" name="payment_method" type="radio" value="bank_transfer" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                <label for="bank_transfer" class="ml-3 flex-1 cursor-pointer">
                                    <span class="block text-sm font-medium text-gray-900">
                                        Virement bancaire
                                    </span>
                                    <span class="block text-xs text-gray-500 mt-1">Paiement directement depuis votre banque</span>
                                </label>
                                <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                </svg>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </section>

            <!-- Order summary -->
            <section aria-labelledby="summary-heading" style="margin-top: 32px; background: #f9fafb; border-radius: 8px; padding: 20px;">
                <h2 id="summary-heading" class="text-lg font-medium text-gray-900">Récapitulatif de la commande</h2>

                <dl class="mt-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">Sous-total</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ number_format($total, 2) }} FCFA</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">TVA (20%)</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ number_format($total * 0.20, 2) }} FCFA</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">Livraison</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $total > 50 ? 'Gratuit' : '5.99 FCFA' }}</dd>
                    </div>
                    <div class="border-t border-gray-200 pt-4 flex items-center justify-between">
                        <dt class="text-base font-medium text-gray-900">Total</dt>
                        <dd class="text-base font-medium text-gray-900">{{ number_format($total + ($total * 0.20) + ($total > 50 ? 0 : 5.99), 2) }} FCFA</dd>
                    </div>
                </dl>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500">
                        Confirmer la commande
                    </button>
                </div>

                <div class="mt-6 text-center text-sm text-gray-500">
                    <p>
                        En passant commande, vous acceptez nos <a href="#" class="text-indigo-600 font-medium hover:text-indigo-500">conditions générales</a>
                    </p>
                </div>
            </section>
        </form>
    </div>
</div>

<!-- Responsive styles for checkout -->
<style>
    @media (min-width: 640px) {
        div[style*="padding: 32px 16px"] { padding: 48px 24px !important; }
        h1[style*="font-size: 24px"] { font-size: 30px !important; }
    }
    @media (min-width: 1024px) {
        form[style*="display: block;"] { display: grid !important; grid-template-columns: 7fr 5fr !important; gap: 48px !important; align-items: start !important; }
        section[style*="width: 100%"] { grid-column: 1 !important; }
        section[style*="margin-top: 32px"][style*="background: #f9fafb;"] { margin-top: 0 !important; grid-column: 2 !important; }
    }
</style>
@endsection