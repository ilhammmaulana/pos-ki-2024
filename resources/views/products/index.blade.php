@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Create product</a>
    @if(Session::get('success'))
    <p class="text-success text-bold`">
        {{ Session::get('success') }}
    </p>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Price Buy</th>
                <th scope="col">Price Sell</th>
                <th scope="col">Stock</th>
                <th scope="col">Category</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <th scope="row">{{ $product->id }}</th>
                <td>
                    <img width="200" src="{{ url($product->image) }}" alt="{{ $product->name }}">
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ format_rupiah($product->price_buy) }}</td>
                <td>{{ format_rupiah($product->price_sell) }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->category->name }}</td>
                <td class="d-flex gap-2">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                        @method('DELETE')
                        @csrf

                        <button class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
    <ul class="pagination">
        @if($products->currentPage() > 1)
        <li class="page-item">
            <a class="page-link" href="{{ url('products?page='.($products->currentPage() - 1)) }}"
                tabindex="-1">Previous</a>
        </li>
        @else
        <li class="page-item disabled">
            <span class="page-link">Previous</span>
        </li>
        @endif

        @for($i = 0; $i < $products->lastPage(); $i++)
            @if($products->currentPage() === $i + 1)
            <li class="page-item active">
                <a class="page-link">{{ $i + 1 }}</a>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ url('products?page='.($i + 1)) }}">{{ $i + 1 }}</a>
            </li>
            @endif
            @endfor

            @if($products->currentPage() < $products->lastPage())
                <li class="page-item">
                    <a class="page-link" href="{{ url('products?page='.($products->currentPage() + 1)) }}">Next</a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link">Next</span>
                </li>
                @endif
    </ul>
</div>
@endsection