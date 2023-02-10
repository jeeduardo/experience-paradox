<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'magento_quote_id',
        'checkout_method',
        'customer_id',
        'customer_email',
        'customer_firstname',
        'customer_middlename',
        'customer_lastname',
        'customer_suffix',
        'customer_is_guest',
        'remote_ip',
        'grand_total',
        'base_grand_total',
        'subtotal',
        'base_subtotal',
        'items_count',
        'items_qty',
        'is_active'
    ];

    /**
     * Update cart data
     * @param null $data
     * @return $this
     */
    public function updateCart($data = null)
    {
        $cartItems = $this->cartItems;
        $itemsQty = 0;
        $itemsCount = 0;
        $subtotal = 0;
        foreach ($cartItems as $cartItem) {
            $itemsQty += $cartItem->qty;
            $subtotal += $cartItem->row_total;
            $itemsCount++;
        }
        $this->items_qty = $itemsQty;
        $this->items_count = $itemsCount;
        $this->subtotal = $subtotal;
        $this->base_subtotal = $subtotal;
        $this->grand_total = $subtotal;
        $this->base_grand_total = $subtotal;

        return $this;
    }

    /**
     * Update the cart totals based on Magento's calculation
     * @param $totals
     * @return $this
     */
    public function updateTotalsBasedOnMagento($totals)
    {
        $this->grand_total = $totals['grand_total'];
        $this->base_grand_total = $totals['base_grand_total'];
        $this->subtotal = $totals['subtotal'];
        $this->base_subtotal = $totals['base_subtotal'];
        $this->items_qty = $totals['items_qty'];
        $this->items_count = count($totals['items']);
        if (!empty($totals['total_segments'])) {
            $this->total_segments = \json_encode($totals['total_segments']);
        }
        return $this;
    }

    /**
     * Get those total segments that the customer will see at checkout/cart page
     * @return array|mixed
     */
    public function getTotalSegments()
    {
        $totalSegments = \json_decode($this->total_segments, true);
        if (\json_last_error() == JSON_ERROR_NONE) {
            return $totalSegments;
        }
        return [];
    }

    /**
     * Accessor method for the cart's items
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Look for any erring cart items, return that item if found
     * @return bool|\App\Models\CartItem
     */
    public function hasErringCartItem()
    {
        $items = $this->cartItems;

        // Return cart item if something is not valid.
        foreach ($items as $cartItem) {
            if ($cartItem->has_failed) {
                return $cartItem;
            }
        }

        return false;
    }

}
