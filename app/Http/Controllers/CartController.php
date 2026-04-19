<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Afficher le panier
    public function index()
    {
        // Récupère le panier depuis la session (ou tableau vide si inexistant)
        $cart = Session::get('cart', []);
        $cartItems = []; // Contiendra les produits formatés pour la vue
        $total = 0; // Total général du panier

        // Parcours de chaque produit dans le panier
        foreach ($cart as $productId => $item) {

            // Récupère le produit depuis la base de données
            $product = Product::find($productId);

            // Vérifie que le produit existe, est actif et en stock
            if ($product && $product->is_active && $product->isInStock()) {

                // Ajoute les informations du produit dans le tableau
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'], // quantité choisie
                    'price' => $product->current_price, // prix actuel
                    'total' => $product->current_price * $item['quantity'] // total par produit
                ];

                // Ajoute au total général
                $total += $product->current_price * $item['quantity'];
            }
        }

        // Retourne la vue avec les données du panier
        return view('store.cart', compact('cartItems', 'total'));
    }

    // Ajouter un produit au panier
    public function add(Request $request, Product $product)
    {
        // Validation de la quantité envoyée par le formulaire
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock_quantity,
        ]);

        // Vérifie que le produit est disponible
        if (!$product->is_active || !$product->isInStock()) {
            return back()->with('error', 'Produit non disponible.');
        }

        // Récupère le panier actuel
        $cart = Session::get('cart', []);
        $quantity = $request->quantity;

        // Si le produit existe déjà dans le panier
        if (isset($cart[$product->id])) {
            // On ajoute la quantité
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            // Sinon on crée une nouvelle entrée
            $cart[$product->id] = [
                'quantity' => $quantity,
                'added_at' => now(), // date d’ajout
            ];
        }

        // Sécurité : ne pas dépasser le stock disponible
        $cart[$product->id]['quantity'] = min(
            $cart[$product->id]['quantity'],
            $product->stock_quantity
        );

        // Sauvegarde du panier en session
        Session::put('cart', $cart);

        // Redirige avec message de succès
        return back()->with('success', 'Produit ajouté au panier.');
    }

    // Mettre à jour la quantité d’un produit
    public function update(Request $request, Product $product)
    {
        // Validation (quantité peut être 0 pour supprimer)
        $request->validate([
            'quantity' => 'required|integer|min:0|max:' . $product->stock_quantity,
        ]);

        // Vérifie disponibilité du produit
        if (!$product->is_active || !$product->isInStock()) {
            return back()->with('error', 'Ce produit n\'est plus disponible.');
        }

        // Récupère le panier
        $cart = Session::get('cart', []);

        // Vérifie si le produit existe dans le panier
        if (!isset($cart[$product->id])) {
            return back()->with('error', 'Ce produit n\'est pas dans votre panier.');
        }

        // Si quantité <= 0 → supprimer le produit
        if ($request->quantity <= 0) {
            unset($cart[$product->id]);
            return back()->with('success', 'Produit retiré du panier.');
        } else {
            // Sinon mise à jour de la quantité
            $cart[$product->id]['quantity'] = $request->quantity;

            // Sauvegarde
            Session::put('cart', $cart);

            return back()->with('success', 'Quantité mise à jour avec succès.');
        }
    }

    // Supprimer un produit du panier
    public function remove(Product $product)
    {
        // Récupère le panier
        $cart = Session::get('cart', []);

        // Supprime le produit
        unset($cart[$product->id]);

        // Met à jour la session
        Session::put('cart', $cart);

        return back()->with('success', 'Produit retiré du panier.');
    }

    // Vider complètement le panier
    public function clear()
    {
        // Supprime la clé 'cart' de la session
        Session::forget('cart');

        return back()->with('success', 'Panier vidé.');
    }
}