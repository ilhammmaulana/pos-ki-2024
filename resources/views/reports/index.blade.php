@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Report Transaction</h1>
    {{-- <a href="{{ route('category-products.create') }}" class="btn btn-primary">Create category</a> --}}
    @if(Session::get('success'))
    <p class="text-success text-bold">
        {{ Session::get('success') }}
    </p>
    @endif
    <div class="row">
        <div class="col-md-6">
            <h3 class="fw-bold">Total Transaction & profit xx</h3>
            <h6>Total transaction today : <span class="text-success">{{ $total_transaction_today }}</span> </h6>
            <h6>Total Transaction in {{ date('F') }} :<span class="text-success">{{
                    $total_transaction_month}}
                </span></h6>
            <h6>Profit today : <span class="text-success">{{ format_rupiah($profit_today) }}</span> </h6>
            <h6>Profit in {{ date('F') }} :<span class="text-success">{{
                    format_rupiah($profit_month) }}
                </span></h6>
        </div>
        <div class="col-md-6">
            <h3 class="fw-bold">Popular Products</h3>
            @foreach ($popular_products as $product)
            <div class="col-md-3 col-sm-6 col-12">
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="{{ url($product->image) }}" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <h6>{{ format_rupiah($product->price_sell) }}</h6>
                        <p>Stock : {{ $product->stock }}</p>
                        <h6 class="text-success fw-bold">Total terjual : {{ $product->transaction_details_count }}</h6>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection