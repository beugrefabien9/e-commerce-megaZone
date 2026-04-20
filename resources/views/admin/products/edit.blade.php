@extends('layouts.admin')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Modifier le produit</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Modifiez les informations du produit "{{ $product->name }}"
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                            <div class="grid grid-cols-3 gap-6">
                                <div class="col-span-3 sm:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nom du produit</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" name="description" rows="4" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>{{ old('description', $product->description) }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700">Prix ( FCFA)</label>
                                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                                <div>
                                    <label for="sale_price" class="block text-sm font-medium text-gray-700">Prix soldé ( FCFA)</label>
                                    <input type="number" step="0.01" name="sale_price" id="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div>
                                <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock</label>
                                <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700">Catégorie</label>
                                    <select id="category_id" name="category_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Sélectionnez une catégorie</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="sub_category_id" class="block text-sm font-medium text-gray-700">Sous-catégorie</label>
                                    <select id="sub_category_id" name="sub_category_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Sélectionnez une sous-catégorie</option>
                                        @foreach($subCategories as $subCategory)
                                            <option value="{{ $subCategory->id }}" data-category="{{ $subCategory->category_id }}" {{ old('sub_category_id', $product->sub_category_id) == $subCategory->id ? 'selected' : '' }}>{{ $subCategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Images actuelles du produit</label>
                                
                                @if($product->images->count() > 0)
                                    <div class="mt-2 grid grid-cols-3 gap-4">
                                        @foreach($product->images as $image)
                                            <div class="relative group border-2 border-gray-200 rounded-lg overflow-hidden">
                                                @php
                                                    $imagePath = $image->image_path;
                                                    $isExternalUrl = str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://');
                                                    $imageSrc = $isExternalUrl ? $imagePath : asset('storage/' . $imagePath);
                                                @endphp
                                                <img src="{{ $imageSrc }}" alt="{{ $image->alt_text }}" class="w-full h-32 object-cover">
                                                
                                                @if($image->is_primary)
                                                    <span class="absolute top-0 left-0 bg-indigo-600 text-white text-xs px-2 py-1 rounded-br">Principale</span>
                                                @endif
                                                
                                                <!-- Checkbox pour supprimer -->
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all flex items-center justify-center">
                                                    <label class="hidden group-hover:flex items-center bg-white rounded-lg px-3 py-2 cursor-pointer">
                                                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="mr-2">
                                                        <span class="text-sm text-red-600 font-medium">Supprimer</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Survolez une image pour la supprimer</p>
                                @else
                                    <p class="text-sm text-gray-500">Aucune image pour ce produit</p>
                                @endif
                            </div>

                            <div class="border-t border-gray-200 pt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ajouter de nouvelles images</label>
                                
                                <!-- Upload de fichiers -->
                                <div class="mb-4">
                                    <label for="images" class="block text-sm text-gray-600 mb-1">Option 1 : Télécharger des images</label>
                                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <p class="mt-1 text-sm text-gray-500">Sélectionnez plusieurs images si nécessaire.</p>
                                </div>

                                <!-- Séparateur -->
                                <div class="relative mb-4">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-300"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-2 bg-white text-gray-500">ou</span>
                                    </div>
                                </div>

                                <!-- URL d'image -->
                                <div>
                                    <label for="image_url" class="block text-sm text-gray-600 mb-1">Option 2 : URL de l'image</label>
                                    <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <p class="mt-1 text-xs text-gray-500">Entrez une URL d'image si vous ne souhaitez pas télécharger une image.</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <input id="is_active" name="is_active" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Produit actif
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input id="is_featured" name="is_featured" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                    Produit en vedette
                                </label>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <a href="{{ route('admin.products.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Mettre à jour
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('category_id').addEventListener('change', function() {
    const categoryId = this.value;
    const subCategorySelect = document.getElementById('sub_category_id');
    const options = subCategorySelect.querySelectorAll('option');

    options.forEach(option => {
        if (option.value === '' || option.getAttribute('data-category') === categoryId) {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    });

    subCategorySelect.value = '';
});

// Trigger change on load to filter subcategories
document.getElementById('category_id').dispatchEvent(new Event('change'));
</script>
@endsection
