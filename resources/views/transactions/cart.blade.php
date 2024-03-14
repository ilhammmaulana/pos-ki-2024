@extends('layouts.app')

@section('content')
<section>
    @if(Session::get('success'))
    <p class="text-success text-bold">
        {{ Session::get('success') }}
    </p>
    @endif
    @if(Session::get('error'))
    <p class="text-danger text-bold">
        {{ Session::get('error') }}
    </p>
    @endif
    @foreach ($errors as $error)
    <p class="text-danger text-bold">
        {{ $error }}
    </p>
    @endforeach
    <div class="row mb-1">
        <div class="col-md-6">

            Transaction : {{ $transaction['id'] }} <br>
            Customer name : {{ $transaction['customer'] ? $transaction['customer']->name : 'Guest' }}
            <h4 class="fw-bold mt-2">Total Product :</h4>
        </div>

        <div class="col-md-6 d-flex gap-2 ">
            <form action="{{ route('cart.checkout') }}" class="" method="POST">
                @csrf
                @method('POST')

                <div class="form-group mb-2">
                    <label for="customer_money">Customer money</label>
                    <input type="number" min="1" name="customer_money" id="customer_money" class="form-control">
                    @error('customer_money')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success">Checkout</button>
            </form>
            <form action="{{ route('cart.cancel') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Cancel the transactions</button>
            </form>
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Clear cart</button>
            </form>
        </div>
    </div>
    <table class="table mt-3">
        <thead>
            <tr>
                <th scope="col">Id Product</th>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Total Price</th>
                <th scope="col">Qty</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
            $total = 0;
            @endphp
            @foreach ($transaction['details'] as $key => $detail)
            <tr>
                @php
                $total += $detail['total_price'];
                @endphp
                <th scope="row">{{ $detail['product']->id }}</th>
                <td>
                    <img width="75" src="{{ url($detail['product']->image) }}" alt="{{ $detail['product']->name }}">
                </td>
                <td>{{ $detail['product']->name }}</td>
                <td>{{ format_rupiah($detail['total_price']) }}</td>
                <form action="{{ route('cart.update') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <td><input type="number" name="qty" class="form-control" value="{{ $detail['qty'] }}"></td>
                    <td class="d-flex gap-2">
                        <input type="hidden" name="index" value="{{ $key }}">
                        <button type="submit" class="btn btn-success">Update QTY</button>
                </form>
                <form action="{{ route('cart.deleteItem') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="index" value="{{ $key }}">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
                </td>

            </tr>
            @endforeach
            <tr>
                <th colspan="3">Total Price : </th>
                <td colspan="3">{{ format_rupiah($total) }}</td>

            </tr>
        </tbody>
    </table>
    <div class="row mt-4">
        @foreach ($products as $product)
        <div class="col-md-3 col-sm-6 col-12">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="{{ url($product->image) }}" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <h6>{{ format_rupiah($product->price_sell) }}</h6>
                    <p>Stock : {{ $product->stock }}</p>
                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="form-group mb-3">
                            <label for="qty-{{ $product->id }}" class="mb-2">Jumlah barang</label>
                            <input type="number" min="1" value="1" id="qty-{{ $product->id }}" class="form-control"
                                name="qty">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Tambahkan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</section>
@endsection