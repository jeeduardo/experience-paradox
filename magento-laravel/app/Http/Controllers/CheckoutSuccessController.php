<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CheckoutSuccessController extends Controller
{
    public function __invoke()
    {
        /** @var \App\Models\Order $order */
        $orderId = session('order_id');
        if (empty($orderId)) {
            return redirect()->to('/catalog/gear')->with('error', 'Missing order ID.');
        }

        $data = ['vars' => $this->getSuccessDataVars($orderId)];

        // Remove cookies... 
        // @todo: update carts table, etc...
        $this->expireCheckoutCookies();

        // @todo: beautification of checkout success page...
        return view('checkout.success', $data);

    }

    private function getSuccessDataVars($orderId)
    {
        $order = Order::findOrFail($orderId);
        return [
            'categoryMenus' => Category::getCategoryMenuTree(),
            'order' => $order,
            'fullname' => $order->getFullName(),
            
        ];
    }

    private function expireCheckoutCookies()
    {
        Cookie::expire('quote_id');
        Cookie::expire('cart-id');
        Cookie::expire('cart-main-id');

    }
}
