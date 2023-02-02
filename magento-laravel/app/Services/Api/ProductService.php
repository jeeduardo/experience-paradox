<?php

namespace App\Services\Api;


use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductService
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Array of products we got from magento to be used
     * for saving/update later to category_products
     * keyed by the magento_product_id
     * @var array
     */
    private $magentoProducts;

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

        if (!empty($this->magentoProducts)) {
            echo "Syncing category-product mapping to category_products...\n";
            CategoryProduct::mapCategoryAndProducts($this->magentoProducts);
            return true;
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

            // Init product to magentProducts array
            $m2ProductId = $product->magento_product_id;
            if (empty($this->magentoProducts[$m2ProductId])) {
                $this->magentoProducts[$m2ProductId] = [
                    'product_id' => $product->id,
                    'magento_product_id' => $product->magento_product_id,
                ];
            }
            if ($indexInCustomAttributes != -1) {
                if ($column == 'category_ids') {
                    // echo "INDEX OF category_ids = ".$indexInCustomAttributes."\n";
                    // @todo: store this value under $this->products[$magentoProductId] array later...
                    // echo implode(',', $customAttributes[$indexInCustomAttributes]['value']);
                    // echo "\n";
                    $categoryIdsColumnIndex = $indexInCustomAttributes;
                    $this->magentoProducts[$m2ProductId]['category_ids']
                        = $customAttributes[$indexInCustomAttributes]['value'];
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