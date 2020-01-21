<?php

use App\Absensi;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

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

        Absensi::create([
            'user_id' => 2,
            'tanggal' => $carbon->toDateString(),
            'absensi_masuk' => $carbon->toTimeString(),
            'absensi_keluar' => $carbon->toTimeString(),
            'keterangan' => 'Absensi',
            'foto_absensi_masuk' => 'masuk.jpg',
            'foto_absensi_keluar' => 'keluar.jpg',
            'latitude' => '1.111',
            'longitude' => '1.111',
        ]);
    }
}
