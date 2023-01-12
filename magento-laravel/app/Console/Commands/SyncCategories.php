<?php

namespace App\Console\Commands;

use App\Services\Api\CategoryService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Magento categories to Laravel database';

    protected $apiBaseUrl;

    protected $token;

    /**
     * @var CategoryService
     */
    protected $categoryService;

    public function __construct(
        CategoryService $categoryService
    ) {
        parent::__construct();
        $this->categoryService = $categoryService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            echo self::class . " :: syncing categories \n";
            $jsonResponse = $this->getCategories();
            $categoryIds = $this->categoryService->processJson($jsonResponse);
            // get "other data" (e.g. custom_attributes) for the categories...
            $categoryCustomAttributesLevel2 = $this->getCategoryCustomAttributes(2);
            $this->categoryService->processCategoryCustomAttributes($categoryCustomAttributesLevel2, 2);
            $categoryCustomAttributesLevel3 = $this->getCategoryCustomAttributes(3);
            $this->categoryService->processCategoryCustomAttributes($categoryCustomAttributesLevel3, 3);
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }

    protected function getCategories()
    {
        $apiBaseUrl = $this->getApiBaseUrl();
        $token = $this->getToken();
        if (empty($apiBaseUrl) || empty($token)) {
            throw new \Exception('CANNOT SYNC :: API parameters (base url/token) are missing!');
        }
        $endpoint = '/categories';
        /** @var \Illuminate\Http\Client\Response $response */
        $response = Http::withToken($token)->get($apiBaseUrl . $endpoint);
        if (!$response->successful()) {
            throw new \Exception('CANNOT SYNC :: response not successful :: '.$response->getState());
        }

        $jsonResponse = $response->json();
        echo self::class . " :: got expected JSON response\n";
        if (!empty($jsonResponse)) {
            return $jsonResponse;
        }

        return false;
    }

    protected function getCategoryCustomAttributes($level)
    {
        $apiBaseUrl = $this->getApiBaseUrl();
        $token = $this->getToken();
        if (empty($apiBaseUrl) || empty($token)) {
            throw new \Exception('CANNOT SYNC :: API parameters (base url/token) are missing!');
        }
        $endpoint = '/categories/list';

        $response = Http::withToken($token)->get($apiBaseUrl . $endpoint, [
            'searchCriteria[filterGroups][0][filters][0][field]' => 'level',
            'searchCriteria[filterGroups][0][filters][0][value]' => $level,
            'searchCriteria[pageSize]' => 20
        ]);
        if (!$response->successful()) {
            throw new \Exception('CANNOT get category custom attributes :: response not successful :: '
                .$response->getState());
        }

        $jsonResponse = $response->json();
        if (!empty($jsonResponse)) {
            return $jsonResponse;
        }
        return false;
    }

    protected function getApiBaseUrl()
    {
        if (!$this->apiBaseUrl) {
            $this->apiBaseUrl = env('MAGENTO_API_BASE_URL');
        }
        return $this->apiBaseUrl;
    }

    protected function getToken()
    {
        if (!$this->token) {
            $this->token = env('MAGENTO_API_TOKEN');
        }
        return $this->token;
    }
}
