<?php

namespace App\Models\Ricesales;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $table = 'reviews';
    protected $fillable = ['user_id', 'product_id', 'rating', 'review'];

}
