<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutAddress extends Model
{
    use HasFactory;

    const ADDRESS_TYPE_SHIPPING = 'shipping';

    const ADDRESS_TYPE_BILLING = 'billing';

    protected $table = 'checkout_address';

    protected $fillable = [
        'cart_id',
        'magento_quote_address_id',
        'customer_id',
        'address_type',
        'email',
        'prefix',
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'company',
        'street',
        'city',
        'region',
        'postcode',
        'country_id',
        'telephone',
        'same_as_billing',
        'collect_shipping_rates',
        'shipping_method',
        'shipping_description',
        'weight',
        'subtotal',
        'base_subtotal',
        'subtotal_with_discount',
        'base_subtotal_with_discount',
        'tax_amount',
        'base_tax_amount',
        'shipping_amount',
        'base_shipping_amount',
        'shipping_tax_amount',
        'base_shipping_tax_amount',
        'discount_amount',
        'base_discount_amount',
        'grand_total',
        'base_grand_total',
        'customer_notes',
        'applied_taxes',
        'discount_description',
        'shipping_discount_amount',
        'base_shipping_discount_amount',
        'subtotal_incl_tax',
        'base_subtotal_incl_tax',
        'discount_tax_compensation_amount',
        'base_discount_tax_compensation_amount',
        'shipping_discount_tax_compensation_amount',
        'base_shipping_discount_tax_compensation_amnt',
        'shipping_incl_tax',
        'base_shipping_incl_tax',
        'vat_id',
        'vat_is_valid',
        'vat_request_id',
        'vat_request_date',
        'vat_request_success',
        'validated_country_code',
        'validated_vat_number',
        'gift_message_id',
        'free_shipping',
    ];

    public static function createOrUpdateAddress($data, $addressType)
    {
        $addressData = [
            'cart_id' => $data['cart_id'],
            'address_type' => $addressType,
            'email' => '',
            'firstname' => $data['address']['firstname'],
            'lastname' => $data['address']['lastname'],
            'street' => $data['address']['street'][0],
            'region' => $data['address']['region'],
            'city' => $data['address']['city'],
            'country_id' => $data['address']['country_id'],
            'telephone' => $data['address']['telephone'],
            'postcode' => $data['address']['postcode'],
            'same_as_billing' => 0,
            'prefix' => '',
            'middlename' => '',
        ];
        if (!empty($data['address']['email'])) {
            $addressData['email'] = $data['address']['email'];
        }
        if (!empty($data['address']['same_as_billing'])) {
            $addressData['same_as_billing'] = $data['address']['same_as_billing'];
        }

        // Add or update checkout/shipping address data
        if (empty($data['address']['id'])) {
            $checkoutAddress = CheckoutAddress::create($addressData);
        } else {
            /** @var CheckoutAddress $checkoutAddress */
            $checkoutAddress = CheckoutAddress::findOrFail($data['address']['id']);
            $checkoutAddress->updateAddress($addressData);
        }

        return (!empty($checkoutAddress->id)) ? $checkoutAddress : false;
    }

    public function updateAddress($data)
    {
        $this->email = $data['email'];
        $this->firstname = $data['firstname'];
        $this->middlename = $data['middlename'];
        $this->lastname = $data['lastname'];
        $this->street = $data['street'];
        $this->city = $data['city'];
        $this->region = $data['region'];
        $this->country_id = $data['country_id'];
        $this->telephone = $data['telephone'];
        $this->postcode = $data['postcode'];
        $this->same_as_billing = $data['same_as_billing'];
        $this->save();
        return $this;
    }

    public function updateTotalsBasedOnMagento($totals)
    {
        $this->shipping_amount = $totals['shipping_amount'];
        $this->base_shipping_amount = $totals['base_shipping_amount'];
        $this->shipping_discount_amount = $totals['shipping_discount_amount'];
        $this->base_shipping_discount_amount = $totals['base_shipping_discount_amount'];
        $this->shipping_incl_tax = $totals['shipping_incl_tax'];
        $this->base_shipping_incl_tax = $totals['base_shipping_incl_tax'];
        $this->grand_total = $totals['grand_total'];
        $this->base_grand_total = $totals['base_grand_total'];
        return $this;
    }

    public static function getShippingAddress($cartId)
    {
        $shippingAddress = self::where('cart_id', $cartId)->first();

        // if ($shippingAddress->id) will return an error...
        if (!empty($shippingAddress->id)) {
            return $shippingAddress;
        }
        return false;
    }

    public static function getBillingAddress($cartId)
    {
        $billingAddress = self::where('cart_id', $cartId)
                                ->where('address_type', self::ADDRESS_TYPE_BILLING)
                                ->first();

        if (!empty($billingAddress->id)) {
            return $billingAddress;
        }
        return false;
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function shippingRates()
    {
        return $this->hasMany(CheckoutShippingRate::class);
    }
}
