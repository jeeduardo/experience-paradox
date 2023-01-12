<?php

namespace App\Services\Api;


use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductService
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct()
    {
        $this->logger = Log::channel('product_sync'); // ->info('Test log -- '.__METHOD__);
    }

    public function processJsonResponses($jsonResponses)
    {
        $products = Product::getProductsIndexedByMagentoProductId();
        foreach ($jsonResponses as $jsonResponse) {
            $items = $jsonResponse['items'];

            foreach ($items as $item) {
                // iterate and save?
                $action =  "added";
                if (isset($products[$item['id']])) {
                    // update
                    $product = $products[$item['id']];
                    $action = "updated";
                } else {
                    // create new instance
                    $product = new Product();
                }
                $product = $this->setColumnsFromCustomAttributes($product, $item['custom_attributes']);
                $product = $this->setProductData($product, $item);
                $product->save();
                $this->logger->info("Product with SKU ".$product->sku." $action...");
            }
        }
        return false;
    }

    protected function setColumnsFromCustomAttributes($product, $customAttributes)
    {
        $columns = ['description', 'category_ids', 'price', 'special_price'];
        $count = count($customAttributes);
        $categoryIdsColumnIndex = -1;

        foreach ($columns as $column) {
            $columnIndexInCustomAttributes = -1;
            for ($i = $count-1; $i >= 0; $i--) {
                $customAttribute = $customAttributes[$i];
                if ($customAttribute['attribute_code'] == $column) {
                    $columnIndexInCustomAttributes = $i;
                    break;
                }
            }

            if ($columnIndexInCustomAttributes != -1) {
                if ($column == 'category_ids') {
                    $categoryIdsColumnIndex = $columnIndexInCustomAttributes;
                } elseif($column == 'special_price') {
                    $this->logger->info('FOUND SPECIAL PRICE FOR '.$product->sku.' :: ');
                    $this->logger->info($customAttributes[$columnIndexInCustomAttributes]['value']);
                    $product->price = $customAttributes[$columnIndexInCustomAttributes]['value'];
                    $this->logger->info($product->price);
                } else {
                    $product->$column = $customAttributes[$columnIndexInCustomAttributes]['value'];
                }
            }
        }
//        if ($categoryIdsColumnIndex != -1) {
//            print_r([
//                $customAttributes[$categoryIdsColumnIndex]['value']
//            ]);
//        }
        return $product;
    }

    protected function setProductData($product, $apiData)
    {
        $columns = [
            'sku',
            'name',
            'attribute_set_id',
            'price',
            'status',
            'visibility',
            'type_id'
        ];
        $product->magento_product_id = $apiData['id'];

        foreach ($columns as $column) {
            if (isset($apiData[$column])) {
                if ($column == 'price' && !empty($product->price)) {
                    $this->logger->info('SKIP PRICE FOR '.$product->sku.' :: price = '.$product->price);
                    continue;
                }
                $product->$column = $apiData[$column];
            }
        }

        $product->media_gallery_entries = json_encode($apiData['media_gallery_entries']);

        return $product;
    }

}