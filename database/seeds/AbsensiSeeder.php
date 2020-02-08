<?php

use App\Absensi;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carbon = new Carbon();
        $faker = Faker::create();
        $status_data = ['tepat waktu', 'terlambat', 'kecepatan'];

        for ($i = 1; $i <= 30; $i++) {
            Absensi::create([
                'user_id' => rand(3, 50),
                'tanggal' => $carbon->createFromDate(2020, rand(2, 12), rand(8, 32))->toDateString(),
                'absensi_masuk' => $carbon->createFromTime(rand(8, 12), rand(1, 59), rand(1, 59))->toTimeString(),
                'absensi_keluar' => $carbon->createFromTime(rand(15, 17), rand(1, 59,), rand(1, 59))->toTimeString(),
                'keterangan' => 'Absensi',
                'status' => array_random($status_data),
                'foto_absensi_masuk' => uniqid() . '_' . 'masuk.jpg',
                'foto_absensi_keluar' => uniqid() . '_' . 'keluar.jpg',
                'latitude_absen_masuk' => $faker->latitude(),
                'longitude_absen_masuk' => $faker->longitude(),
                'latitude_absen_keluar' => $faker->latitude(),
                'longitude_absen_keluar' => $faker->longitude(),
            ]);
        }
    }
}
