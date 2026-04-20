@extends('layouts.admin')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Nouveau produit</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Ajoutez un nouveau produit à votre boutique.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
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
                                    <input type="text" name="name" id="name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" name="description" rows="4" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700">Prix ( FCFA)</label>
                                    <input type="number" step="0.01" name="price" id="price" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                                <div>
                                    <label for="sale_price" class="block text-sm font-medium text-gray-700">Prix soldé ( FCFA)</label>
                                    <input type="number" step="0.01" name="sale_price" id="sale_price" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div>
                                <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock</label>
                                <input type="number" name="stock_quantity" id="stock_quantity" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700">Catégorie</label>
                                    <select id="category_id" name="category_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Sélectionnez une catégorie</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="sub_category_id" class="block text-sm font-medium text-gray-700">Sous-catégorie</label>
                                    <select id="sub_category_id" name="sub_category_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Sélectionnez une sous-catégorie</option>
                                        @foreach($subCategories as $subCategory)
                                            <option value="{{ $subCategory->id }}" data-category="{{ $subCategory->category_id }}">{{ $subCategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" style="font-size: 2rem;">Image principale du produit</label>
                                
                                <!-- Option 1: Upload de fichier -->
                                <div class="mb-4">
                                    <label for="primary_image" class="block text-sm text-gray-600 mb-1">Option 1 : Télécharger une image</label>
                                    <input type="file" name="primary_image" id="primary_image" accept="image/*" class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100">
                                    <p class="mt-1 text-xs text-gray-500">Formats acceptés : JPEG, PNG, JPG, GIF (max 2MB)</p>
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

                                <!-- Option 2: URL d'image -->
                                <div>
                                    <label for="primary_image_url" class="block text-sm text-gray-600 mb-1">Option 2 : URL de l'image principale</label>
                                    <input type="url" name="primary_image_url" id="primary_image_url" placeholder="https://example.com/image.jpg" 
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <p class="mt-1 text-xs text-gray-500">Collez le lien direct vers l'image (JPEG, PNG, GIF, WebP)</p>
                                    
                                    <!-- Preview de l'URL -->
                                    <div id="primary-url-preview" class="mt-3 hidden">
                                        <p class="text-xs text-gray-600 mb-1">Aperçu :</p>
                                        <img id="primary-preview-image" src="" alt="Aperçu" class="max-w-xs h-auto rounded-lg border border-gray-300">
                                    </div>
                                </div>
                            </div>

                            <!-- Images détaillées (galerie) -->
                            <div class="border-t border-gray-200 pt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2 " style="font-size: 2rem;">Images détaillées du produit (galerie)</label>
                                <p class="text-xs text-gray-500 mb-4">Ajoutez des images supplémentaires pour montrer le produit sous différents angles</p>
                                
                                <!-- Option 1: Upload de fichiers -->
                                <div class="mb-4">
                                    <label for="detail_images" class="block text-sm text-gray-600 mb-1">Option 1 : Télécharger des images</label>
                                    <input type="file" name="detail_images[]" id="detail_images" multiple accept="image/*" class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100">
                                    <p class="mt-1 text-xs text-gray-500">Formats acceptés : JPEG, PNG, JPG, GIF (max 2MB par image)</p>
                                    <p class="mt-1 text-xs text-gray-500">Vous pouvez sélectionner plusieurs images</p>
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

                                <!-- Option 2: URLs d'images -->
                                <div>
                                    <label for="detail_image_urls" class="block text-sm text-gray-600 mb-1">Option 2 : URLs des images (une par ligne)</label>
                                    <textarea name="detail_image_urls" id="detail_image_urls" rows="4" placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg&#10;https://example.com/image3.jpg" 
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Collez les liens directs vers les images (un lien par ligne)</p>
                                    
                                    <!-- Preview des URLs -->
                                    <div id="detail-urls-preview" class="mt-3 hidden">
                                        <p class="text-xs text-gray-600 mb-2">Aperçu des images :</p>
                                        <div id="detail-preview-container" class="grid grid-cols-3 gap-2"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <input id="is_active" name="is_active" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" checked>
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Produit actif
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input id="is_featured" name="is_featured" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
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
                                Créer le produit
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

// Preview de l'image principale URL
const primaryImageUrlInput = document.getElementById('primary_image_url');
const primaryUrlPreview = document.getElementById('primary-url-preview');
const primaryPreviewImage = document.getElementById('primary-preview-image');

if (primaryImageUrlInput) {
    let debounceTimer;
    primaryImageUrlInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const url = this.value.trim();
        
        debounceTimer = setTimeout(() => {
            if (url) {
                // Vérifier si c'est une URL valide
                try {
                    new URL(url);
                    primaryPreviewImage.src = url;
                    primaryUrlPreview.classList.remove('hidden');
                    
                    primaryPreviewImage.onload = function() {
                        primaryUrlPreview.classList.remove('hidden');
                    };
                    
                    primaryPreviewImage.onerror = function() {
                        primaryUrlPreview.classList.add('hidden');
                    };
                } catch (e) {
                    primaryUrlPreview.classList.add('hidden');
                }
            } else {
                primaryUrlPreview.classList.add('hidden');
            }
        }, 500);
    });
}

// Preview des images détaillées URLs
const detailImageUrlsInput = document.getElementById('detail_image_urls');
const detailUrlsPreview = document.getElementById('detail-urls-preview');
const detailPreviewContainer = document.getElementById('detail-preview-container');

if (detailImageUrlsInput) {
    let debounceTimer;
    detailImageUrlsInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const urls = this.value.trim();
        
        debounceTimer = setTimeout(() => {
            if (urls) {
                const urlList = urls.split('\n').filter(url => url.trim() !== '');
                detailPreviewContainer.innerHTML = '';
                
                if (urlList.length > 0) {
                    detailUrlsPreview.classList.remove('hidden');
                    
                    urlList.forEach((url, index) => {
                        const urlTrimmed = url.trim();
                        try {
                            new URL(urlTrimmed);
                            const wrapper = document.createElement('div');
                            wrapper.className = 'aspect-w-1 aspect-h-1';
                            
                            const img = document.createElement('img');
                            img.src = urlTrimmed;
                            img.alt = `Aperçu ${index + 1}`;
                            img.className = 'w-full h-20 object-cover rounded-lg border border-gray-300';
                            
                            img.onload = function() {
                                wrapper.appendChild(img);
                                detailPreviewContainer.appendChild(wrapper);
                            };
                            
                            img.onerror = function() {
                                // Ne pas ajouter l'image si elle ne charge pas
                            };
                        } catch (e) {
                            // URL invalide, ignorer
                        }
                    });
                    
                    if (detailPreviewContainer.children.length === 0) {
                        detailUrlsPreview.classList.add('hidden');
                    }
                } else {
                    detailUrlsPreview.classList.add('hidden');
                }
            } else {
                detailUrlsPreview.classList.add('hidden');
                detailPreviewContainer.innerHTML = '';
            }
        }, 500);
    });
}
</script>
@endsection