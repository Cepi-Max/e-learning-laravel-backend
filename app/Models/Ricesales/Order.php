<?php

namespace App\Models\Ricesales;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $table = 'orders';
    protected $fillable = ['user_id', 'order_code', 'total_price', 'status', 'stock', 'image', 'midtrans_transaction_token', 'is_paid'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
    // Order.php
    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }


    public function order() {
        return $this->belongsTo(Order::class);
    }

}
