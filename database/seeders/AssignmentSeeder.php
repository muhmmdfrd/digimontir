<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = User::where('email', 'budi.admin@digimontir.com')->first()->id ?? 1;
        $techAndiId = User::where('email', 'andi.tech@digimontir.com')->first()->id ?? 2;
        $techRikoId = User::where('email', 'riko.tech@digimontir.com')->first()->id ?? 3;

        $statusPending = Status::first()->id ?? 1;
        $statusInProgress = Status::skip(1)->first()->id ?? 2;
        $statusCompleted = Status::skip(2)->first()->id ?? 3;

        DB::table('assignments')->insertOrIgnore([
            [
                'admin_id' => $adminId,
                'technician_id' => $techAndiId,
                'customer_id' => 1,
                'status_id' => $statusCompleted,
                'description_by_admin' => 'Perbaikan AC mobil rusak tidak dingin',
                'lat_check_in' => -6.241586,
                'lng_check_in' => 106.822286,
                'check_in_photo_path' => 'photos/check_in_1.jpg',
                'check_out_photo_path' => 'photos/check_out_1.jpg',
                'lat_check_out' => -6.241600,
                'lng_check_out' => 106.822300,
                'description_by_technician' => 'Kompresor AC sudah diganti dan freon diisi ulang',
                'rating' => 5,
                'review_by_admin' => 'Pekerjaan memuaskan dan cepat',
                'completed_at' => now()->subDays(2),
                'closed_at' => now()->subDays(1),
                'scheduled_date' => now()->subDays(1),
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(1),
            ],
            [
                'admin_id' => $adminId,
                'technician_id' => $techRikoId,
                'customer_id' => 2,
                'status_id' => $statusInProgress,
                'description_by_admin' => 'Penggantian oli dan filter udara',
                'lat_check_in' => -6.385589,
                'lng_check_in' => 106.832789,
                'check_in_photo_path' => 'photos/check_in_2.jpg',
                'check_out_photo_path' => null,
                'lat_check_out' => null,
                'lng_check_out' => null,
                'description_by_technician' => null,
                'rating' => null,
                'review_by_admin' => null,
                'completed_at' => null,
                'closed_at' => null,
                'scheduled_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admin_id' => $adminId,
                'technician_id' => $techAndiId,
                'customer_id' => 3,
                'status_id' => $statusPending,
                'description_by_admin' => 'Pengecekan rem blong kendaraan niaga',
                'lat_check_in' => null,
                'lng_check_in' => null,
                'check_in_photo_path' => null,
                'check_out_photo_path' => null,
                'lat_check_out' => null,
                'lng_check_out' => null,
                'description_by_technician' => null,
                'rating' => null,
                'review_by_admin' => null,
                'completed_at' => null,
                'closed_at' => null,
                'scheduled_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admin_id' => $adminId,
                'technician_id' => $techRikoId,
                'customer_id' => 1,
                'status_id' => $statusCompleted,
                'description_by_admin' => 'Tune up rutin bulanan',
                'lat_check_in' => -6.241586,
                'lng_check_in' => 106.822286,
                'check_in_photo_path' => 'photos/check_in_4.jpg',
                'check_out_photo_path' => 'photos/check_out_4.jpg',
                'lat_check_out' => -6.241586,
                'lng_check_out' => 106.822286,
                'description_by_technician' => 'Busi dan jalur injeksi sudah dibersihkan',
                'rating' => 4,
                'review_by_admin' => 'Pekerjaan standar, namun tepat waktu',
                'completed_at' => now()->subDay(),
                'closed_at' => now(),
                'scheduled_date' => now(),
                'created_at' => now()->subDays(2),
                'updated_at' => now(),
            ],
        ]);
    }
}
