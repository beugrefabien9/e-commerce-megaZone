<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subCategories = SubCategory::with('category')->paginate(15);
        return view('admin.sub-categories.index', compact('subCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.sub-categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'category_id', 'description']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');

        SubCategory::create($data);

        return redirect()->route('admin.sub-categories.index')->with('success', 'Sous-catégorie créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubCategory $subCategory)
    {
        return view('admin.sub-categories.show', compact('subCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subCategory)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.sub-categories.edit', compact('subCategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubCategory $subCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sub_categories,name,' . $subCategory->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'category_id', 'description']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');

        $subCategory->update($data);

        return redirect()->route('admin.sub-categories.index')->with('success', 'Sous-catégorie mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();

        return redirect()->route('admin.sub-categories.index')->with('success', 'Sous-catégorie supprimée avec succès.');
    }
}
