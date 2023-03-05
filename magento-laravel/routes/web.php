<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginActionController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\LogoutController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

// Admin level routes
Route::get('/', LoginController::class)->name('login');

Route::get('/login', LoginController::class)->name('login');

Route::post('/login', LoginActionController::class)->name('login.action');

Route::get('/admin/logout', LogoutController::class)->name('admin.logout');

Route::get('/admin/dashboard', DashboardController::class)->name('admin.dashboard')->middleware('admin');

// Frontend routes...
Route::get('/catalog', [
    \App\Http\Controllers\CategoryController::class,
    'invalidCategory'
])->name('invalid.category');

Route::get('/catalog/{urlPath?}', [
    \App\Http\Controllers\CategoryController::class,
    'view'
])->where('urlPath', '(.*)');

Route::get('/carts/{cartId}', [
    \App\Http\Controllers\CartController::class,
    'getCart'
])->name('cart.view');

Route::post('/cart/init', [
    \App\Http\Controllers\CartController::class,
    'init'
])->name('cart.init');

Route::post('/cart/items', [
    \App\Http\Controllers\CartController::class,
    'add'
])->name('cart.add');

Route::get('/cart/{cartMainId}/poll_quote', [
    \App\Http\Controllers\CartController::class,
    'pollQuote',
])->name('cart.poll_quote');

Route::delete('/cartItems/{cartItemId}', [
    \App\Http\Controllers\CartController::class,
    'removeCartItem'
])->name('cart.item.delete');

Route::get('/checkout', [
    \App\Http\Controllers\CheckoutController::class,
    'checkout'
])->name('checkout');

Route::post('/checkout/{cartToken}/shipping-address', [
    \App\Http\Controllers\Checkout\ShippingController::class,
    'saveShippingAddress'
])->name('checkout.save_shipping_address');

Route::post('/checkout/{cartToken}/billing-address', [
    \App\Http\Controllers\Checkout\BillingController::class,
    'saveBillingAddress'
])->name('checkout.save_billing_address');

Route::get('/checkout/address/{addressId}/shipping-methods', [
    \App\Http\Controllers\Checkout\ShippingController::class,
    'shippingMethods'
])->name('checkout.shipping_methods');


Route::post('/checkout/shipping-method', [
    \App\Http\Controllers\Checkout\ShippingController::class,
    'saveShippingMethod'
])->name('checkout.save_shipping_method');

Route::post('/checkout/{cartToken}/order', [
    App\Http\Controllers\CheckoutController::class,
    'placeOrder'
])->name('place.order');

Route::get('/checkout/success', \App\Http\Controllers\CheckoutSuccessController::class)->name('checkout.success');