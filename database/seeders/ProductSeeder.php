<?php

namespace Database\Seeders;

use App\Models\Ricesales\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {  
        // Buat produk contoh
        $products = [
            [
                'user_id' => '1',
                'name' => 'Gabah Rias',
                'description' => 'Gabah Rias Kualitas Terjamin, ditanam tanpa pupuk kimia.',
                'price' => '30000.00',
                'stock' => '80',
                'image' => '1.jpg',
            ],
            [
                'user_id' => '2',
                'name' => 'Gabah Bangka Selatan',
                'description' => 'Gabah bersih kering, siap digiling tanpa dikeringkan kembali.',
                'price' => '50000.00',
                'stock' => '90',
                'image' => '2.jpg',
            ],
            [
                'user_id' => '1',
                'name' => 'Beras Premium 5 kiloan',
                'description' => 'Beras kualitas super dari petani lokal.',
                'price' => '50000.00',
                'stock' => '90',
                'image' => '3.jpg',
            ],
            [
                'user_id' => '2',
                'name' => 'Beras Medium 5 kiloan',
                'description' => 'Beras medium untuk kebutuhan sehari-hari.',
                'price' => '50000.00',
                'stock' => '90',
                'image' => 'default.jpg',
            ],
        ];

        // Insert semua produk ke database
        DB::table('products')->insert($products);
        
        // Ambil ID metode pembayaran yang ada
        $paymentMethodIds = DB::table('payment_methods')->pluck('id')->toArray();
        
        // Ambil ID produk yang baru dimasukkan
        $productIds = DB::table('products')->pluck('id')->toArray();

        // Hubungkan setiap produk dengan metode pembayaran yang tersedia
        $productPaymentData = [];
        foreach ($productIds as $productId) {
            foreach ($paymentMethodIds as $paymentMethodId) {
                $productPaymentData[] = [
                    'product_id' => $productId,
                    'payment_method_id' => $paymentMethodId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        // Insert ke tabel product_payment_methods
        DB::table('product_payment_methods')->insert($productPaymentData);
    }
}
