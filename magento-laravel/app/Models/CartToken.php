<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartToken extends Model
{
    use HasFactory;

    protected $table = 'cart_tokens';

    protected $fillable = [
        'cart_id',
        'cart_token',
    ];

    public static function findByCartToken($token)
    {
        return self::where('cart_token', $token)->first();
    }
}
