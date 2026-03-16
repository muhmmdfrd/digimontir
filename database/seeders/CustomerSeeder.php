<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insertOrIgnore([
            [
                'name' => 'Siti Aminah',
                'email' => 'siti.aminah@example.com',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 10, Jakarta Selatan',
                'latitude' => -6.241586,
                'longitude' => 106.822286,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Joko Susilo',
                'email' => 'joko.s@example.com',
                'phone' => '089876543210',
                'address' => 'Komp. Buana Asri, Depok',
                'latitude' => -6.385589,
                'longitude' => 106.832789,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PT. Maju Mundur',
                'email' => 'admin@majumundur.co.id',
                'phone' => '0215556667',
                'address' => 'Gedung Surya Raya Lt. 3, Jakarta Pusat',
                'latitude' => -6.182312,
                'longitude' => 106.843321,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
