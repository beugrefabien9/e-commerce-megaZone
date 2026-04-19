@extends('layouts.app')

@section('content')
<div class="bg-gray-50">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="mt-4 text-3xl font-extrabold text-gray-900">Commande confirmée !</h1>
            <p class="mt-2 text-lg text-gray-600">Merci pour votre commande. Votre reçu est ci-dessous.</p>
        </div>

        <!-- Receipt -->
        <div id="receipt" class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Receipt Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">Mega Zone</h2>
                        <p class="text-indigo-200 text-sm mt-1">Reçu de commande</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                            <p class="text-xs text-indigo-200">Référence</p>
                            <p class="text-xl font-bold font-mono">{{ $order->order_number }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receipt Content -->
            <div class="px-6 py-8">
                <!-- Order Info Grid -->
                <div class="grid grid-cols-2 gap-6 mb-8 pb-8 border-b border-gray-200">
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Date de commande</h3>
                        <p class="text-sm font-medium text-gray-900">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Statut</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Adresse de livraison</h3>
                        <p class="text-sm text-gray-900">{{ $order->shipping_address }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Nom du client</h3>
                        <p class="text-sm font-medium text-gray-900">{{ $order->billing_address }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Numéro de téléphone</h3>
                        <p class="text-sm font-medium text-gray-900">
                            @php
                                $phone = '';
                                if ($order->notes && preg_match('/Tél: ([^|]+)/', $order->notes, $matches)) {
                                    $phone = trim($matches[1]);
                                }
                            @endphp
                            @if($phone)
                                <a href="tel:{{ $phone }}" class="text-indigo-600 hover:text-indigo-700 font-semibold">{{ $phone }}</a>
                            @else
                                <span class="text-gray-500">Non spécifié</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Méthode de paiement</h3>
                        <p class="text-sm font-medium text-gray-900">
                            @if($order->payment_method === 'cash_on_delivery')
                                💰 Paiement à la livraison
                            @elseif($order->payment_method === 'card')
                                💳 Carte de crédit/débit
                            @elseif($order->payment_method === 'bank_transfer')
                                🏦 Virement bancaire
                            @else
                                {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Statut du paiement</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            @if($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Articles commandés</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center">
                                            @if($item->product && $item->product->primaryImage)
                                                <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" alt="{{ $item->product->name }}" class="h-12 w-12 rounded object-cover mr-3">
                                            @else
                                                <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center mr-3">
                                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $item->product->name ?? 'Produit inconnu' }}</p>
                                                <p class="text-xs text-gray-500">{{ $item->product->category->name ?? '' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center text-sm text-gray-900">{{ number_format($item->price, 0, ',', ' ') }} FCFA</td>
                                    <td class="px-4 py-4 text-center text-sm text-gray-900">{{ $item->quantity }}</td>
                                    <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">{{ number_format($item->total, 0, ',', ' ') }} FCFA</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Totals -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Sous-total</span>
                            <span class="font-medium">{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">TVA (20%)</span>
                            <span class="font-medium">{{ number_format($order->tax_amount, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Livraison</span>
                            <span class="font-medium">{{ $order->shipping_amount > 0 ? number_format($order->shipping_amount, 0, ',', ' ') . ' FCFA' : 'Gratuit' }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3 flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-indigo-600">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </div>

                <!-- Thank You Message -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">Merci pour votre confiance !</p>
                    <p class="text-xs text-gray-500 mt-2">Pour toute question, contactez-nous avec votre référence de commande : <strong class="font-mono">{{ $order->order_number }}</strong></p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="window.print()" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Imprimer le reçu
            </button>
            <a href="{{ route('store.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Continuer vos achats
                <svg class="-mr-1 ml-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #receipt, #receipt * {
        visibility: visible;
    }
    #receipt {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none;
    }
}
</style>
@endsection