<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['order_id',
        'user_id',
        'phone_1',
        'phone_2',
        'member_id',
        'city',
        'address',
        'voucher',
        'payment',
        'status',
        'delivery_charges',
        'remark'];

        public function order_items()
        {
            return $this->hasMany(OrderItem::class,'order_id','order_id');
        }

        public function customer(){
            return $this->hasOne(User::class,'id','user_id');
        }
}


