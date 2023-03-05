<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesToday extends Model
{
    use HasFactory;

    protected $table = 'sales_today';

    public static function getSalesToday($dateToday)
    {
        $salesToday = self::where('date_today', $dateToday)->get()->first();

        if (empty($salesToday->id)) {
            return false;
        }

        return $salesToday;
    }

    public static function getSalesPriorToToday($dateToday)
    {
        $salesPriorToToday = self::where('date_today', '<', $dateToday)
                                ->orderBy('date_today', 'DESC')
                                ->get()
                                ->first();
        if (empty($salesPriorToToday->id)) {
            return false;
        }
        return $salesPriorToToday;
    }
}
