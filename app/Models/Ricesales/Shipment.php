<?php

namespace App\Models\Ricesales;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    //
    protected $table = 'shipments';
    protected $fillable = ['order_id', 'tracking_number', 'courier', 'status', 'estimated_delivery'];

}
