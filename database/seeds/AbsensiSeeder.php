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
            'tanggal' => $carbon->now()->subDays(1)->format('d F Y'),
            'absensi_masuk' => $carbon->now()->subHour(3)->format('H:i'),
            'absensi_keluar' => $carbon->now()->subHour(1)->format('H:i'),
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
            'tanggal' => $carbon->now()->subDays(2)->format('d F Y'),
            'absensi_masuk' => $carbon->now()->subHour(4)->format('H:i'),
            'absensi_keluar' => $carbon->now()->subHour(2)->format('H:i'),
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
            'tanggal' => $carbon->now()->subDays(3)->format('d F Y'),
            'absensi_masuk' => $carbon->now()->subHour(5)->format('H:i'),
            'absensi_keluar' => $carbon->now()->subHour(3)->format('H:i'),
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
            'tanggal' => $carbon->now()->subDays(4)->format('d F Y'),
            'absensi_masuk' => $carbon->now()->subHour(6)->format('H:i'),
            'absensi_keluar' => $carbon->now()->subHour(4)->format('H:i'),
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
            'tanggal' => $carbon->now()->subDays(5)->format('d F Y'),
            'absensi_masuk' => $carbon->now()->subHour(7)->format('H:i'),
            'absensi_keluar' => $carbon->now()->subHour(5)->format('H:i'),
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
            'tanggal' => $carbon->now()->subDays(6)->format('d F Y'),
            'absensi_masuk' => $carbon->now()->subHour(8)->format('H:i'),
            'absensi_keluar' => $carbon->now()->subHour(6)->format('H:i'),
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
            'tanggal' => $carbon->now()->subDays(7)->format('d F Y'),
            'absensi_masuk' => $carbon->now()->subHour(9)->format('H:i'),
            'absensi_keluar' => $carbon->now()->subHour(7)->format('H:i'),
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
            'tanggal' => $carbon->now()->subDays(8)->format('d F Y'),
            'absensi_masuk' => $carbon->now()->subHour(10)->format('H:i'),
            'absensi_keluar' => $carbon->now()->subHour(8)->format('H:i'),
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
            'tanggal' => $carbon->now()->subDays(9)->format('d F Y'),
            'absensi_masuk' => $carbon->now()->subHour(11)->format('H:i'),
            'absensi_keluar' => $carbon->now()->subHour(9)->format('H:i'),
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
            'tanggal' => $carbon->now()->subDays(10)->format('d F Y'),
            'absensi_masuk' => $carbon->now()->subHour(12)->format('H:i'),
            'absensi_keluar' => $carbon->now()->subHour(10)->format('H:i'),
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
