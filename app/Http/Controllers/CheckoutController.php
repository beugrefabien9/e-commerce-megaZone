<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * Constructeur
     * → Applique le middleware 'auth' : l'utilisateur doit être connecté
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Affiche la page de paiement (checkout)
    public function index()
    {
        // Récupère le panier depuis la session
        $cart = Session::get('cart', []);

        // Si panier vide → redirection
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $cartItems = [];
        $total = 0;

        // Vérifie chaque produit du panier
        foreach ($cart as $productId => $item) {

            // Récupère le produit en base
            $product = Product::find($productId);

            // Vérifie disponibilité + stock suffisant
            if (
                $product &&
                $product->is_active &&
                $product->isInStock() &&
                $item['quantity'] <= $product->stock_quantity
            ) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->current_price,
                    'total' => $product->current_price * $item['quantity']
                ];

                // Calcul du total
                $total += $product->current_price * $item['quantity'];
            }
        }

        // Si aucun produit valide → retour panier
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Certains produits ne sont plus disponibles.');
        }

        // Affiche la vue checkout
        return view('store.checkout', compact('cartItems', 'total'));
    }

    // Enregistre la commande
    public function store(Request $request)
    {
        // Double sécurité : vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour passer une commande.');
        }

        // Validation des données du formulaire
        $request->validate([
            'shipping_city' => 'required|string|max:255',
            'shipping_quarter' => 'required|string|max:255',
            'shipping_details' => 'nullable|string|max:500',
            'billing_phone' => 'required|string|max:20',
            'billing_name' => 'required|string|max:255',
            'payment_method' => 'required|in:card,bank_transfer,paypal,cash_on_delivery',
        ]);

        // Récupère le panier
        $cart = Session::get('cart', []);

        // Si panier vide → retour
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        // Début transaction (important pour éviter erreurs de données)
        DB::beginTransaction();

        try {
            $cartItems = [];
            $subtotal = 0;

            // Vérifie et prépare les produits
            foreach ($cart as $productId => $item) {

                $product = Product::find($productId);

                // Si problème → annule tout
                if (
                    !$product ||
                    !$product->is_active ||
                    !$product->isInStock() ||
                    $item['quantity'] > $product->stock_quantity
                ) {
                    throw new \Exception(
                        'Produit non disponible: ' . ($product ? $product->name : 'ID: ' . $productId)
                    );
                }

                $price = $product->current_price;
                $total = $price * $item['quantity'];

                // Ajoute au tableau
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'total' => $total
                ];

                $subtotal += $total;

                // 🔥 Diminue le stock du produit
                $product->decrement('stock_quantity', $item['quantity']);
            }

            // Calculs financiers
            $taxAmount = $subtotal * 0.20; // TVA 20%
            $shippingAmount = $subtotal > 500000 ? 0 : 10000; // livraison gratuite si > 500k
            $totalAmount = $subtotal + $taxAmount + $shippingAmount;

            // Format adresse livraison
            $shippingAddress = $request->shipping_quarter . ', ' . $request->shipping_city;

            if ($request->shipping_details) {
                $shippingAddress .= ' - ' . $request->shipping_details;
            }

            // Notes (nom + téléphone)
            $orderNotes = 'Client: ' . $request->billing_name . ' | Tél: ' . $request->billing_phone;

            // Création de la commande
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'total_amount' => $totalAmount,
                'shipping_address' => $shippingAddress,
                'billing_address' => $request->billing_name,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'notes' => $orderNotes,
            ]);

            // Création des lignes de commande (OrderItems)
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                ]);
            }

            // Valide toutes les opérations
            DB::commit();

            // Vide le panier
            Session::forget('cart');

            // Redirection vers page de succès
            return redirect()
                ->route('checkout.success', $order)
                ->with('success', 'Commande créée avec succès!');

        } catch (\Exception $e) {

            // En cas d'erreur → annule tout
            DB::rollback();

            return back()->with(
                'error',
                'Erreur lors de la création de la commande: ' . $e->getMessage()
            );
        }
    }

    // Page de succès après commande
    public function success(Order $order)
    {
        // Sécurité : l'utilisateur ne peut voir que SES commandes
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            abort(403);
        }

        // Charge les relations (produits + utilisateur)
        $order->load(['orderItems.product', 'user']);

        // Affiche la page de confirmation
        return view('store.checkout-success', compact('order'));
    }
}