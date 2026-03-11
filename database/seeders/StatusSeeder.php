<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->insertOrIgnore([
            ['code' => 'ASGN', 'name' => 'Assigned'],
            ['code' => 'CKIN', 'name' => 'Checked-In'],  
            ['code' => 'CMPT', 'name' => 'Completed'],   
            ['code' => 'CLSD', 'name' => 'Closed'],      
        ]);
    }
}
