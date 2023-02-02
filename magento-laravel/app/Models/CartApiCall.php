<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartApiCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'cart_item_id',
        'magento_api_url',
        'method',
        'payload',
        'response_status',
        'response',
        'status',
        'error'
    ];
}
