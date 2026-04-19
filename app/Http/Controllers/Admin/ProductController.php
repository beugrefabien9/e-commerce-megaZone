<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function dashboard()
    {
        // Page d'accueil de l'administration
        $totalProducts = Product::count();
        $totalOrders = \App\Models\Order::count();
        $totalUsers = \App\Models\User::count();
        $recentOrders = \App\Models\Order::with('user')->latest()->take(5)->get();

        return view('admin.store', compact('totalProducts', 'totalOrders', 'totalUsers', 'recentOrders'));
    }

    public function index()
    {
        $products = Product::with(['category', 'subCategory', 'primaryImage'])->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $subCategories = SubCategory::where('is_active', true)->get();
        return view('admin.products.create', compact('categories', 'subCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|url',
        ]);

        $data = $request->except(['images', 'image_url', 'is_active', 'is_featured']);
        $data['slug'] = Str::slug($request->name);
        $data['sku'] = 'SKU-' . strtoupper(Str::random(8));
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        $product = Product::create($data);

        // Handle image URL if provided
        if ($request->filled('image_url')) {
            $this->handleImageUrl($product, $request->image_url);
        }
        
        // Handle image uploads if provided
        if ($request->hasFile('images')) {
            $this->handleImageUploads($product, $request->file('images'));
        }

        return redirect()->route('admin.products.index')->with('success', 'Produit créé avec succès.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'subCategory', 'images', 'orderItems']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $subCategories = SubCategory::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories', 'subCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|url',
        ]);

        $data = $request->except(['images', 'image_url', 'is_active', 'is_featured']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        $product->update($data);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $this->handleImageUploads($product, $request->file('images'));
        }

        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Product $product)
    {
        // Delete associated images
        foreach ($product->images as $image) {
            // Only delete local files, not external URLs
            if (!str_starts_with($image->image_path, 'http://') && !str_starts_with($image->image_path, 'https://')) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé avec succès.');
    }

    private function handleImageUploads(Product $product, array $images)
    {
        $sortOrder = $product->images()->max('sort_order') ?? 0;
        $isFirstImage = $product->images()->count() === 0;

        foreach ($images as $image) {
            $path = $image->store('products', 'public');

            $product->images()->create([
                'image_path' => $path,
                'alt_text' => $product->name,
                'is_primary' => $isFirstImage,
                'sort_order' => ++$sortOrder,
            ]);
            
            $isFirstImage = false;
        }
    }

    private function handleImageUrl(Product $product, string $url)
    {
        $isFirstImage = $product->images()->count() === 0;
        $sortOrder = $product->images()->max('sort_order') ?? 0;

        $product->images()->create([
            'image_path' => $url,
            'alt_text' => $product->name,
            'is_primary' => $isFirstImage,
            'sort_order' => ++$sortOrder,
        ]);
    }
}
