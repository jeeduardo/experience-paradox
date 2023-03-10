<?php

use App\Http\Controllers\Api\CategoryViewController;
use App\Http\Controllers\Api\Customer\CartController;
use App\Http\Controllers\Api\UserLoginController;
use App\Http\Controllers\Api\UserRegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('categories/{id}', CategoryViewController::class)->name('category.view');

Route::middleware('auth:api')->post('/users', UserRegistrationController::class)->name('user.add');

Route::middleware('auth:api')->post('/login', UserLoginController::class)->name('user.login');

Route::middleware('auth:api')->post('/carts', CartController::class)->name('registered.cart');