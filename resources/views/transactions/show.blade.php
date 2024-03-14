@extends('layouts.app')

@section('content')
<section>
    Transaction : {{ $transaction->id }} <br>
    Customer name : {{ $transaction->customer ? $transaction->customer->name : 'Guest' }}
    <h4 class="fw-bold mt-2">Total Product :</h4>
    <table class="table">
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
            @foreach ($transactions as $transaction)
            <tr>
                <th scope="row">{{ $transaction->id }}</th>
                <td>{{ $transaction->customer->name }}</td>
                <td>{{ $transaction->total_price }}</td>
                <td>{{ $transaction->profit }}</td>
                <td>{{ $transaction->user->name }}</td>
                <td>{{ $transaction->status }}</td>
                <td class="d-flex gap-2">
                    <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST">
                        @method('DELETE')
                        @csrf

                        <button class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <form action="">

    </form>
</section>
@endsection