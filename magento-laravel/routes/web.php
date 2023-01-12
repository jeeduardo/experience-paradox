<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/catalog/{id}', [
    \App\Http\Controllers\CategoryController::class,
    'view'
]);

Route::get('/carts/{cartId}', [
    \App\Http\Controllers\CartController::class,
    'getCart'
])->name('cart.view');

Route::post('/cart/init', [
    \App\Http\Controllers\CartController::class,
    'init'
])->name('cart.init');

Route::post('/cart/{cartId}/items', [
    \App\Http\Controllers\CartController::class,
    'add'
])->name('cart.add');

Route::delete('/cartItems/{cartItemId}', [
    \App\Http\Controllers\CartController::class,
    'removeCartItem'
])->name('cart.item.delete');

Route::get('/checkout', [
    \App\Http\Controllers\CheckoutController::class,
    'checkout'
])->name('checkout');