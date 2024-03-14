<?php


namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('created_at', '>=', Carbon::today())
            ->where('status', 'done')
            ->get();
        $transactionMonth = Transaction::whereMonth('created_at', '=', Carbon::now()->month)
            ->where('status', 'done')
            ->get();

        $total_transaction_today = count($transactions);
        $total_transaction_month = count($transactionMonth);

        $profit_today = 0;
        $profit_month = 0;
        $popularProducts = Product::withCount('transactionDetails')
            ->orderBy('transaction_details_count', 'desc')
            ->limit(10)
            ->get();
        foreach ($transactions as $key => $transaction) {
            $profit_today += $transaction->profit;
        }
        foreach ($transactionMonth as $key => $transaction) {
            $profit_month += $transaction->profit;
        }
        return view('reports.index', [
            'transactionToday' => $transactions,
            'transactionMonth' => $transactionMonth,
            'total_transaction_today' => $total_transaction_today,
            'total_transaction_month' => $total_transaction_month,
            'profit_today' => $profit_today,
            'profit_month' => $profit_month,
            'popular_products' => $popularProducts
        ]);
    }
}
