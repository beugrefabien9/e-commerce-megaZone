@extends('layouts.admin')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Gestion des commandes</h1>
            <p class="mt-2 text-gray-600">Consultez et gérez toutes les commandes</p>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Toutes les commandes</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Liste complète des commandes passées sur la plateforme</p>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse($orders as $order)
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <h4 class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">
                                                Commande #{{ $order->order_number }}
                                            </a>
                                        </h4>
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <div class="mt-1 text-sm text-gray-600">
                                        <p>Client: {{ $order->user->name }} • {{ $order->orderItems->count() }} article(s) • {{ number_format($order->total_amount, 2) }} FCFA</p>
                                        <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                @empty
                <li>
                    <div class="px-4 py-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune commande</h3>
                        <p class="mt-1 text-sm text-gray-500">Il n'y a pas encore de commandes.</p>
                    </div>
                </li>
                @endforelse
            </ul>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection