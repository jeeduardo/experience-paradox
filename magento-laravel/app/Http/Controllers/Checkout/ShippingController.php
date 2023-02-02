<?php

namespace App\Http\Controllers\Checkout;

use App\Models\CheckoutAddress;
use App\Models\CheckoutShippingRate;
use Illuminate\Http\Request;

class ShippingController extends \App\Http\Controllers\Controller
{
    public function saveShippingAddress($cartToken, Request $request)
    {
        $data = $request->all();
        $checkoutAddress = CheckoutAddress::createOrUpdateAddress($data, CheckoutAddress::ADDRESS_TYPE_SHIPPING);

        $cart = $checkoutAddress->cart;
        // For populating cart JSON property "cartItems"...
        $items = $cart->cartItems;
        if ($checkoutAddress->id) {
            // dispatch and return $checkoutAddress
            dispatch(new \App\Jobs\EstimateShippingMethodsJob(
                $cart,
                $cartToken,
                $checkoutAddress->id,
                ['address' => $data['address']]
            ));
            return response()->json(['checkout_address' => $checkoutAddress]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Please check logs for details.',
        ]);
    }

    /**
     * GET shipping methods for given address
     * @param $addressId
     * @return \Illuminate\Http\JsonResponse
     */
    public function shippingMethods($addressId)
    {
        if ($addressId) {
            return response()->json([
                'success' => true,
                'shippingMethods' => CheckoutShippingRate::getShippingRatesForAddress($addressId),
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Please check logs for details.',
        ]);
    }

    /**
     * Save shipping method selected
     * @param Request $request
     * @param \App\Services\Api\CartService $cartService
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveShippingMethod(
        Request $request,
        \App\Services\Api\CartService $cartService
    ) {
        $data = $request->all();
        if (!empty($data['address_id'])) {
            try {
                /** @var \App\Models\CheckoutAddress $checkoutAddress */
                $checkoutAddress = CheckoutAddress::findOrFail($data['address_id']);
                $checkoutAddress->shipping_method = $data['shipping_method'];
                $checkoutAddress->shipping_description = $data['shipping_description'];
                $checkoutAddress->save();

                // @todo: get billing address
                $response = $cartService->saveShippingMethod([
                    'cart' => $checkoutAddress->cart,
                    'shipping_address' => $checkoutAddress,
                    'billing_address' => $checkoutAddress,
                    'shipping_carrier_code' => $data['shipping_carrier_code'],
                ]);

                // @todo: Save payment methods available
                if ($response) {
                    // Update totals of cart based on Magento totals
                    /** @var \App\Models\Cart $cart */
                    $cart = $checkoutAddress->cart;
                    $cart->updateTotalsBasedOnMagento($response['totals']);
                    $cart->save();
                    // Update totals of shipping address also
                    $checkoutAddress->updateTotalsBasedOnMagento($response['totals']);
                    $checkoutAddress->save();

                    return response()->json([
                        'payment_methods' => $response['payment_methods'],
                        'totals' => $response['totals'],
                    ]);
                }
                return response()->json(['success' => false]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error occured.'
                ]);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Please contact administrator for investigation.',
        ]);
    }
}
