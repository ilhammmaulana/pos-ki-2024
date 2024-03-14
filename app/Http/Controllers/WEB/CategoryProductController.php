<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\CategoryProduct;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorProducts = CategoryProduct::latest()->paginate(10);
        return view('category-products.index', [
            'categories' => $categorProducts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category-products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:255',
        ]);
        $input = $request->only('name');
        CategoryProduct::create($input);
        return to_route('category-products.index')->with('success', 'Success create category product!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryProduct $categoryProduct)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoryProduct $categoryProduct)
    {
        return view('category-products.edit', [
            'categoryProduct' => $categoryProduct
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoryProduct $categoryProduct)
    {
        $request->validate([
            'name' => 'required|min:1|max:255',
        ]);
        $input = $request->only('name');
        $categoryProduct->update($input);
        return to_route('category-products.index')->with('success', 'Success update category product!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryProduct $categoryProduct)
    {
        $categoryProduct->delete();
        return to_route('category-products.index')->with('success', 'Success update category product!');
    }
}
