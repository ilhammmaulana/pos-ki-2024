@extends('layouts.app')

@section('content')
<section>
    <form action="{{ route('category-products.store') }}" method="POST">
        @csrf
        @method('POST')
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Enter category name">
            @error('name')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary mt-3">Create</button>
    </form>
</section>
@endsection