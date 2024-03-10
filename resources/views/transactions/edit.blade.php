@extends('layouts.app')

@section('content')
<section>
    <img src="{{ url($product->image) }}" alt="{{ $product->name }}" width="200">
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="form-group mb-3">
            <label for="image">Image product</label>
            <input type="file" class="form-control" name="image" id="image" placeholder="Uploud image">
            @error('image')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="name">Name Product</label>
            <input type="text" class="form-control" value="{{old('name', $product->name)}}" name="name" id="name"
                placeholder="Enter name">
            @error('name')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="price_buy">Price buy</label>
            <input type="number" class="form-control" value="{{old('price_buy', $product->price_buy)}}" name="price_buy"
                id="price_buy" placeholder="Enter Price Buy">
            @error('price_buy')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="price_sell">Price sell</label>
            <input type="number" class="form-control" name="price_sell"
                value="{{old('price_sell', $product->price_sell)}}" id="price_sell" placeholder="Enter Price Sell">
            @error('price_sell')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="stock">Stock</label>
            <input type="number" class="form-control" name="stock" value="{{old('stock', $product->stock)}}" id="stock"
                placeholder="Enter Stock">
            @error('stock')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="category_product_id">Category</label>
            <select name="category_product_id" class="form-control" id="category_product_id">
                <option selected disabled>Pilih category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" @if($product->category_product_id == $category->id) selected @endif>
                    {{
                    $category->name }}</option>
                @endforeach
            </select>
            @error('category_product_id')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</section>
@endsection