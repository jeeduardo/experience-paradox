<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use App\Models\Category;
use Illuminate\Support\Facades\Cookie;

class CheckoutController extends Controller
{
    public function checkout()
    {
        $cart = Cart::where('magento_quote_id', Cookie::get('quote_id'))->first();
        $categoryMenus = Category::getCategoryMenuTree();
        $cartItems = $cart->cartItems;
//        dd([
//            Cookie::get('cart-id'),
//            $cart,
//        ]);
        $data = [
            'vars' => [
                'categoryMenus' => $categoryMenus,
                'cart' => $cart,
            ]
        ];
        return view('checkout.checkout', $data);
    }
}