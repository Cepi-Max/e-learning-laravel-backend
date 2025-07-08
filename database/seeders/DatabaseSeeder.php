<?php

namespace Database\Seeders;

use App\Models\Ricesales\Order;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Ricesales\OrderItem;
use App\Models\Ricesales\Payment;
use App\Models\Ricesales\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DataPadiSeeder::class,
            ProductSeeder::class,
            CartSeeder::class,
            PaymentMethodSeeder::class,
        ]);
        $user = User::create([
            'name' => 'Mas Pembeli',
            'email' => 'pembelibaru@example.com',
            'password' => Hash::make('password123'),
            'lokasi' => 'Spa',
            'role' => 'user',
        ]);

        // 2. Seed Products (buat oleh user_id petani / admin / bebas sementara)
        $product1 = Product::create([
            'user_id' => $user->id,
            'name' => 'Beras Premium 5kg',
            'description' => 'Beras kualitas super dari petani lokal',
            'price' => 75000,
            'stock' => 100,
            'image' => 'beras_5kg.jpg',
        ]);

        $product2 = Product::create([
            'user_id' => $user->id,
            'name' => 'Beras Medium 5kg',
            'description' => 'Beras medium untuk kebutuhan sehari-hari',
            'price' => 60000,
            'stock' => 150,
            'image' => 'beras_medium.jpg',
        ]);

        // 3. Seed Order
        $order = Order::create([
            'user_id' => $user->id,
            'order_code' => 'ORD-' . strtoupper(Str::random(8)),
            'total_price' => ($product1->price * 2) + ($product2->price * 1),
            'status' => 'pending',
        ]);

        // 4. Seed Order Items
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product1->id,
            'quantity' => 2,
            'price' => $product1->price,
            'subtotal' => $product1->price * 2,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product2->id,
            'quantity' => 1,
            'price' => $product2->price,
            'subtotal' => $product2->price * 1,
        ]);

        // 5. Seed Payment
        Payment::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'payment_status' => 'pending',
            'transaction_id' => 'TRX-' . strtoupper(Str::random(8)),
            'amount' => $order->total_price,
        ]);
    }
}
