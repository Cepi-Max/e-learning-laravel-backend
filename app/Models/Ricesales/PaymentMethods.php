<?php

namespace App\Models\Ricesales;

use Illuminate\Database\Eloquent\Model;

class PaymentMethods extends Model
{
    //
    protected $table = 'payment_methods';
    protected $fillable = [
        'name', 
    ];

    
}
