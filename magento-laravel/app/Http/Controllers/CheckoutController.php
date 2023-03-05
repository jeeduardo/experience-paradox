<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use App\Models\CartToOrderApi;
use App\Models\Category;
use App\Models\CheckoutAddress;
use App\Models\CheckoutShippingRate;
use App\Models\DirectoryCountryRegion;
use App\Models\Order;
use App\Services\Api\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use function Termwind\ValueObjects\p;

class CheckoutController extends Controller
{
    public function checkout()
    {
        /** @var \App\Models\Cart $cart */
        $cart = Cart::where('magento_quote_id', Cookie::get('quote_id'))->first();

        $redirectPath = '/catalog/gear';
        if (!$cart) {
            return redirect()->to($redirectPath)->with('error', 'Your cart is empty. Cannot checkout.');
        }

        // If we found an erring cart item, return with error message.
        $hasErringCartItem = $cart->hasErringCartItem();
        if ($hasErringCartItem instanceof \App\Models\CartItem) {
            $message = $hasErringCartItem->getErrorMessageForItem();

            return redirect()
                ->to($redirectPath)
                ->with('error', 'Cannot checkout'.$message);
        }

        $categoryMenus = Category::getCategoryMenuTree();

        // reformat total_segments for proper display
        $cart->total_segments = $cart->getTotalSegments();
        // Needed to do this so that cart JS object will have a non-empty "cartItems" property
        $cartItems = $cart->cartItems;

        $addresses = CheckoutAddress::getAddresses($cart->id);
        // @todo: validate the addresses!
        $hasAddressValidationErrors = CheckoutAddress::validateAddresses($addresses);
        $hasShippingAddressValidationError = $hasAddressValidationErrors['shipping'];
        $hasBillingAddressValidationError = $hasAddressValidationErrors['billing'];

        $shippingMethods = [];
        // if ($shippingAddress->id) will have an error if it's a boolean/null
        // Hence, I'm using empty...
        if (!empty($addresses['shipping']->id)) {
            $shippingMethods = CheckoutShippingRate::getShippingRatesForAddress($addresses['shipping']->id);
        }
        $data = [
            'vars' => [
                'categoryMenus' => $categoryMenus,
                'cart' => $cart,
                'addresses' => $addresses,
                'hasShippingAddressValidationError' => $hasShippingAddressValidationError,
                'hasBillingAddressValidationError' => $hasBillingAddressValidationError,
                'shippingMethods' => $shippingMethods,
                'regions' => DirectoryCountryRegion::getRegions('CA'),
            ]
        ];
        return view('checkout.checkout', $data);
    }

    public function placeOrder(
        $cartToken,
        Request $request,
        OrderService $orderService
    ) {
        // Get payload
        $data = $request->all();
        $payload = [
            'email' => $data['order']['email'],
            'paymentMethod' => $data['order']['paymentMethod'],
            'billing_address' => $data['order']['billing_address'],
        ];

        // Call custom Magento placeOrder API
        // /V1/guest-carts/:cartId/payment-information-order
        $result = $orderService->placeOrder($cartToken, $payload);

        // json array for return response()->json...
        $json = ['status' => $result['status']];

        if (empty($result['successful'])) {
            // Non 20x HTTP return status...
            $response = $result['body'];
            $order = false;
            $json['message'] = 'Order creation failed. Please contact administrator for assistance.';
            return response()->json($json);
        }

        // Save record to order table
        $orderData = Order::prepareOrderData($result['json']);
        
        /** @var \App\Models\Order $order */
        $order = Order::create($orderData);
        // Return immediately if there's no order ID
        if (empty($order->id)) {
            return response()->json($json);
        }
        // while top-level order data is saved, let's save the order_items data in the background
        $cartId = Cookie::get('cart-main-id');
        $cart = Cart::findOrFail($cartId);
        dispatch(new \App\Jobs\AddOrderItemsJob($order, $result['json'], $cart));

        $json['order_id'] = $result['json']['entity_id'];
        $json['increment_id'] = $result['json']['increment_id'];
        // Cookie::queue('order_id', $json['increment_id']);
        session(['order_id' => $order->id]);

        $response = $result['json'];
        // Get results, store to cart_to_order_api
        $cartToOrderApiData = [
            'cart_id' => $data['cartId'],
            'endpoint' => $result['endpoint'],
            'http_return_status' => $result['status'],
            'order_creation_api_response' => (is_array($response)) ? \json_encode($response) : $response,
        ];
        $cartToOrderApiData['order_id'] = $order->id;
        $order->afterSave($data['cartId']);

        $cartToOrderApi = CartToOrderApi::create($cartToOrderApiData);

        return response()->json($json);
    }
}
