<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = ['order_id',
    'product_id',
    'member_id',
    'user_id',
    'price',
    'qty',
    'color',
    'price',
    'size'];

    public function product()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }
}
