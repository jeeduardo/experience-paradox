<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'magento_order_id',
        'state',
        'status',
        'coupon_code',
        'shipping_description',
        'customer_id',
        'base_discount_amount',
        'base_discount_canceled',
        'base_discount_invoiced',
        'base_discount_refunded',
        'base_grand_total',
        'base_shipping_amount',
        'base_shipping_canceled',
        'base_shipping_invoiced',
        'base_shipping_refunded',
        'base_shipping_tax_amount',
        'base_shipping_tax_refunded',
        'base_subtotal',
        'base_subtotal_canceled',
        'base_subtotal_invoiced',
        'base_subtotal_refunded',
        'base_tax_amount',
        'base_tax_canceled',
        'base_tax_invoiced',
        'base_tax_refunded',
        'base_to_global_rate',
        'base_to_order_rate',
        'base_total_canceled',
        'base_total_invoiced',
        'base_total_invoiced_cost',
        'base_total_offline_refunded',
        'base_total_online_refunded',
        'base_total_paid',
        'base_total_qty_ordered',
        'base_total_refunded',
        'discount_amount',
        'discount_canceled',
        'discount_invoiced',
        'discount_refunded',
        'grand_total',
        'shipping_amount',
        'shipping_canceled',
        'shipping_invoiced',
        'shipping_refunded',
        'shipping_tax_amount',
        'shipping_tax_refunded',
        'store_to_base_rate',
        'store_to_order_rate',
        'subtotal',
        'subtotal_canceled',
        'subtotal_invoiced',
        'subtotal_refunded',
        'tax_amount',
        'tax_canceled',
        'tax_invoiced',
        'tax_refunded',
        'total_canceled',
        'total_invoiced',
        'total_offline_refunded',
        'total_online_refunded',
        'total_paid',
        'total_qty_ordered',
        'total_refunded',
        'can_ship_partially',
        'can_ship_partially_item',
        'customer_is_guest',
        'customer_note_notify',
        'billing_address_id',
        'customer_group_id',
        'edit_increment',
        'email_sent',
        'send_email',
        'force_shipment_with_invoice',
        'quote_id',
        'adjustment_negative',
        'adjustment_positive',
        'base_adjustment_negative',
        'base_adjustment_positive',
        'base_shipping_discount_amount',
        'base_subtotal_incl_tax',
        'base_total_due',
        'payment_authorization_amount',
        'shipping_discount_amount',
        'subtotal_incl_tax',
        'total_due',
        'weight',
        'increment_id',
        'applied_rule_ids',
        'base_currency_code',
        'customer_email',
        'customer_firstname',
        'customer_lastname',
        'customer_middlename',
        'customer_prefix',
        'customer_suffix',
        'customer_taxvat',
        'discount_description',
        'ext_customer_id',
        'ext_order_id',
        'global_currency_code',
        'hold_before_state',
        'hold_before_status',
        'order_currency_code',
        'original_increment_id',
        'relation_child_id',
        'relation_child_real_id',
        'relation_parent_id',
        'relation_parent_real_id',
        'remote_ip',
        'shipping_method',
        'store_currency_code',
        'store_name',
        'x_forwarded_for',
        'payment_auth_expiration',
        'quote_address_id',
        'shipping_address_id',
        'customer_dob',
        'customer_note',
        'total_item_count',
        'customer_gender',
        'discount_tax_compensation_amount',
        'base_discount_tax_compensation_amount',
        'shipping_discount_tax_compensation_amount',
        'base_shipping_discount_tax_compensation_amnt | decimal(20,4)',
        'discount_tax_compensation_invoiced',
        'base_discount_tax_compensation_invoiced',
        'discount_tax_compensation_refunded',
        'base_discount_tax_compensation_refunded',
        'shipping_incl_tax',
        'base_shipping_incl_tax',
        'coupon_rule_name',
        'gift_message_id',
        'paypal_ipn_customer_notified'
    ];

    public static function prepareOrderData($jsonData)
    {
        $data = [];
        $data['magento_order_id'] = $jsonData['entity_id'];
        $data['state'] = $jsonData['state'];
        $data['status'] = $jsonData['status'];
        $data['grand_total'] = $jsonData['grand_total'];
        $data['base_grand_total'] = $jsonData['base_grand_total'];
        $data['subtotal'] = $jsonData['subtotal'];
        $data['base_subtotal'] = $jsonData['base_subtotal'];
        $data['base_grand_total'] = $jsonData['base_grand_total'];

        // Field mapping to set the column value from our orders table to what's in Magento
        $fieldsMapping = [
            'customer_email' => 'customer_email',
            'customer_firstname' => 'customer_firstname',
            'customer_lastname' => 'customer_lastname',
            'increment_id' => 'increment_id',
            'shipping_amount' => 'shipping_amount',
            'total_qty_ordered' => 'total_qty_ordered',
        ];

        foreach ($fieldsMapping as $ourField => $magentoField) {
            $data[$ourField] = $jsonData[$magentoField];
        }
//        $data['total_paid'] = $jsonData['total_paid'];
//        $data['base_total_paid'] = $jsonData['base_total_paid'];
//        $data['total_qty_ordered'] = $jsonData['total_qty_ordered'];
//        $data['total_item_count'] = $jsonData['total_item_count'];
//        $data['shipping_amount'] = $jsonData['shipping_amount'];
//        $data['shipping_amount'] = $jsonData['shipping_amount'];
        return $data;
    }

    public function afterSave($cartId)
    {
        $cart = Cart::findOrFail($cartId);
        $cart->order_id = $this->id;
        $cart->save();
        return $this;
    }

    public function getFullName()
    {
        return $this->customer_firstname . ' ' . $this->customer_lastname;
    }
}
