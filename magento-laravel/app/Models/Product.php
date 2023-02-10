<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'short_description',
        'sku',
        'price',
        'type_id',
        'attribute_set_id',
        'magento_product_id',
        'media_gallery_entries'
    ];

    /**
     * Retrieve products according to specified $categoryId
     *
     * @param $categoryId
     * @return mixed
     */
    public static function getProductsByCategory($categoryId)
    {
        $visibility = 4;
        $products = Product::where('visibility', $visibility)
            ->where('category_products.category_id', $categoryId)
            ->join('category_products', 'products.id', 'category_products.product_id')
            ->get()
            ->all();

        return $products;
    }

    /**
     * Retrieve products indexed by the product ID from Magento
     * 
     * @return array
     */
    public static function getProductsIndexedByMagentoProductId()
    {
        $products = [];
        // indexed by the magento_product_id
        $data = self::all();

        foreach ($data as $product) {
            $products[$product->magento_product_id] = $product;
        }

        return $products;
    }
}
