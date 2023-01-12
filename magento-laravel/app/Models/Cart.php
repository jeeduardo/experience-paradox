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
        $this->grand_total = $subtotal;

        return $this;
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
