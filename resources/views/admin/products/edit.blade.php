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
                                <label for="images" class="block text-sm font-medium text-gray-700">Images du produit</label>
                                <input type="file" name="images[]" id="images" multiple accept="image/*" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <p class="mt-1 text-sm text-gray-500">Sélectionnez plusieurs images si nécessaire.</p>
                                
                                @if($product->images->count() > 0)
                                    <div class="mt-2 grid grid-cols-3 gap-2">
                                        @foreach($product->images as $image)
                                            <div class="relative">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->alt_text }}" class="w-full h-24 object-cover rounded">
                                                @if($image->is_primary)
                                                    <span class="absolute top-0 right-0 bg-indigo-600 text-white text-xs px-2 py-1 rounded-bl">Principale</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            
                            /// Champ pour URL d'image
                            <div>
                                <label for="image_url" class="block text-sm font-medium text-gray-700">URL de l'image principale</label>
                                <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <p class="mt-1 text-sm text-gray-500">Entrez une URL d'image si vous ne souhaitez pas télécharger une image.</p>
                                // condition pour afficher URL de l'image a modifier
                                @if($product->images->count() > 0)
                                    <p class="mt-1 text-sm text-gray-500">URL actuelle : {{ asset('storage/' . $product->images->firstWhere('is_primary', true)->image_path) }}</p>
                                @endif
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
