@extends('layouts.admin')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Détails de la sous-catégorie</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Informations sur la sous-catégorie "{{ $subCategory->name }}"
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom de la sous-catégorie</label>
                            <div class="mt-1 text-sm text-gray-900">{{ $subCategory->name }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Slug</label>
                            <div class="mt-1 text-sm text-gray-900">{{ $subCategory->slug }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Catégorie parente</label>
                            <div class="mt-1 text-sm text-gray-900">{{ $subCategory->category->name }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <div class="mt-1 text-sm text-gray-900">{{ $subCategory->description ?: 'Aucune description' }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Statut</label>
                            <div class="mt-1">
                                @if($subCategory->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de création</label>
                            <div class="mt-1 text-sm text-gray-900">{{ $subCategory->created_at->format('d/m/Y H:i') }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dernière modification</label>
                            <div class="mt-1 text-sm text-gray-900">{{ $subCategory->updated_at->format('d/m/Y H:i') }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre de produits</label>
                            <div class="mt-1 text-sm text-gray-900">{{ $subCategory->products()->count() }} produit(s)</div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <a href="{{ route('admin.sub-categories.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                            Retour à la liste
                        </a>
                        <a href="{{ route('admin.sub-categories.edit', $subCategory) }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                            Modifier
                        </a>
                        <form action="{{ route('admin.sub-categories.destroy', $subCategory) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette sous-catégorie ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
