<?php

namespace Database\Seeders;

use App\Models\DataPadi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataPadiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datapadi = [
            [
                'nama' => 'Padi Rias',
                'user_id' => 1,
                'jumlah_padi' => '200',
                'jenis_padi' => 'sppd',
                'foto_padi' => null,
            ],
            [
                'nama' => 'Padi Cianjur',
                'user_id' => 2,
                'jumlah_padi' => '900',
                'jenis_padi' => '98sds',
                'foto_padi' => null,
            ],
        ];

        foreach ($datapadi as $padi) {
            DataPadi::create($padi);
        }
    }
}
