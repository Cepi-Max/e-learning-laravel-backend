<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = ['bank transfer', 'e-wallet', 'qris', 'cod'];

        foreach ($methods as $method) {
            DB::table('payment_methods')->insert([
                'name' => $method,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
