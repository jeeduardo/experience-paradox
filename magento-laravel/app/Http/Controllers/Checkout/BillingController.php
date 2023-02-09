<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Models\CheckoutAddress;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function saveBillingAddress($cartToken, Request $request)
    {
        $data = $request->all();
        $checkoutAddress = CheckoutAddress::createOrUpdateAddress($data, CheckoutAddress::ADDRESS_TYPE_BILLING);

        $cart = $checkoutAddress->cart;
        // For populating cart JSON property "cartItems"...
        $items = $cart->cartItems;
        if ($checkoutAddress->id) {
            return response()->json(['checkout_address' => $checkoutAddress]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Please check logs for details.',
        ]);
    }

    public function placeOrder($cartToken, Request $request)
    {
        $data = $request->all();
/*
{
    "email": "guest@jesadiya.com",
    "paymentMethod": {
        "method": "cashondelivery"
    },
    "billing_address": {
        "email": "rakesh@wearejh.com",
        "region": "County Kerry",
        "region_id": 0,
        "region_code": "",
        "country_id": "IE",
        "street": [
            "123 Dublin street"
        ],
        "postcode": "W34 X4Y5",
        "city": "Dublin",
        "telephone": "441XXXXX44",
        "firstname": "Janes Guest",
        "lastname": "Does Guest"
    }
}
 */
        // return order ID and its details
    }
}
