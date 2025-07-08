<?php

namespace App\Models\Ricesales;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    //
    protected $table = 'products';
    protected $fillable = ['user_id', 'name', 'description', 'price', 'stock', 'image'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
