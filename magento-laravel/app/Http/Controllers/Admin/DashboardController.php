<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SalesToday;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $dateToday = $this->getDateToday();
        $vars = [
            'salesToday' => SalesToday::getSalesToday($dateToday),
            'salesPriorToToday' => SalesToday::getSalesPriorToToday($dateToday),
            'bestsellers' => OrderItem::getBestsellers(),
            'topCustomers' => Order::getTopCustomers(),
        ];
        
        return view('admin.dashboard', $vars);
    }

    private function getDateToday()
    {
        // Same as EST / Toronto time
        $date = new \DateTime('now', new \DateTimeZone('America/New_York'));
        $dateToday = $date->format('Y-m-d');
        return $dateToday;
    }
}
