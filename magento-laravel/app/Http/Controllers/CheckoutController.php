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
        $cart = Cart::where('magento_quote_id', Cookie::get('quote_id'))->first();

        $redirectPath = '/catalog/1';
        if (!$cart) {
            return redirect()->to($redirectPath)->with('error', 'Your cart is empty. Cannot checkout.');
        }

        $categoryMenus = Category::getCategoryMenuTree();

        // reformat total_segments for proper display
        $cart->total_segments = $cart->getTotalSegments();
        // Needed to do this so that cart JS object will have a non-empty "cartItems" property
        $cartItems = $cart->cartItems;

        $addresses = CheckoutAddress::getAddresses($cart->id);

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

        if (!$result['successful']) {
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

        $json['order_id'] = $result['json']['entity_id'];
        $json['increment_id'] = $result['json']['increment_id'];
        Cookie::queue('order_id', $json['increment_id']);

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

    public function success()
    {
        $cartId = Cookie::get('cart-main-id');
        $cart = Cart::findOrFail($cartId);
        /** @var \App\Models\Order $order */
        $order = Order::findOrFail($cart->order_id);

        $data = [
            'vars' => [],
            'order' => $order,
            // while increment_id is not yet in our DB
            'orderIncrementId' => $order->increment_id,
            'fullname' => $order->getFullName(),
        ];

        // Remove cookies... update carts table, etc...
        Cookie::expire('order_id');
        Cookie::expire('quote_id');
        Cookie::expire('cart-id');
        Cookie::expire('cart-main-id');

        // @todo: beautification of checkout success page...
        return view('checkout.success', $data);

    }
}
