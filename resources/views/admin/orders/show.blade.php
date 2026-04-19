@extends('layouts.admin')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Commande #{{ $order->order_number }}</h1>
                    <p class="mt-2 text-gray-600">Détails de la commande</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour aux commandes
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations de la commande -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Articles commandés</h3>
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-md overflow-hidden">
                                    @if($item->product->primaryImage)
                                        <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-center object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h4>
                                    <p class="text-sm text-gray-500">Quantité: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ number_format($item->price, 2) }} FCFA</p>
                                    <p class="text-sm text-gray-500">Sous-total: {{ number_format($item->quantity * $item->price, 2) }} FCFA</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations client et statut -->
            <div class="space-y-6">
                <!-- Informations client -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informations client</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nom du client</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900">{{ $order->billing_address }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Numéro de téléphone</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @php
                                        $phone = '';
                                        if ($order->notes && preg_match('/Tél: ([^|]+)/', $order->notes, $matches)) {
                                            $phone = trim($matches[1]);
                                        }
                                    @endphp
                                    @if($phone)
                                        <a href="tel:{{ $phone }}" class="text-indigo-600 hover:text-indigo-700 font-semibold">{{ $phone }}</a>
                                    @else
                                        <span class="text-gray-400">Non spécifié</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email du compte</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->user->email }}</dd>
                            </div>
                            @if($order->shipping_address)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Adresse de livraison</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_address }}</dd>
                            </div>
                            @endif
                            @if($order->payment_method)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Méthode de paiement</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($order->payment_method === 'cash_on_delivery')
                                        💰 Paiement à la livraison
                                    @elseif($order->payment_method === 'card')
                                        💳 Carte de crédit/débit
                                    @elseif($order->payment_method === 'bank_transfer')
                                        🏦 Virement bancaire
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                    @endif
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Statut de la commande -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Statut de la commande</h3>

                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                                <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>En traitement</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Livrée</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                </select>
                            </div>

                            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Mettre à jour le statut
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Statut de paiement -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Statut de paiement</h3>

                        <form action="{{ route('admin.orders.updatePaymentStatus', $order) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="payment_status" class="block text-sm font-medium text-gray-700">Paiement</label>
                                <select name="payment_status" id="payment_status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Payé</option>
                                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Échec</option>
                                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Remboursé</option>
                                </select>
                            </div>

                            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Mettre à jour le paiement
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Récapitulatif -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Récapitulatif</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Sous-total</dt>
                                <dd class="text-sm text-gray-900">{{ number_format($order->subtotal, 2) }} FCFA</dd>
                            </div>
                            @if($order->tax_amount > 0)
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">TVA</dt>
                                <dd class="text-sm text-gray-900">{{ number_format($order->tax_amount, 2) }} FCFA</dd>
                            </div>
                            @endif
                            @if($order->shipping_amount > 0)
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Frais de port</dt>
                                <dd class="text-sm text-gray-900">{{ number_format($order->shipping_amount, 2) }} FCFA</dd>
                            </div>
                            @endif
                            <div class="border-t pt-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-900">Total</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ number_format($order->total_amount, 2) }} FCFA</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Date de commande</dt>
                                <dd class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y à H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection