@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Customers</h1>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Name</th>
                <th scope="col">Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
            <tr>
                <th scope="row">{{ $customer->id }}</th>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->phone }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>x`
</div>
@endsection