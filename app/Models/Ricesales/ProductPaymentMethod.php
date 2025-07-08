<?php

namespace App\Models\Ricesales;

use Illuminate\Database\Eloquent\Model;

class ProductPaymentMethod extends Model
{
    //
    protected $table = 'product_payment_methods';
    protected $fillable = [
        'product_id', 
        'payment_method_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function payment()
    {
        return $this->belongsTo(PaymentMethods::class);
    }

}
