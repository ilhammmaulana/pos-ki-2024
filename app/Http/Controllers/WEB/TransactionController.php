<?php


namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::latest()->paginate(10);
        $customers = Customer::orderBy('name', 'asc')->get();
        return view('transactions.index', [
            'transactions' => $transactions,
            'customers' => $customers
        ]);
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
        $request->validate([
            'customer_id' => "required"
        ]);
        $input = $request->only('customer_id');
        if ($input['customer_id'] == 'guest') {
            $input['customer_id'] = null;
        }
        $input['created_by'] = auth()->user()->id;
        $transaction = Transaction::create($input);
        Session::put('transaction', [
            'id' => $transaction->id,
            'customer' => $transaction->customer,
            'details' => []
        ]);
        return to_route('cart.index')->with('success', 'Success create transaction, Select product in transaction !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return view('transactions.show', [
            'transaction' => $transaction
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        if ($transaction->status === 'done' && $transaction->status === 'hold   ') {
            return to_route('transactions.index')->with('error', 'Transaction failed because status is done!');
        }
        $transaction->delete();
        return to_route('transactions.index')->with('success', 'Success delete transaction!');
    }
}
