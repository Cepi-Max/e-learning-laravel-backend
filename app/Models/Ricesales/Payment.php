<?php

namespace App\Models\Ricesales;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $table = 'payments';
    protected $fillable = [
        'order_id', 'user_id', 'payment_method', 'payment_status', 'transaction_id', 'amount'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
