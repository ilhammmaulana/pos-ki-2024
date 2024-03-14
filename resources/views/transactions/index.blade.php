@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Transaction</h1>
    <form action="{{ route('transactions.store') }}" method="POST">
        @csrf
        @method('POST')
        <div class="form-group mb-3 col-4">
            <label for="customer_id">Customer :</label>
            <select name="customer_id" id="customer_id" class="form-control">
                <option value="guest">Guest (Tamu)</option>
                @foreach ($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create transaction</button>
    </form>

    @if(Session::get('success'))
    <p class="text-success text-bold`">
        {{ Session::get('success') }}
    </p>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Transaction Id</th>
                <th scope="col">Customer</th>
                <th scope="col">Total Price</th>
                <th scope="col">Profit</th>
                <th scope="col">Customer Money</th>
                <th scope="col">Return Money</th>
                <th scope="col">Created By</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
            <tr>
                <th scope="row">{{ $transaction->id }}</th>
                <td>{{ $transaction->customer ? $transaction->customer->name : '(Guest)' }}</td>
                <td>{{ format_rupiah($transaction->total_price) }}</td>
                <td>{{ format_rupiah($transaction->profit) }}</td>
                <td>{{ format_rupiah($transaction->customer_money) }}</td>
                <td>{{ format_rupiah($transaction->return_money) }}</td>
                <td>{{ $transaction->user->name }}</td>
                <td>{{ $transaction->status }}</td>
                <td class="d-flex gap-2">
                    @if($transaction->status !== 'done')
                    <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST">
                        @method('DELETE')
                        @csrf

                        <button class="btn btn-danger">Delete</button>
                    </form>
                    @else
                    <p class="text-success">Transaction Done</p>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <ul class="pagination">
        @if($transactions->currentPage() > 1)
        <li class="page-item">
            <a class="page-link" href="{{ url('transactions?page='.($transactions->currentPage() - 1)) }}"
                tabindex="-1">Previous</a>
        </li>
        @else
        <li class="page-item disabled">
            <span class="page-link">Previous</span>
        </li>
        @endif

        @for($i = 0; $i < $transactions->lastPage(); $i++)
            @if($transactions->currentPage() === $i + 1)
            <li class="page-item active">
                <a class="page-link">{{ $i + 1 }}</a>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ url('transactions?page='.($i + 1)) }}">{{ $i + 1 }}</a>
            </li>
            @endif
            @endfor

            @if($transactions->currentPage() < $transactions->lastPage())
                <li class="page-item">
                    <a class="page-link"
                        href="{{ url('transactions?page='.($transactions->currentPage() + 1)) }}">Next</a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link">Next</span>
                </li>
                @endif
    </ul>
</div>
@endsection