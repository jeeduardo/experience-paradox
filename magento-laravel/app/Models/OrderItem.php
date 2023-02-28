<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'magento_item_id',
        'order_id',
        'magento_order_id',
        'magento_parent_item_id',
        'magento_quote_item_id',
        'store_id',
        'product_id',
        'orders',
        'products',
        'sku',
        'name',
        'description',
        'applied_rule_ids',
        'additional_data',
        'is_qty_decimal',
        'no_discount',
        'qty_backordered',
        'qty_canceled',
        'qty_invoiced',
        'qty_ordered',
        'qty_shipped',
        'base_cost',
        'price',
        'base_price',
        'original_price',
        'base_original_price',
        'tax_percent',
        'tax_amount',
        'base_tax_amount',
        'tax_invoiced',
        'base_tax_invoiced',
        'discount_percent',
        'discount_amount',
        'base_discount_amount',
        'discount_invoiced',
        'base_discount_invoiced',
        'amount_refunded',
        'base_amount_refunded',
        'row_total',
        'base_row_total',
        'row_invoiced',
        'base_row_invoiced',
        'price_incl_tax',
        'base_price_incl_tax',
        'row_total_incl_tax',
        'base_row_total_incl_tax',
        'row_weight',
        'other_magento_data',
        'locked_do_invoice',
        'locked_do_ship',
        'free_shipping',
    ];

    public static function createOrderItems(
        \App\Models\Order $order,
        $orderItems,
        $cartItems
    ) {
        /** @var \Psr\Log\LoggerInterface $logger */
        $logger = \Illuminate\Support\Facades\Log::channel('order_sync');
        // @todo: rearrange cart items such that each are keyed by cart item id
        $items = [];
        foreach ($cartItems as $cartItem) {
            $items[$cartItem->magento_quote_item_id] = $cartItem;
        }

        $createdOrderItems = [];
        foreach ($orderItems as $orderItem) {
            // $items array is from cart_items
            $data = self::prepareData($orderItem, $items[$orderItem['quote_item_id']]);
            $data['order_id'] = $order->id;
            $data['magento_order_id'] = $order->magento_order_id;
            $orderItemModel = OrderItem::create($data);
            if (empty($orderItemModel->id)) {
                $logger->error('order_items record not created. please check...');
                $logger->error(print_r($data, true));
                continue;
                // @todo: log this
            }
            $logger->info('order_items record created :: ID :: '.$orderItemModel->id);
            $createdOrderItems[] = $orderItemModel;
        }
        return $createdOrderItems;
    }

    public static function prepareData($orderItem, $item)
    {
        $data = [];
        $columns = [
            'applied_rule_ids' => 'applied_rule_ids',
            'base_discount_amount' => 'base_discount_amount',
            'base_original_price' => 'base_original_price',
            'base_price' => 'base_price',
            'base_price_incl_tax' => 'base_price_incl_tax',
            'base_row_total' => 'base_row_total',
            'base_row_total_incl_tax' => 'base_row_total_incl_tax',
            'base_tax_amount' => 'base_tax_amount',
            'discount_amount' => 'discount_amount',
            'discount_percent' => 'discount_percent',
            'free_shipping' => 'free_shipping',
            'is_qty_decimal' => 'is_qty_decimal',
            'item_id' => 'magento_item_id',
            'name' => 'name',
            'order_id' => 'magento_order_id',
            'original_price' => 'original_price',
            'price' => 'price',
            'price_incl_tax' => 'price_incl_tax',
            'qty_ordered' => 'qty_ordered',
            'quote_item_id' => 'magento_quote_item_id',
            'row_total' => 'row_total',
            'row_total_incl_tax' => 'row_total_incl_tax',
            'row_weight' => 'row_weight',
            'sku' => 'sku',
            'store_id' => 'store_id',
            'tax_amount' => 'tax_amount',
            'tax_percent' => 'tax_percent',
        ];
        foreach ($columns as $magentoColumn => $column) {
            $data[$column] = $orderItem[$magentoColumn];
        }
        $data['product_id'] = $item->product_id;

        return $data;
    }
}
