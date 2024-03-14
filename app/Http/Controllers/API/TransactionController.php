<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Traits\ResponseAPI;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Validator;

class TransactionController extends Controller
{
    use ResponseAPI;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with(['customer', 'details'])->latest()->get();
        return $this->requestSuccessData(TransactionResource::collection($transactions));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
        ]);
        if ($validator->fails()) {
            throw new HttpResponseException($this->requestValidation(formatErrorValidatioon($validator->errors()), 'Failed!'));
        }
        $input = $request->only('customer_id');
        if ($input['customer_id'] == 'guest') {
            $input['customer_id'] = null;
        }
        $input['created_by'] = auth()->user()->id;
        $transaction = Transaction::create($input);
        return $this->requestSuccessData(new TransactionResource($transaction), 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function clearCart(string $id)
    {
        try {
            $transaction = Transaction::where('id', $id)->firstOrFail();
            if ($transaction->status !== 'hold') {
                return $this->badRequest('transaction_done_or_cancel', 'Failed! Transaction status is done or cancel');
            }
            TransactionDetail::where('transaction_id', $transaction->id)->delete();
            return $this->requestSuccess();
        } catch (ModelNotFoundException $th) {
            return $this->requestNotFound('Transaction not found!');
        }
    }
    public function checkout(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'customer_money' => 'required',
        ]);
        if ($validator->fails()) {
            throw new HttpResponseException($this->requestValidation(formatErrorValidatioon($validator->errors()), 'Failed!'));
        }
        try {
            $input = $request->only('customer_money');
            $transaction = Transaction::findOrFail($id);
            $details = $transaction->details;
            $profit = 0;
            $total_price = 0;
            if ($transaction->status !== 'hold') {
                return $this->badRequest('transaction_done_or_cancel', 'Failed! Transaction status is done or cancel');
            }
            foreach ($details as $key => $detail) {

                $profit += ($detail->product->price_sell - $detail->product->price_sell) * $detail->qty;
                $stock = $detail->product->stock - $detail->qty;
                $detail->product->stock = $stock;
                Product::where('id', $detail->product->id)->update([
                    'stock' => $stock
                ]);
                $total_price += $detail->total_price;
            }
            if ($input['customer_money'] < $total_price) {
                return $this->badRequest('money_lacking', 'Failed! The customer money is lacking ');
            }

            $transaction->profit = $profit;
            $transaction->total_price = $total_price;
            $transaction->status = 'done';
            $transaction->customer_money = $input['customer_money'];
            $transaction->return_money = $input['customer_money'] - $total_price;

            $transaction->save();
            return $this->requestSuccessData(new TransactionResource($transaction));

        } catch (ModelNotFoundException $th) {
            return $this->requestNotFound('Transaction not found!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
