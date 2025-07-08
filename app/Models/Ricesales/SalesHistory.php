<?php

namespace App\Models\Ricesales;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SalesHistory extends Model
{
    //
    protected $table = 'sales_history';
    protected $fillable = [
        'seller_id',
        'buyer_id',
        'order_id',
        'product_id',
        'quantity',
        'total',
        'sale_date',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }



}
