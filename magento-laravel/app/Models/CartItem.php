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

    /**
     * Update cart item data
     * @param $json
     * @return $this
     */
    public function updateCartItem($json)
    {
        $this->magento_quote_item_id = $json['item_id'];
        $this->row_total = $json['price'];
        return $this;
    }

    /**
     * Determine what to put in failure_code column
     * based on the add-to-cart API response we got from Magento
     *
     * @param $exception
     * @return $this
     */
    public function determineFailureCode($exception)
    {
        $message = strtolower($exception->getMessage());

        $this->failure_code = self::FAILURE_CODE_ERROR;
        // @todo: Go back later.. Below string is the same reason when a product is disabled :(
        if (preg_match("/trying to add is not available/", $message)) {
            $this->failure_code = self::FAILURE_CODE_OOS;
        }
        return $this;
    }

    /**
     * Get the specific error message for a cart item depending on failure_code
     *
     * @return string
     */
    public function getErrorMessageForItem()
    {
        $message = '';

        $errorMessages = [
            self::FAILURE_CODE_OOS => 'is out of stock :(',
            self::FAILURE_CODE_ERROR => 'has an error. Please contact administrator.',
            self::FAILURE_CODE_DISABLED => 'is now disabled. :('
        ];

        if (isset($errorMessages[$this->failure_code])) {
            $message = ': '.$this->name.' '.$errorMessages[$this->failure_code];
        }

        return $message;
    }

    /**
     * Get parent cart of the subject cart item
     * Accessed by $cartItem->cart NOT $cartItem->cart()
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Add a cart item data to the cart_items table
     *
     * @param $cart
     * @param $request
     * @param $quoteId
     * @return bool
     */
    public static function createCartItemData($cart, $request, $quoteId)
    {
        $cartItemData = self::parseCartItemData($cart, $request, $quoteId);
        $cartItem = self::create($cartItemData);

        return (!empty($cartItem->id)) ? $cartItem : false;
    }

    /**
     * Return an array of cart item data to be inserted/updated
     *
     * @param $cart
     * @param $request
     * @param $quoteId
     * @return array
     */
    private static function parseCartItemData($cart, $request, $quoteId)
    {
        $paramCartItem = $request->post('cartItem');
        $paramProduct = $request->post('product');
        $rowTotal = (int)$paramCartItem['qty'] * $paramProduct['price'];

        return [
            'magento_quote_id' => $quoteId,
            'cart_id' => $cart->id,
            'sku' => $paramCartItem['sku'],
            'name' => $paramProduct['name'],
            'product_id' => $paramProduct['id'],
            'qty' => $paramCartItem['qty'],
            'price' => $paramProduct['price'],
            'base_price' => $paramProduct['price'],
            'row_total' => $rowTotal,
            'base_row_total' => $rowTotal,
        ];
    }
}
