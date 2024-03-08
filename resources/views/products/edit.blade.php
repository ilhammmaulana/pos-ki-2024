@extends('layouts.app')

@section('content')
<section>
    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $customer->name }}" id="name"
                placeholder="Enter name">
            @error('name')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" name="phone" id="phone" value="{{ $customer->phone }}"
                placeholder="Enter phone">
            @error('phone')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary mt-3">Create</button>
    </form>
</section>
@endsection