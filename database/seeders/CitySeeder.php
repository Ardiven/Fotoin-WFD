<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            'Jakarta',
            'Bandung',
            'Surabaya',
            'Semarang',
            'Yogyakarta',
            'Malang',
            'Bekasi',
            'Depok',
            'Tangerang',
            'Bogor',
            'Cirebon',
            'Solo (Surakarta)',
            'Tegal',
            'Purwokerto',
            'Madiun'
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'name' => $city,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
