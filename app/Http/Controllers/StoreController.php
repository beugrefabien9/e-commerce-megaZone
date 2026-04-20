<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    // Page d'accueil de la boutique
    public function index()
    {
        // Récupère toutes les catégories actives
        $categories = Category::where('is_active', true)->get();

        // Récupère les produits actifs avec leurs relations (image principale + catégorie)
        // latest() = trie par les plus récents
        // paginate(12) = 12 produits par page
        $products = Product::where('is_active', true)
            ->with(['primaryImage', 'category'])
            ->latest()
            ->paginate(12);
        
        // Récupère les produits mis en avant (featured)
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with(['primaryImage', 'category'])
            ->take(8) // limite à 8 produits
            ->get();

        // Envoie les données à la vue
        return view('store.index', compact('categories', 'products', 'featuredProducts'));
    }

    // Afficher les produits d'une catégorie
    public function category(Category $category)
    {
        // Récupère les produits de la catégorie sélectionnée
        $products = $category->products()
            ->where('is_active', true)
            ->with(['primaryImage', 'subCategory']) // relations
            ->paginate(12);

        // Récupère les sous-catégories actives
        $subCategories = $category->subCategories()
            ->where('is_active', true)
            ->get();

        // Retourne la vue avec les données
        return view('store.category', compact('category', 'products', 'subCategories'));
    }

    // Afficher le détail d'un produit
    public function product(Product $product)
    {
        // Si le produit n'est pas actif → erreur 404
        if (!$product->is_active) {
            abort(404);
        }

        // Charge les relations du produit (images, primaryImage, catégorie, sous-catégorie)
        $product->load(['images', 'primaryImage', 'category', 'subCategory']);

        // Récupère les produits similaires (même catégorie)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id) // exclure le produit actuel
            ->where('is_active', true)
            ->with('primaryImage')
            ->take(4) // limite à 4 produits
            ->get();

        // Envoie les données à la vue
        return view('store.product', compact('product', 'relatedProducts'));
    }

    // Recherche de produits
    public function search(Request $request)
    {
        // Récupère les paramètres de recherche
        $query = $request->get('q'); // mot-clé
        $category = $request->get('category'); // catégorie sélectionnée

        // Construction de la requête
        $products = Product::where('is_active', true)
            ->with(['primaryImage', 'category'])

            // Si un mot-clé existe → filtre par nom ou description
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })

            // Si une catégorie est sélectionnée → filtre par catégorie
            ->when($category, function ($q) use ($category) {
                $q->where('category_id', $category);
            })

            ->paginate(12); // pagination

        // Récupère les catégories pour les filtres dans la vue
        $categories = Category::where('is_active', true)->get();

        // Retourne la vue avec les résultats
        return view('store.search', compact('products', 'categories', 'query', 'category'));
    }
}