@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Category Products</h1>
    <a href="{{ route('category-products.create') }}" class="btn btn-primary">Create category</a>
    @if(Session::get('success'))
    <p class="text-success text-bold">
        {{ Session::get('success') }}
    </p>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
            <tr>
                <th scope="row">{{ $category->id }}</th>
                <td>{{ $category->name }}</td>
                <td class="d-flex gap-2">
                    <a href="{{ route('category-products.edit', $category->id) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('category-products.destroy', $category->id) }}" method="POST">
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
        @if($categories->currentPage() > 1)
        <li class="page-item">
            <a class="page-link" href="{{ url('categories?page='.($categories->currentPage() - 1)) }}"
                tabindex="-1">Previous</a>
        </li>
        @else
        <li class="page-item disabled">
            <span class="page-link">Previous</span>
        </li>
        @endif

        @for($i = 0; $i < $categories->lastPage(); $i++)
            @if($categories->currentPage() === $i + 1)
            <li class="page-item active">
                <a class="page-link">{{ $i + 1 }}</a>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ url('categories?page='.($i + 1)) }}">{{ $i + 1 }}</a>
            </li>
            @endif
            @endfor

            @if($categories->currentPage() < $categories->lastPage())
                <li class="page-item">
                    <a class="page-link" href="{{ url('categories?page='.($categories->currentPage() + 1)) }}">Next</a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link">Next</span>
                </li>
                @endif
    </ul>
</div>
@endsection