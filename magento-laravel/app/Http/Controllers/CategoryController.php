<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

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
        if (Cookie::get('quote_id')) {
            $quoteId = Cookie::get('quote_id');
            /** @var \App\Models\Cart $cart */
            $cart = Cart::where('magento_quote_id', $quoteId)->first();
            $cart->updateCart();
            $cart->save();
        }

        $data = [
            'vars' => [
                'cart' => $cart,
                'categoryMenus' => $categoryMenus,
                'category' => $category,
                'products' => $products,
            ]
        ];
        return view('category.view', $data);
    }
}