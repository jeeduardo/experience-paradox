<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryCountryRegion extends Model
{
    use HasFactory;

    protected $table = 'directory_country_regions';

    public static function getRegions($countryId)
    {
        $results = self::where('country_id', $countryId)->get();

        $regions = [];
        foreach ($results as $region) {
            $regions[$region->region_id] = $region->default_name;
        }
        return $regions;
    }
}
