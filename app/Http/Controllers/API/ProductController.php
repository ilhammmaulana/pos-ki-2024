<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ResponseAPI;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Storage;
use Validator;

class ProductController extends ApiController
{

    use ResponseAPI;
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        // throw new HttpResponseException($this->requestValidation(formatErrorValidatioon($validator->errors()), 'Failed!'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = ProductResource::collection(Product::orderBy('id', 'desc')->latest()->get());
        return $this->requestSuccessData($products);

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4|max:255',
            'price_buy' => 'required',
            'price_sell' => 'required',
            'stock' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'category_product_id' => 'required|exists:category_products,id'
        ]);
        if ($validator->fails()) {
            throw new HttpResponseException($this->requestValidation(formatErrorValidatioon($validator->errors()), 'Failed!'));
        }
        $input = $request->only('name', 'price_buy', 'price_sell', 'stock', 'category_product_id');
        $image = $request->file('image');
        $path = $image->store('public/products');
        $input['image'] = $path;
        $product = Product::create($input);
        return $this->requestSuccessData(new ProductResource($product), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|min:4|max:255',
                'price_buy' => 'required',
                'price_sell' => 'required',
                'stock' => 'required|numeric',
                'image' => 'image|mimes:jpeg,jpg,png|max:2048',
                'category_product_id' => 'required|exists:category_products,id'
            ]);
            if ($validator->fails()) {
                throw new HttpResponseException($this->requestValidation(formatErrorValidatioon($validator->errors()), 'Failed!'));
            }
            $input = $request->only('name', 'price_buy', 'price_sell', 'stock', 'category_product_id');
            $image = $request->file('image');
            if ($image) {
                Storage::delete($product->image);
                $input['image'] = $image->store('public/products');
            }
            $product->update($input);
            return $this->requestSuccessData(new ProductResource($product), 200, 'Success!');
        } catch (ModelNotFoundException $th) {
            return $this->requestNotFound('Product not found!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return $this->requestSuccess('Success!');
        } catch (ModelNotFoundException $th) {

            return $this->requestNotFound('Product not found!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
