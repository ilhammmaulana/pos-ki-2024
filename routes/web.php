<?php

use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\CustomerController;
use App\Models\CategoryProduct;
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
    Route::resource('category-products', CategoryProductController::class)->names('category-products');

});

Route::fallback(function () {
    return to_route('login');
});