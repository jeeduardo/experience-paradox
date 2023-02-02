<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function view($urlPath, Request $request)
    {
        // @todo: we will find by url_path eventually
        $category = Category::findOrFail($urlPath);
        $visibility = 4;
        $products = Product::where('visibility', $visibility)->get()->all();

        $categoryMenus = Category::getCategoryMenuTree();

        $cart = false;
        if (Cookie::get('cart-main-id')) {
            /** @var \App\Models\Cart $cart */
            $cart = Cart::findOrFail(Cookie::get('cart-main-id'));
            $cart->updateCart();
            $cart->save();
        }

        // Variables to be used for rendering
        $data = [
            'vars' => [
                'cart' => $cart,
                'categoryMenus' => $categoryMenus,
                'category' => $category,
                'products' => $products,
                'errorMessages' => $this->getErrorMessages(),
            ]
        ];
        return view('category.view', $data);
    }

    /**
     * @return array
     */
    private function getErrorMessages()
    {
        $errorMessages = Session::get('error');

        if (empty($errorMessages)) {
            return [];
        }

        if (is_string($errorMessages)) {
            $errorMessages = [$errorMessages];
        }

        return $errorMessages;
    }
}
