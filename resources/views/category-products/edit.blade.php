@extends('layouts.app')

@section('content')
<section>
    <form action="{{ route('category-products.update', $categoryProduct->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $categoryProduct->name }}" id="name"
                placeholder="Enter category name">
            @error('name')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Edit</button>
    </form>

</section>
@endsection