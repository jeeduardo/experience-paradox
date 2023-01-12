<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    // failure_code - Could be the following:
    // OOS - Out of Stock
    const FAILURE_CODE_OOS = 'OOS';

    // DISABLED - Disabled in Magento side (discontinued)
    const FAILURE_CODE_DISABLED = 'DISABLED';

    // ERROR - Error in communication with Magento API
    const FAILURE_CODE_ERROR = 'ERROR';

    use HasFactory;

    protected $fillable = [
        'magento_quote_item_id',
        'magento_quote_id',
        'cart_id',
        'sku',
        'name',
        'product_id',
        'qty',
        'price',
        'base_price',
        'discount_percent',
        'discount_amount',
        'base_discount_amount',
        'tax_percent',
        'tax_amount',
        'base_tax_amount',
        'row_total',
        'base_row_total',
        'row_total_with_discount',
        'product_type',
        'base_tax_before_discount ',
        'tax_before_discount',
        'original_custom_price',
        'redirect_url',
        'price_incl_tax',
        'base_price_incl_tax',
        'row_total_incl_tax',
        'base_row_total_incl_tax',
        'free_shipping',
        'retries',
        'is_failed'
    ];

    public function updateCartItem($json)
    {
        $this->magento_quote_item_id = $json['item_id'];
        $this->row_total = $json['price']; // @todo: really?
        return $this;
    }

    public function determineFailureCode($exception)
    {
        $message = strtolower($exception->getMessage());

        $this->failure_code = self::FAILURE_CODE_ERROR;
        // @todo: go back to this later..
        // @todo: This is the same reason when a product is disabled :(
        if (preg_match("/trying to add is not available/", $message)) {
            $this->failure_code = self::FAILURE_CODE_OOS;
        }
        // @todo: what if DISABLED?
        return $this;
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
