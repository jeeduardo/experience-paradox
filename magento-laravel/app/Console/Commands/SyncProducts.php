<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\Api\ProductService;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync magento products to Laravel database';

    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(
        ProductService $productService
    ){
        parent::__construct();
        $this->productService = $productService;
        $this->logger = Log::channel('product_sync');
    }

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sync:products')->everyTwoMinutes();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // @todo: remove hardcode eventually
        $categoryIds = [4, 5];
        $jsonResponses = $this->getProductsForCategories($categoryIds);
        $this->productService->processJsonResponses($jsonResponses);
        $this->logger->info("================");
        return Command::SUCCESS;
    }

    protected function getProductsForCategories($categoryIds)
    {

        $apiBaseUrl = env('MAGENTO_API_BASE_URL');
        $token = env('MAGENTO_API_TOKEN');

        $jsonResponses = [];
        $pageSize = env('MAGENTO_SYNC_PAGE_SIZE', 100);
        $LIMIT = 7;

        for ($currentPage = 1; $currentPage <= $LIMIT; $currentPage++) {
            $this->logger->info("Fetching products (page $currentPage) ...");

            $response = Http::withToken($token)->get($apiBaseUrl . '/products', [
//                'searchCriteria[filterGroups][0][filters][0][field]' => 'category_id',
//                'searchCriteria[filterGroups][0][filters][0][value]' => $categoryId,
                'searchCriteria[pageSize]' => $pageSize,
                'searchCriteria[currentPage]' => $currentPage
            ]);
            // @todo: IF NO PRODUCTS - break the loop
            $jsonResponse = $response->json();
            if (!empty($jsonResponse)) {
                $jsonResponses[$currentPage] = $jsonResponse;
            }

            $this->logger->info("Page size: " . $jsonResponse['search_criteria']['page_size'] . "");
            $this->logger->info("Finished fetching products (page $currentPage) ");
        }

        if (!empty($jsonResponses)) {
            return $jsonResponses;
        }

        return false;
    }

    protected function processResponse()
    {
        $json = '
        {
    "id": 2,
    "parent_id": 1,
    "name": "Default Category",
    "is_active": true,
    "position": 1,
    "level": 1,
    "product_count": 1181,
    "children_data": [
        {
            "id": 36,
            "parent_id": 2,
            "name": "What\'s New",
            "is_active": true,
            "position": 1,
            "level": 2,
            "product_count": 0,
            "children_data": []
        },
        {
            "id": 18,
            "parent_id": 2,
            "name": "Women",
            "is_active": true,
            "position": 2,
            "level": 2,
            "product_count": 0,
            "children_data": [
                {
                    "id": 19,
                    "parent_id": 18,
                    "name": "Tops",
                    "is_active": true,
                    "position": 1,
                    "level": 3,
                    "product_count": 784,
                    "children_data": [
                        {
                            "id": 21,
                            "parent_id": 19,
                            "name": "Jackets",
                            "is_active": true,
                            "position": 1,
                            "level": 4,
                            "product_count": 186,
                            "children_data": []
                        },
                        {
                            "id": 22,
                            "parent_id": 19,
                            "name": "Hoodies & Sweatshirts",
                            "is_active": true,
                            "position": 2,
                            "level": 4,
                            "product_count": 182,
                            "children_data": []
                        },
                        {
                            "id": 23,
                            "parent_id": 19,
                            "name": "Tees",
                            "is_active": true,
                            "position": 3,
                            "level": 4,
                            "product_count": 192,
                            "children_data": []
                        },
                        {
                            "id": 24,
                            "parent_id": 19,
                            "name": "Bras & Tanks",
                            "is_active": true,
                            "position": 4,
                            "level": 4,
                            "product_count": 224,
                            "children_data": []
                        }
                    ]
                },
                {
                    "id": 20,
                    "parent_id": 18,
                    "name": "Bottoms",
                    "is_active": true,
                    "position": 2,
                    "level": 3,
                    "product_count": 228,
                    "children_data": [
                        {
                            "id": 25,
                            "parent_id": 20,
                            "name": "Pants",
                            "is_active": true,
                            "position": 1,
                            "level": 4,
                            "product_count": 91,
                            "children_data": []
                        },
                        {
                            "id": 26,
                            "parent_id": 20,
                            "name": "Shorts",
                            "is_active": true,
                            "position": 2,
                            "level": 4,
                            "product_count": 137,
                            "children_data": []
                        }
                    ]
                }
            ]
        },
        {
            "id": 9,
            "parent_id": 2,
            "name": "Men",
            "is_active": true,
            "position": 3,
            "level": 2,
            "product_count": 0,
            "children_data": [
                {
                    "id": 10,
                    "parent_id": 9,
                    "name": "Tops",
                    "is_active": true,
                    "position": 1,
                    "level": 3,
                    "product_count": 678,
                    "children_data": [
                        {
                            "id": 12,
                            "parent_id": 10,
                            "name": "Jackets",
                            "is_active": true,
                            "position": 1,
                            "level": 4,
                            "product_count": 176,
                            "children_data": []
                        },
                        {
                            "id": 13,
                            "parent_id": 10,
                            "name": "Hoodies & Sweatshirts",
                            "is_active": true,
                            "position": 2,
                            "level": 4,
                            "product_count": 208,
                            "children_data": []
                        },
                        {
                            "id": 14,
                            "parent_id": 10,
                            "name": "Tees",
                            "is_active": true,
                            "position": 3,
                            "level": 4,
                            "product_count": 192,
                            "children_data": []
                        },
                        {
                            "id": 15,
                            "parent_id": 10,
                            "name": "Tanks",
                            "is_active": true,
                            "position": 4,
                            "level": 4,
                            "product_count": 102,
                            "children_data": []
                        }
                    ]
                },
                {
                    "id": 11,
                    "parent_id": 9,
                    "name": "Bottoms",
                    "is_active": true,
                    "position": 2,
                    "level": 3,
                    "product_count": 304,
                    "children_data": [
                        {
                            "id": 16,
                            "parent_id": 11,
                            "name": "Pants",
                            "is_active": true,
                            "position": 1,
                            "level": 4,
                            "product_count": 156,
                            "children_data": []
                        },
                        {
                            "id": 17,
                            "parent_id": 11,
                            "name": "Shorts",
                            "is_active": true,
                            "position": 2,
                            "level": 4,
                            "product_count": 148,
                            "children_data": []
                        }
                    ]
                }
            ]
        },
        {
            "id": 3,
            "parent_id": 2,
            "name": "Gear",
            "is_active": true,
            "position": 4,
            "level": 2,
            "product_count": 45,
            "children_data": [
                {
                    "id": 4,
                    "parent_id": 3,
                    "name": "Bags",
                    "is_active": true,
                    "position": 1,
                    "level": 3,
                    "product_count": 14,
                    "children_data": []
                },
                {
                    "id": 5,
                    "parent_id": 3,
                    "name": "Fitness Equipment",
                    "is_active": true,
                    "position": 2,
                    "level": 3,
                    "product_count": 22,
                    "children_data": []
                },
                {
                    "id": 6,
                    "parent_id": 3,
                    "name": "Watches",
                    "is_active": true,
                    "position": 3,
                    "level": 3,
                    "product_count": 9,
                    "children_data": []
                }
            ]
        },
        {
            "id": 7,
            "parent_id": 2,
            "name": "Collections",
            "is_active": false,
            "position": 5,
            "level": 2,
            "product_count": 13,
            "children_data": [
                {
                    "id": 8,
                    "parent_id": 7,
                    "name": "New Luma Yoga Collection",
                    "is_active": true,
                    "position": 1,
                    "level": 3,
                    "product_count": 347,
                    "children_data": []
                },
                {
                    "id": 32,
                    "parent_id": 7,
                    "name": "Erin Recommends",
                    "is_active": true,
                    "position": 2,
                    "level": 3,
                    "product_count": 279,
                    "children_data": []
                },
                {
                    "id": 33,
                    "parent_id": 7,
                    "name": "Performance Fabrics",
                    "is_active": true,
                    "position": 3,
                    "level": 3,
                    "product_count": 310,
                    "children_data": []
                },
                {
                    "id": 34,
                    "parent_id": 7,
                    "name": "Eco Friendly",
                    "is_active": true,
                    "position": 4,
                    "level": 3,
                    "product_count": 247,
                    "children_data": []
                },
                {
                    "id": 37,
                    "parent_id": 7,
                    "name": "Performance Sportswear New",
                    "is_active": true,
                    "position": 5,
                    "level": 3,
                    "product_count": 0,
                    "children_data": []
                },
                {
                    "id": 38,
                    "parent_id": 7,
                    "name": "Eco Collection New",
                    "is_active": true,
                    "position": 6,
                    "level": 3,
                    "product_count": 0,
                    "children_data": []
                }
            ]
        },
        {
            "id": 35,
            "parent_id": 2,
            "name": "Sale",
            "is_active": true,
            "position": 6,
            "level": 2,
            "product_count": 0,
            "children_data": []
        },
        {
            "id": 27,
            "parent_id": 2,
            "name": "Promotions",
            "is_active": false,
            "position": 6,
            "level": 2,
            "product_count": 0,
            "children_data": [
                {
                    "id": 28,
                    "parent_id": 27,
                    "name": "Women Sale",
                    "is_active": true,
                    "position": 1,
                    "level": 3,
                    "product_count": 224,
                    "children_data": []
                },
                {
                    "id": 29,
                    "parent_id": 27,
                    "name": "Men Sale",
                    "is_active": true,
                    "position": 2,
                    "level": 3,
                    "product_count": 39,
                    "children_data": []
                },
                {
                    "id": 30,
                    "parent_id": 27,
                    "name": "Pants",
                    "is_active": true,
                    "position": 3,
                    "level": 3,
                    "product_count": 247,
                    "children_data": []
                },
                {
                    "id": 31,
                    "parent_id": 27,
                    "name": "Tees",
                    "is_active": true,
                    "position": 4,
                    "level": 3,
                    "product_count": 192,
                    "children_data": []
                }
            ]
        }
    ]
}
        ';

        $json = json_decode($json, true);

        $categoryTree = $json['id'] . '--' . $json['parent_id'] . '--' . $json['name'] . "\n";
        if (!empty($json['children_data'])) {
            $categoryTree = $this->someRecursiveFunction($json['children_data'], $categoryTree);
        }
        $this->logger->info("==============");
        echo $categoryTree . "\n";
    }

    private function someRecursiveFunction($json, $categoryTree)
    {
        foreach ($json as $element) {
            $categoryTree = $categoryTree . str_repeat(' ', $element['level']) . $element['id'] . '--' . $element['parent_id'] . '--' . $element['name'] . "\n";
            if (!empty($element['children_data'])) {
                $categoryTree = $this->someRecursiveFunction($element['children_data'], $categoryTree);
            }
        }
        return $categoryTree;
    }
}
