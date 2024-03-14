<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionDetailResource;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Traits\ResponseAPI;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Validator;

class CartController extends Controller
{
    use ResponseAPI;

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'transaction_id' => 'required|exists:transactions,id',
                'qty' => 'numeric',
            ]);

            if ($validator->fails()) {
                throw new HttpResponseException($this->requestValidation(formatErrorValidatioon($validator->errors()), 'Failed!'));
            }
            $input = $request->only('product_id', 'transaction_id', 'qty');
            $transaction = Transaction::find($input['transaction_id']);
            if ($transaction->status == 'done' || $transaction->status == 'cancel') {
                return $this->badRequest('status_cancel_or_done', 'This transactioon allready done or cancel');
            }
            $input['qty'] = $input['qty'] ? $input['qty'] : 1;
            $product = Product::findOrFail($input['product_id']);
            $transactionDetail = TransactionDetail::where('transaction_id', $input['transaction_id'])->where('product_id', $product->id)->first();

            if ($transactionDetail) {
                $transactionDetail->qty = $transactionDetail->qty + $input['qty'];
                if ($transactionDetail->qty > $product->stock) {
                    return $this->badRequest('insufficient_stock', 'Failed! Insufficient product stock');
                }
                $transactionDetail->total_price = $product->price_sell * $transactionDetail->qty;
                $transactionDetail->save();
                return $this->requestSuccessData(new TransactionDetailResource($transactionDetail), 200, 'Success update qty! ');
            } else {
                if ($input['qty'] > $product->stock) {
                    return $this->badRequest('insufficient_stock', 'Failed! Insufficient product stock');
                }
                $input['total_price'] = $product->price_sell * $input['qty'];
                $detail = TransactionDetail::create($input);
                return $this->requestSuccessData(new TransactionDetailResource($detail), 201);

            }

        } catch (ModelNotFoundException $th) {
            return $this->requestNotFound('Product not found!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'qty' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new HttpResponseException($this->requestValidation(formatErrorValidatioon($validator->errors()), 'Failed!'));
        }
        $input = $request->only('qty');
        $transactionDetail = TransactionDetail::where('id', $id)->first();

        if (!$transactionDetail) {
            return $this->requestNotFound('Cart not found!');
        }
        if ($input['qty'] > $transactionDetail->product->stock) {
            return $this->badRequest('insufficient_stock', 'Failed! Insufficient product stock');
        }
        $transaction = Transaction::find($transactionDetail->transaction_id);
        if ($transaction->status == 'done' || $transaction->status == 'cancel') {
            return $this->badRequest('transaction_done_or_cancel', 'Failed! Transaction status is done or cancel');
        }
        $transactionDetail->qty = $input['qty'];
        $transactionDetail->save();

        return $this->requestSuccess();
    }
    public function destroy(string $id)
    {
        try {
            $transactionDetail = TransactionDetail::where('id', $id)->firstOrFail();
            if ($transactionDetail->transaction->status !== 'hold') {
                return $this->badRequest('transaction_done_or_cancel', 'Failed! Transaction status is done or cancel');
            }
            $transactionDetail->delete();
            return $this->requestSuccess();
        } catch (ModelNotFoundException $th) {
            return $this->requestNotFound('Transaction detail not found!');
        }

    }
}
