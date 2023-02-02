<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    use HasFactory;

    protected $table = 'category_products';

    protected $fillable = [
        'product_id',
        'category_id',
        'magento_product_id',
        'magento_category_id',
        'position'
    ];

    /**
     * Method to add/update product-category mappings
     *
     * @param $products
     */
    public static function mapCategoryAndProducts($products)
    {
        // Our categories, keyed by magento_category_id
        $categories = Category::getCategoriesByMagentoCategoryId();
        foreach ($products as $m2ProductId => $data) {
            foreach ($data['category_ids'] as $m2CategoryId) {
                $ourCategory = $categories[$m2CategoryId];
                $categoryProduct = self::updateOrCreate([
                    'product_id' => $data['product_id'],
                    'category_id' => $ourCategory['category_id'],
                ], [
                    'magento_product_id' => $data['magento_product_id'],
                    'magento_category_id' => $m2CategoryId,
                ]);
                echo "Product ".$data['product_id']." with category ".$ourCategory['category_id'];
                echo " added/updated (category_product ID :: ".$categoryProduct->id.").\n";
            }
        }
    }
}
