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

        for ($i = 2; $i <= 20; $i++) {
            $absenMasukByAdmin = rand(1, 4) === 1;
            $absenKeluarByAdmin = rand(1, 4) === 1;

            Absensi::create([
                'user_id' => $i,
                'tanggal' => $carbon->now()->toDateString(),
                'absensi_masuk' => $carbon->createFromTime(rand(8, 12), rand(1, 59), rand(1, 59))->toTimeString(),
                'absensi_keluar' => $carbon->createFromTime(rand(15, 17), rand(1, 59), rand(1, 59))->toTimeString(),
                'keterangan' => 'Absensi',
                'status' => array_random($status_data),
                'foto_absensi_masuk' => $absenMasukByAdmin ? null : '/dummy/masuk.jpeg',
                'foto_absensi_keluar' => $absenKeluarByAdmin ? null : '/dummy/keluar.jpeg',
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
