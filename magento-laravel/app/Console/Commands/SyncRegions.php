<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncRegions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:regions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync regions to directory_country_regions table';

    /**
     * @var \App\Services\Api\ApiService
     */
    private $apiService;

    public function __construct(
        \App\Services\Api\ApiService $apiService
    ) {
        parent::__construct();
        $this->apiService = $apiService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $countryId = 'CA';
        $availableRegions = $this->getRegions($countryId);
        $result = $this->addRegions($availableRegions, $countryId);

        return Command::SUCCESS;
    }

    /**
     * Add the regions to the table
     *
     * @param $availableRegions
     * @param $countryId
     * @return bool
     */
    private function addRegions($availableRegions, $countryId)
    {
        $regions = [];
        foreach ($availableRegions as $region) {
            $regions[] = [
                'region_id' => $region['id'],
                'country_id' => $countryId,
                'code' => $region['code'],
                'default_name' => $region['name'],
            ];
        }
        return DB::table('directory_country_regions')->insert($regions);
    }

    /**
     * Get regions for specified $countryId from the Magento API
     *
     * @param $countryId
     * @return mixed
     */
    private function getRegions($countryId)
    {
        // http://magento.test/rest/V1/directory/countries/CA
        $endpoint = '/directory/countries/'.$countryId;
        $response = $this->apiService->get($endpoint);

        $availableRegions = $response['available_regions'];
        return $availableRegions;
    }
}
