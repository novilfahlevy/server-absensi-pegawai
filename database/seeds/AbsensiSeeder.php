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
            $absenMasukByAdmin = rand(1, 4) === 1;
            $absenKeluarByAdmin = rand(1, 4) === 1;

            Absensi::create([
                'user_id' => rand(3, 50),
                'tanggal' => $carbon->createFromDate(2020, rand(2, 12), rand(8, 32))->toDateString(),
                'absensi_masuk' => $carbon->createFromTime(rand(8, 12), rand(1, 59), rand(1, 59))->toTimeString(),
                'absensi_keluar' => $carbon->createFromTime(rand(15, 17), rand(1, 59,), rand(1, 59))->toTimeString(),
                'keterangan' => 'Absensi',
                'status' => array_random($status_data),
                'foto_absensi_masuk' => $absenMasukByAdmin ? null : uniqid() . '_' . 'masuk.jpg',
                'foto_absensi_keluar' => $absenKeluarByAdmin ? null : uniqid() . '_' . 'keluar.jpg',
                'latitude_absen_masuk' => $absenMasukByAdmin ? null : $faker->latitude(),
                'longitude_absen_masuk' => $absenMasukByAdmin ? null : $faker->longitude(),
                'latitude_absen_keluar' => $absenKeluarByAdmin ? null : $faker->latitude(),
                'longitude_absen_keluar' => $absenKeluarByAdmin ? null : $faker->longitude(),
                'absen_masuk_oleh_admin' => $absenMasukByAdmin ? 1 : null,
                'absen_keluar_oleh_admin' => $absenKeluarByAdmin ? 1 : null
            ]);
        }
    }
}
