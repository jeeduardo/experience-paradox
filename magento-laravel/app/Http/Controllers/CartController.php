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
    /**
     * Get cart information based on given $cartId
     *
     * @param $cartId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCart($cartId)
    {
        $data = ['error' => 'No cart object.'];
        /** @var \App\Models\Cart $cart */
        $cart = Cart::findOrFail($cartId);
        if ($cart->items_qty == 0) {
            $cart->items_qty = '0';
        }
        // so that $cart will also have cart_items in JSON response
        $cartItems = $cart->cartItems;
        $data = ['cart' => $cart];
        return response()->json($data);
    }

    /**
     * Initialize a record in "carts" , dispatch job to initiate a Magento quote/cart session
     * @param Request $request
     * @param GuestCartService $guestCartService
     * @return \Illuminate\Http\JsonResponse
     */
    public function init(Request $request, GuestCartService $guestCartService)
    {
        if (Cookie::get('cart-id')) {
            $cartId = Cookie::get('cart-id');
            $cartToken = CartToken::where('cart_token', $cartId)->first();
            $cart = Cart::findOrFail(Cookie::get('cart-main-id'));
            $quoteId = $cart->magento_quote_id;
        } else {

            // Save a record to "carts" table
            // Dispatch a job to initialize a guest cart session in Magento side
            // Update the "carts" record
            // With the quote_id from magento and it's mask
            $cart = Cart::create([
                'remote_ip' => $request->ip(),
            ]);
            if ($cart->id) {
                Cookie::queue('cart-main-id', $cart->id);
                dispatch(new \App\Jobs\InitGuestCartJob($guestCartService, $cart));
            }
        }

        return response()->json([
            'cartMainId' => $cart->id,
            'quote_id' => ($quoteId) ?? ''
        ]);
    }

    /**
     * Check if we have a Magento quote session already
     *
     * GET /cart/{cartMainId}/poll_quote
     * @param $cartMainId
     * @return \Illuminate\Http\JsonResponse
     */
    public function pollQuote($cartMainId)
    {
        $cart = Cart::findOrFail($cartMainId);
        if ($cart->id && !empty($cart->magento_quote_id)) {
            Cookie::queue('quote_id', $cart->magento_quote_id);
            Cookie::queue('cart-id', $cart->cart_token);
            return response()->json([
                'success' => true,
                'quote_id' => $cart->magento_quote_id,
                'cart_token' => $cart->cart_token,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No cart data yet.',
        ]);
    }

    /**
     * Add an item to cart
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $cartId = Cookie::get('cart-main-id');
        /** @var \App\Models\Cart $cart */
        $cart = Cart::findOrFail($cartId);
        /** @var \App\Models\CartItem $cartItem */
        $cartItem = CartItem::createCartItemData($cart, $request, Cookie::get('quote_id'));
        if ($cartItem->id) {
            // Dispatch job to add cart item to Magento's quote_item table
            $payload = ['cartItem' => $request->post('cartItem')];
            $cartIdToken = Cookie::get('cart-id');
            dispatch(new \App\Jobs\AddToCartJob($payload, $cartIdToken, $cartItem));
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

    /**
     * Remove an item from cart
     *
     * @param $cartItemId
     * @return \Illuminate\Http\JsonResponse
     */
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
            dispatch(new \App\Jobs\RemoveFromCartJob(Cookie::get('cart-id'), $cartItem));
            return response()->json(['success' => true]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Cart item not found',
        ]);
    }
}