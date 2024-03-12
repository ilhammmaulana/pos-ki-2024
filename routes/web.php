<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('customers', CustomerController::class)->names('customers');
    Route::resource('products', ProductController::class)->names('products');
    Route::resource('category-products', CategoryProductController::class)->names('category-products');
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('transactions.index')->middleware('checkCart');
        Route::middleware(['cart.guard'])->group(function () {
            Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
            Route::patch('/cart', [CartController::class, 'update'])->name('cart.update');
            Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
            Route::delete('/cart', [CartController::class, 'cancel'])->name('cart.cancel');
            Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

        });
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
        Route::post('/', [TransactionController::class, 'store'])->name('transactions.store');
        Route::patch('/{transaction}', [TransactionController::class, 'uupdate'])->name('transactions.update');
        Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    });

});

Route::fallback(function () {
    return to_route('login');
});