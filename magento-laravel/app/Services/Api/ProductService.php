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

    /**
     * ProductService constructor.
     */
    public function __construct()
    {
        $this->logger = Log::channel('product_sync');
    }

    /**
     * Process the responses we got from querying Magento about products
     *
     * @param $jsonResponses
     * @return bool
     */
    public function processJsonResponses($jsonResponses)
    {
        $products = Product::getProductsIndexedByMagentoProductId();
        foreach ($jsonResponses as $jsonResponse) {
            $items = $jsonResponse['items'];

            foreach ($items as $item) {
                // Default product instance and action
                $product = new Product();
                $action =  "added";
                // iterate and save
                if (isset($products[$item['id']])) {
                    // update
                    $product = $products[$item['id']];
                    $action = "updated";
                }
                $product = $this->setColumnsFromCustomAttributes($product, $item['custom_attributes']);
                $product = $this->setProductData($product, $item);
                $product->save();
                $this->logger->info("Product with SKU ".$product->sku." $action...");
            }
        }
        return false;
    }

    /**
     * Get data from Magento product's custom_attributes
     * To be used in storing product data to Laravel DB
     *
     * @param $product
     * @param $customAttributes
     * @return mixed
     */
    protected function setColumnsFromCustomAttributes($product, $customAttributes)
    {
        $columns = ['description', 'category_ids', 'price', 'special_price'];
        $count = count($customAttributes);
        $categoryIdsColumnIndex = -1;

        foreach ($columns as $column) {
            $indexInCustomAttributes = -1;
            for ($i = $count-1; $i >= 0; $i--) {
                $customAttribute = $customAttributes[$i];
                if ($customAttribute['attribute_code'] == $column) {
                    $indexInCustomAttributes = $i;
                    break;
                }
            }

            if ($indexInCustomAttributes != -1) {
                if ($column == 'category_ids') {
                    $categoryIdsColumnIndex = $indexInCustomAttributes;
                } elseif($column == 'special_price') {
                    $this->logger->info('Special price found for '.$product->sku.' :: ');
                    $this->logger->info($customAttributes[$indexInCustomAttributes]['value']);
                    $product->price = $customAttributes[$indexInCustomAttributes]['value'];
                } else {
                    $product->$column = $customAttributes[$indexInCustomAttributes]['value'];
                }
            }
        }

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