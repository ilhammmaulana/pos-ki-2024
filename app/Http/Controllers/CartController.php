<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{

    public function index(Transaction $transaction)
    {
        $products = Product::latest()->get();
        $transaction = Session::get('transaction');
        return view('transactions.cart', [
            'products' => $products,
            'transaction' => $transaction
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'qty' => 'required|numeric'
        ]);
        $input = $request->only('product_id', 'qty');
        $transaction = Session::get('transaction');
        $product = Product::find($input['product_id']);
        $detail = [
            'product' => $product,
            'qty' => $input['qty'],
            'total_price' => $product->price_sell * (int) $input['qty'],
            'created_at' => now()->toDateTimeString()
        ];

        array_push($transaction['details'], $detail);
        Session::put('transaction', $transaction);
        // dd($transaction);
        return to_route('cart.index')->with('success', 'Success added product into cart!');

    }
    public function cancel()
    {
        $transaction = Session::get('transaction');
        Transaction::where('id', $transaction['id'])->update([
            'status' => 'cancel'
        ]);
        Session::forget('transaction');
        return to_route('transactions.index')->with('success', 'Success cancel transaction');
    }
    public function update(Request $request)
    {
        $request->validate([
            'index' => 'required',
            'qty' => 'required|numeric|min:0'
        ]);
        $input = $request->only('index', 'qty');
        $transaction = Session::get('transaction');
        $productId = $transaction['details'][(int) $input['index']]['product']->id;
        $product = Product::find($productId);
        if ($input['qty'] >= $product->stock) {
            return to_route('cart.index')->with('error', 'Failed because there was insufficient product stock!');
        }
        $transaction['details'][(int) $input['index']]['product'] = $product;
        $transaction['details'][(int) $input['index']]['qty'] = $input['qty'];
        $transaction['details'][(int) $input['index']]['total_price'] = $input['qty'] * $product->price_sell;
        Session::put('transaction', $transaction);
        return to_route('cart.index')->with('success', 'Success update qty!');
    }
    public function clear()
    {

        $transaction = Session::get('transaction');
        $transaction['details'] = [];
        Session::put($transaction);

        return to_route('cart.index');
    }
}