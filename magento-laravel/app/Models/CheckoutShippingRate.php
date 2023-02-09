<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutShippingRate extends Model
{
    use HasFactory;

    protected $table = 'checkout_shipping_rates';

    protected $fillable = [
        'address_id',
        'magento_address_id',
        'carrier',
        'carrier_title',
        'code',
        'method',
        'method_description',
        'price',
        'error_message',
        'method_title'
    ];

    public static function addShippingRatesForAddress(
        $checkoutAddressId,
        $shippingMethods
    ) {
        $shippingMethodObjects = [];
        foreach ($shippingMethods as $method) {
            $addressId = $checkoutAddressId;

            $shippingMethod = self::where('address_id', $addressId)
                                ->where('method', $method['method_code'])
                                ->first();

            if (!empty($shippingMethod)) {
                // update
                $shippingMethod->carrier = $method['carrier_code'];
                $shippingMethod->carrier_title = $method['carrier_title'];
                $shippingMethod->code = $method['method_code'];
                $shippingMethod->method = $method['method_code'];
                $shippingMethod->method_title = $method['method_title'];
                $shippingMethod->price = $method['base_amount'];
                $shippingMethod->save();
            } else {
                $shippingMethod = self::create([
                    'address_id' => $addressId,
                    'carrier' => $method['carrier_code'],
                    'carrier_title' => $method['carrier_title'],
                    'code' => $method['method_code'],
                    'method' => $method['method_code'],
                    'method_title' => $method['method_title'],
                    'price' => $method['base_amount'],
                ]);
            }
            $shippingMethodObjects[] = $shippingMethod;
        }
        return $shippingMethodObjects;
    }

    public static function getShippingRatesForAddress($addressId)
    {
        $shippingMethods = CheckoutShippingRate::where('address_id', $addressId)->get();
        return $shippingMethods;
    }
}
