<?php

namespace App\Http\Controllers\WEB;

use App\Exceptions\InsufficientCustomerMoneyException;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        if ($input['qty'] > $product->stock) {
            return to_route('cart.index')->with('error', 'Failed because there was insufficient product stock!');
        }

        $existingItemKey = null;
        foreach ($transaction['details'] as $key => $detail) {
            if ($detail['product']->id === (int) $input['product_id']) {
                $existingItemKey = $key;
                break;
            }
        }

        if ($existingItemKey !== null) {
            // Update existing item quantity and total price
            $transaction['details'][$existingItemKey]['qty'] += (int) $input['qty'];
            $transaction['details'][$existingItemKey]['total_price'] = $product->price_sell * $transaction['details'][$existingItemKey]['qty'];
        } else {
            // Add new item to details
            $detail = [
                'product' => $product,
                'qty' => $input['qty'],
                'total_price' => $product->price_sell * (int) $input['qty'],
                'created_at' => now()->toDateTimeString()
            ];
            array_push($transaction['details'], $detail);
        }

        Session::put('transaction', $transaction);

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
        if ($input['qty'] > $product->stock) {
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
        Session::put('transaction', $transaction);
        return to_route('cart.index');
    }
    public function deleteItem(Request $request)
    {
        $input = $request->only('index');
        $transaction = Session::get('transaction');
        unset($transaction['details'][$input['index']]);
        Session::put('transaction', $transaction);
        return to_route('cart.index');
    }
    public function checkout(Request $request)
    {
        $request->validate([
            'customer_money' => 'required|numeric'
        ]);
        $input = $request->only('customer_money');

        $transaction = Session::get('transaction');
        $total_price = 0;
        $profit = 0;
        try {
            DB::transaction(function () use ($transaction, $request, &$total_price, &$profit, &$input) {
                // Validate stock sufficiency for each product in the transaction
                foreach ($transaction['details'] as $key => $detail) {
                    $product = Product::find($detail['product']->id);
                    if ($detail['qty'] > $product->stock) {
                        DB::rollBack();
                        return back()->with('error', "Stock barang $product->name tidak mencukupi");
                    }

                    $product->stock = $product->stock - $detail['qty'];
                    $product->save();

                    $priceNow = $product->price_sell * $detail['qty'];
                    $profit += ($product->price_sell - $product->price_buy) * $detail['qty'];
                    $total_price += $priceNow;
                    TransactionDetail::create([
                        'product_id' => $product->id,
                        'transaction_id' => $transaction['id'],
                        'total_price' => $priceNow,
                        'qty' => $detail['qty']
                    ]);
                }
                if ((int) $input['customer_money'] < $total_price) {
                    throw new InsufficientCustomerMoneyException("Customer's money (" . format_rupiah($request['customer_money']) . ") is insufficient for the total price (" . format_rupiah($total_price) . ").");
                } else {
                    Transaction::where('id', $transaction['id'])->update([
                        'status' => 'done',
                        'total_price' => $total_price,
                        'profit' => $profit,
                        'customer_money' => (int) $input['customer_money'],
                        'return_money' => (int) $input['customer_money'] - $total_price,
                    ]);
                    Session::forget('transaction');
                }

            });

            return redirect()->route('transactions.index')->with('success', 'Transaction completed successfully!');
        } catch (InsufficientCustomerMoneyException $e) {
            DB::rollBack();
            return to_route('cart.index')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            throw $e;
        }
    }
}