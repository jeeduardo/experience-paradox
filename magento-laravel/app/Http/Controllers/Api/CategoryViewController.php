<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryViewController extends Controller
{
    /**
     * API controller to get category data and its products
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($id)
    {
        $category = Category::findOrFail($id);
        $products = Product::getProductsByCategory($id);

        $json = ['category' => $category];
        $json['category']['products'] = $products;
        return response()->json($json);
    }
}
