@extends('layouts.app')

@section('content')
<section>
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        @method('POST')
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Enter name">
            @error('name')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter phone">
            @error('phone')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary mt-3">Create</button>
    </form>
</section>
@endsection