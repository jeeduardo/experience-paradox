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
}
