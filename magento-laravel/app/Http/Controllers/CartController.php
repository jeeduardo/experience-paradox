<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use App\Models\CartApiCall;
use App\Models\CartItem;
use App\Models\CartToken;
use App\Services\Api\GuestCartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function getCart($cartId)
    {
        $data = ['error' => 'No cart object.'];
        /** @var \App\Models\Cart $cart */
        $cart = Cart::findOrFail($cartId);
        // so that $cart will also have cart_items in JSON response
        if ($cart->items_qty == 0) {
            $cart->items_qty = '0';
        }
        $cartItems = $cart->cartItems;
        $data = [
            'cart' => $cart
        ];
        return response()->json($data);
    }
    public function init(Request $request, GuestCartService $guestCartService)
    {
        $cartLifetime = env('CART_COOKIE_LIFETIME');

        if (Cookie::get('cart-id')) {
            $cartId = Cookie::get('cart-id');
            $cartToken = CartToken::where('cart_token', $cartId)->first();
            $cart = Cart::findOrFail($cartToken->cart_id);
            $quoteId = $cart->magento_quote_id;
        } else {
            $cartId = $guestCartService->requestGuestCart();
            Cookie::queue('cart-id', $cartId, $cartLifetime);
            // Create CartToken
            $cartToken = CartToken::create([
                'cart_token' => $cartId,
            ]);

            $quoteInfo = $guestCartService->getQuoteInfo($cartId);
            if ($quoteInfo['id']) {
                Cookie::queue('quote_id', $quoteInfo['id'], $cartLifetime);
                $cart = Cart::create([
                    'magento_quote_id' => $quoteInfo['id']
                ]);
                $quoteId = $quoteInfo['id'];
                if ($cart->id) {
                    $cartToken->cart_id = $cart->id;
                    $cartToken->save();
                }
            }
        }


        return response()->json([
            'cartId' => $cartId,
            'quote_id' => ($quoteId) ?? ''
        ]);
    }

    public function add($paramCartId, Request $request)
    {
        // add to cart, queue the Magento API call later...
        $cartToken = CartToken::findByCartToken($paramCartId);
        /** @var \App\Models\Cart $cart */
        $cart = Cart::findOrFail($cartToken->cart_id);
        $cartItem = CartItem::create($this->parseCartItemData($cart, $request));

        if ($cartItem->id) {
            // Dispatch job to add item to Magento DB
            $payload = [
                'cartItem' => $request->post('cartItem')
            ];
            dispatch(new \App\Jobs\AddToCartJob($payload, $paramCartId, $cartItem));
            $cart->updateCart();
            $cart->save();
            return response()->json([
                'success' => true,
                'cartItem' => $cartItem,
                'cart' => $cart,
            ]);

        }
        return response()->json([
            'success' => false
        ]);
    }

    public function removeCartItem($cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);

        if ($cartItem && Cookie::get('cart-id')) {
            $cartId = $cartItem->cart_id;
            /** @var \App\Models\Cart $cart */
            $cart = Cart::findOrFail($cartId);
            if (empty($cartItem->magento_quote_item_id)) {
                // Remove already if not synced to Magento
                $cartItem->delete();
                $cart->updateCart();
                $cart->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Unsynced item removed from cart.',
                    'cart' => $cart,
                ]);
            }
            dispatch(new \App\Jobs\RemoveFromCartJob(
                Cookie::get('cart-id'),
                $cartItem
            ));
            return response()->json(['success' => true]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Cart item not found',
        ]);
    }

    protected function parseCartItemData($cart, Request $request)
    {
        $paramCartItem = $request->post('cartItem');
        $paramProduct = $request->post('product');
        $rowTotal = (int)$paramCartItem['qty'] * $paramProduct['price'];

        return [
            'magento_quote_id' => $paramCartItem['quote_id'],
            'cart_id' => $cart->id,
            'sku' => $paramCartItem['sku'],
            'name' => $paramProduct['name'],
            'product_id' => $paramProduct['id'],
            'qty' => $paramCartItem['qty'],
            'price' => $paramProduct['price'],
            'base_price' => $paramProduct['price'],
            'row_total' => $rowTotal,
            'base_row_total' => $rowTotal,
        ];
    }
}