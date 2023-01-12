<?php
namespace App\Services\Api;

use App\Models\Category;
use function League\Flysystem\get;

class CategoryService
{
    public function processJson($json)
    {
        $category = $this->saveCategory([
            'magento_category_id' => $json['id'],
            'magento_parent_category_id' => $json['parent_id'],
            'name' => $json['name'],
            'is_active' => $json['is_active'],
            'position' => $json['position'],
            'level' => $json['level'],
            // change schema para NULLABLE UNG IBA KASI PANGIT UN API ni magento /categories...
        ]);

        $categoryIds = [];
        if ($category) {
            $categoryIds[] = $category->id;
        }
        if (!empty($json['children_data'])) {
            $categoryIds = $this->processCategories($json['children_data'], $categoryIds);
        }
        echo self::class . " :: categoryIds? \n";
        echo implode(',', $categoryIds) . "\n";
        return true;
    }

    protected function processCategories($json, $categoryIds)
    {
        // Process category data here except children_data?
        // process children_data
        foreach ($json as $element) {
            $category = $this->saveCategory([
                'magento_category_id' => $element['id'],
                'magento_parent_category_id' => $element['parent_id'],
                'name' => $element['name'],
                'is_active' => $element['is_active'],
                'position' => $element['position'],
                'level' => $element['level'],
            ]);
            if ($category) {
                $categoryIds[] = $category->id;
            }
//            $categoryIds = $categoryIds . str_repeat(' ', $element['level']) . $element['id'] . '--' . $element['parent_id'] . '--' . $element['name'] . "\n";
            if (!empty($element['children_data'])) {
                $categoryIds = $this->processCategories($element['children_data'], $categoryIds);
            }
        }
        return $categoryIds;
    }

    protected function saveCategory($categoryData)
    {
        $category = Category::where('magento_category_id', $categoryData['magento_category_id'])->first();
        if ($category) {
            foreach ($categoryData as $key => $value) {
                $category->$key = $value;
            }
            $category->save();
            echo "Category ".$category->name." updated. ID: ".$category->id."\n";
        } else {
            $category = Category::create($categoryData);
            echo "Category ".$category->name." CREATED. ID: ".$category->id."\n";
        }

        return ($category->id) ? $category : false;
    }

    public function processCategoryCustomAttributes($categoryCustomAttributes, $level)
    {
        $categories = Category::getCategoriesByLevel($level);

        // response looks like
        // '{
        //  "items":
        //      [{
        //          "id": 4,
        //          "parent_id":3,
        //          "name":"Bags",
        //          "et":"cetera",
        //          "custom_attributes": [{"attribute_code": "url_path", "value": "url/path"}]
        //  }'

        $items = $categoryCustomAttributes['items'];
        foreach ($items as $item) {
            if (isset($categories[$item['id']])) {
                $category = $categories[$item['id']];
                $customAttributeJson = [];
                foreach ($item['custom_attributes'] as $customAttribute) {
                    $customAttributeJson[$customAttribute['attribute_code']] = $customAttribute['value'];
                    if ($customAttribute['attribute_code'] == 'url_path') {
//                        $category->url_path = $customAttribute['value'];
                    }
                }
                $category->custom_attributes = $customAttributeJson;
                $category->save();
                echo "Category {$category->name} ({$category->id}) updated. \n";
            }
        }

        return false;
    }
}
