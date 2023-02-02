<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use App\Models\Category;
use App\Models\CheckoutAddress;
use App\Models\CheckoutShippingRate;
use Illuminate\Support\Facades\Cookie;
use function Termwind\ValueObjects\p;

class CheckoutController extends Controller
{
    public function checkout()
    {
        $cart = Cart::where('magento_quote_id', Cookie::get('quote_id'))->first();

        $redirectPath = '/catalog/1';
        if (!$cart) {
            return redirect()->to($redirectPath)->with('error', 'Your cart is empty. Cannot checkout.');
        }

        $categoryMenus = Category::getCategoryMenuTree();

        // Needed to do this so that cart JS object will have a non-empty "cartItems" property
        $cartItems = $cart->cartItems;

        $shippingAddress = CheckoutAddress::getShippingAddress($cart->id);
        $billingAddress = false;
        $addresses = [];
        if (!empty($shippingAddress)) {
            $addresses[] = $shippingAddress;
            if ($shippingAddress->same_as_billing) {
                $billingAddress = $shippingAddress;
            } else {
                $billingAddress = CheckoutAddress::getBillingAddress($cart->id);
            }
        }
        if (!empty($billingAddress)) {
            $addresses[] = $billingAddress;
        }

        $shippingMethods = [];

        // if ($shippingAddress->id) will have an error if it's a boolean/null
        // Hence, I'm using empty...
        if (!empty($shippingAddress->id)) {
            $shippingMethods = CheckoutShippingRate::getShippingRatesForAddress($shippingAddress->id);
        }
        $data = [
            'vars' => [
                'categoryMenus' => $categoryMenus,
                'cart' => $cart,
                'addresses' => $addresses,
                'shippingMethods' => $shippingMethods,
            ]
        ];
        return view('checkout.checkout', $data);
    }
}