<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ricesales\Cart;
use App\Models\Ricesales\CartItem;
use App\Models\Ricesales\Product;
use App\Models\User;

class CartSeeder extends Seeder
{
    public function run()
    {
        // $user = User::where('user_id', 4); 
        $cart = Cart::create(['user_id' => 4]);

        $products = Product::inRandomOrder()->take(3)->get();

        foreach ($products as $product) {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => rand(1, 5),
            ]);
        }
    }
}
