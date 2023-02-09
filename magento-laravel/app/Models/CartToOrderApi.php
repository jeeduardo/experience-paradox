<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartToOrderApi extends Model
{
    use HasFactory;

    protected $table = 'cart_to_order_api';

    protected $fillable = [
        'cart_id',
        'order_id',
        'endpoint',
        'http_return_status',
        'order_creation_api_response'
    ];
}
