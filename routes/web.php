<?php

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
    Route::resource('transactions', TransactionController::class)->names('transactions');
    Route::prefix('transactions')->group(function () {
        Route::get('cart', [TransactionController::class, 'cart']);
    });
});

Route::fallback(function () {
    return to_route('login');
});