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
            'tanggal' => $carbon->now()->subDays(1),
            'absensi_masuk' => $carbon->toTimeString(),
            'absensi_keluar' => $carbon->toTimeString(),
            'keterangan' => 'Absensi',
            'status' => 'tepat waktu',
            'foto_absensi_masuk' => 'masuk.jpg',
            'foto_absensi_keluar' => 'keluar.jpg',
            'latitude_absen_masuk' => '1.111',
            'longitude_absen_masuk' => '1.111',
            'latitude_absen_keluar' => '1.111',
            'longitude_absen_keluar' => '1.111',
        ]);

        Absensi::create([
            'user_id' => 2,
            'tanggal' => $carbon->now()->subDays(2),
            'absensi_masuk' => $carbon->toTimeString(),
            'absensi_keluar' => $carbon->toTimeString(),
            'keterangan' => 'Absensi',
            'status' => 'tepat waktu',
            'foto_absensi_masuk' => 'masuk.jpg',
            'foto_absensi_keluar' => 'keluar.jpg',
            'latitude_absen_masuk' => '1.111',
            'longitude_absen_masuk' => '1.111',
            'latitude_absen_keluar' => '1.111',
            'longitude_absen_keluar' => '1.111',
        ]);

        Absensi::create([
            'user_id' => 2,
            'tanggal' => $carbon->now()->subDays(3),
            'absensi_masuk' => $carbon->toTimeString(),
            'absensi_keluar' => $carbon->toTimeString(),
            'keterangan' => 'Absensi',
            'status' => 'tepat waktu',
            'foto_absensi_masuk' => 'masuk.jpg',
            'foto_absensi_keluar' => 'keluar.jpg',
            'latitude_absen_masuk' => '1.111',
            'longitude_absen_masuk' => '1.111',
            'latitude_absen_keluar' => '1.111',
            'longitude_absen_keluar' => '1.111',
        ]);

        Absensi::create([
            'user_id' => 2,
            'tanggal' => $carbon->now()->subDays(4),
            'absensi_masuk' => $carbon->toTimeString(),
            'absensi_keluar' => $carbon->toTimeString(),
            'keterangan' => 'Absensi',
            'status' => 'tepat waktu',
            'foto_absensi_masuk' => 'masuk.jpg',
            'foto_absensi_keluar' => 'keluar.jpg',
            'latitude_absen_masuk' => '1.111',
            'longitude_absen_masuk' => '1.111',
            'latitude_absen_keluar' => '1.111',
            'longitude_absen_keluar' => '1.111',
        ]);

        Absensi::create([
            'user_id' => 2,
            'tanggal' => $carbon->now()->subDays(5),
            'absensi_masuk' => $carbon->toTimeString(),
            'absensi_keluar' => $carbon->toTimeString(),
            'keterangan' => 'Absensi',
            'status' => 'tepat waktu',
            'foto_absensi_masuk' => 'masuk.jpg',
            'foto_absensi_keluar' => 'keluar.jpg',
            'latitude_absen_masuk' => '1.111',
            'longitude_absen_masuk' => '1.111',
            'latitude_absen_keluar' => '1.111',
            'longitude_absen_keluar' => '1.111',
        ]);

        Absensi::create([
            'user_id' => 2,
            'tanggal' => $carbon->now()->subDays(6),
            'absensi_masuk' => $carbon->toTimeString(),
            'absensi_keluar' => $carbon->toTimeString(),
            'keterangan' => 'Absensi',
            'status' => 'tepat waktu',
            'foto_absensi_masuk' => 'masuk.jpg',
            'foto_absensi_keluar' => 'keluar.jpg',
            'latitude_absen_masuk' => '1.111',
            'longitude_absen_masuk' => '1.111',
            'latitude_absen_keluar' => '1.111',
            'longitude_absen_keluar' => '1.111',
        ]);

        Absensi::create([
            'user_id' => 2,
            'tanggal' => $carbon->now()->subDays(7),
            'absensi_masuk' => $carbon->toTimeString(),
            'absensi_keluar' => $carbon->toTimeString(),
            'keterangan' => 'Absensi',
            'status' => 'tepat waktu',
            'foto_absensi_masuk' => 'masuk.jpg',
            'foto_absensi_keluar' => 'keluar.jpg',
            'latitude_absen_masuk' => '1.111',
            'longitude_absen_masuk' => '1.111',
            'latitude_absen_keluar' => '1.111',
            'longitude_absen_keluar' => '1.111',
        ]);

        Absensi::create([
            'user_id' => 2,
            'tanggal' => $carbon->now()->subDays(8),
            'absensi_masuk' => $carbon->toTimeString(),
            'absensi_keluar' => $carbon->toTimeString(),
            'keterangan' => 'Absensi',
            'status' => 'tepat waktu',
            'foto_absensi_masuk' => 'masuk.jpg',
            'foto_absensi_keluar' => 'keluar.jpg',
            'latitude_absen_masuk' => '1.111',
            'longitude_absen_masuk' => '1.111',
            'latitude_absen_keluar' => '1.111',
            'longitude_absen_keluar' => '1.111',
        ]);

        Absensi::create([
            'user_id' => 2,
            'tanggal' => $carbon->now()->subDays(9),
            'absensi_masuk' => $carbon->toTimeString(),
            'absensi_keluar' => $carbon->toTimeString(),
            'keterangan' => 'Absensi',
            'status' => 'tepat waktu',
            'foto_absensi_masuk' => 'masuk.jpg',
            'foto_absensi_keluar' => 'keluar.jpg',
            'latitude_absen_masuk' => '1.111',
            'longitude_absen_masuk' => '1.111',
            'latitude_absen_keluar' => '1.111',
            'longitude_absen_keluar' => '1.111',
        ]);

        Absensi::create([
            'user_id' => 2,
            'tanggal' => $carbon->now()->subDays(10),
            'absensi_masuk' => $carbon->toTimeString(),
            'absensi_keluar' => $carbon->toTimeString(),
            'keterangan' => 'Absensi',
            'status' => 'tepat waktu',
            'foto_absensi_masuk' => 'masuk.jpg',
            'foto_absensi_keluar' => 'keluar.jpg',
            'latitude_absen_masuk' => '1.111',
            'longitude_absen_masuk' => '1.111',
            'latitude_absen_keluar' => '1.111',
            'longitude_absen_keluar' => '1.111',
        ]);
    }
}
