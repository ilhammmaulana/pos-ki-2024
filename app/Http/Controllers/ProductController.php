<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category'])->latest()->paginate(10);
        return view('products.index', [
            'products' => $products,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CategoryProduct::latest()->get();
        return view('products.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:4|max:255',
            'price_buy' => 'required',
            'price_sell' => 'required',
            'stock' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'category_product_id' => 'required'
        ]);
        $input = $request->only('name', 'price_buy', 'price_sell', 'stock', 'category_product_id');
        $image = $request->file('image');
        $path = $image->store('public/products');
        $input['image'] = $path;
        Product::create($input);
        return to_route('products.index')->with('success', 'Success create product!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|min:4|max:255',
            'phone' => 'required',
        ]);
        $input = $request->only('name', 'phone');
        $product->update($input);
        return to_route('products.index')->with('success', 'Success update product!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return to_route('products.index')->with('success', 'Success delete product!');

    }
}
