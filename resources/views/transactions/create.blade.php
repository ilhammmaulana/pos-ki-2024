@extends('layouts.app')

@section('content')
<section>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="form-group mb-3">
            <label for="image">Image product</label>
            <input type="file" class="form-control" name="image" id="image" placeholder="Uploud image">
            @error('image')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="name">Name Product</label>
            <input type="text" value="{{ old('name') }}" class="form-control" name="name" id="name"
                placeholder="Enter name">
            @error('name')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="price_buy">Price buy</label>
            <input type="text" value="{{ old('price_buy') }}" class="form-control" name="price_buy" id="price_buy"
                placeholder="Enter Price Buy">
            @error('price_buy')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="price_sell">Price sell</label>
            <input type="text" class="form-control" value="{{ old('price_sell') }}" name="price_sell" id="price_sell"
                placeholder="Enter Price Sell">
            @error('price_sell')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="stock">Stock</label>
            <input type="number" class="form-control" value="{{ old('stock') }}" name="stock" id="stock"
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
                <option value="{{ $category->id }}"> {{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_product_id')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary mt-3">Create</button>
    </form>
</section>
@endsection