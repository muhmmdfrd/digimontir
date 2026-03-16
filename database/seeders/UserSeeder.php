<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRoleId = Role::where('code', 'ADM')->first()->id;
        $techRoleId = Role::where('code', 'TECH')->first()->id;

        DB::table('users')->insertOrIgnore([
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.admin@digimontir.com',
                'password' => bcrypt('password'),
                'role_id' => $adminRoleId,
                'supervisor_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Andi Wijaya',
                'email' => 'andi.tech@digimontir.com',
                'password' => bcrypt('password'),
                'role_id' => $techRoleId,
                'supervisor_id' => 1, // Assuming Budi is ID 1
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Riko Pratama',
                'email' => 'riko.tech@digimontir.com',
                'password' => bcrypt('password'),
                'role_id' => $techRoleId,
                'supervisor_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
        ]);
    }
}
