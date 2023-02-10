<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'magento_category_id',
        'magento_parent_category_id',
        'name',
        'is_active',
        'position',
        'level',
        'path',
        'include_in_menu',
        'custom_attributes'
    ];

    /**
     * Retrieve category data by url_path provided (e.g. the "gear/bags" in http://website/catalog/gear/bags)
     * Trigger a 404 if category was not found
     *
     * @param $urlPath
     * @return mixed
     * @throws ModelNotFoundException
     */
    public static function getByUrlPathOrFail($urlPath)
    {
        $category = self::where('url_path', $urlPath)->first();
        if (is_null($category)) {
            throw (new ModelNotFoundException)->setModel(self::class, $urlPath);
        }
        return $category;
    }

    /**
     * Get categories keyed by the Magento category ID
     *
     * @return array
     */
    public static function getCategoriesByMagentoCategoryId()
    {
        $rows = self::all();
        $categories = [];
        foreach ($rows as $category) {
            $categories[$category->magento_category_id] = [
                'category_id' => $category->id
            ];
        }
        return $categories;
    }

    /**
     * Get categories according to level specified
     *
     * @param $level
     * @return array
     */
    public static function getCategoriesByLevel($level)
    {
        $rows = self::where('level', $level)->get()->all();
        $categories = [];
        foreach ($rows as $category) {
            $categories[$category->magento_category_id] = $category;
        }
        return $categories;
    }

    /**
     * Build and return category menu tree for Menu Display
     *
     * @return array
     */
    public static function getCategoryMenuTree()
    {
        // get level 2 and 3
        $rows = self::where('level', 2)->orderBy('magento_category_id')->get()->all();

        $categories = [];
        foreach ($rows as $row) {
            $categoryCustomAttributes = json_decode($row->custom_attributes, true);
            $categories[$row->magento_category_id] = [
                'name' => $row->name,
                'url_path' => $categoryCustomAttributes['url_path'],
                'children' => []
            ];
        }

        $rows = self::where('level', 3)->get()->all();
        foreach ($rows as $row) {
            if (isset($categories[$row->magento_parent_category_id])) {
                $categoryCustomAttributes = json_decode($row->custom_attributes, true);
                $categories[$row->magento_parent_category_id]['children'][] = [
                    'name' => $row->name,
                    'url_path' => $categoryCustomAttributes['url_path'],
                ];
            }
        }

        return $categories;
    }
}
