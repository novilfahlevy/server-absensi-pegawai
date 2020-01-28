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
            'absensi_masuk' => $carbon->now()->subHour(3)->toTimeString(),
            'absensi_keluar' => $carbon->now()->subHour(1)->toTimeString(),
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
            'user_id' => 3,
            'tanggal' => $carbon->now()->subDays(2)->toDateString(),
            'absensi_masuk' => $carbon->now()->subHour(4)->toTimeString(),
            'absensi_keluar' => $carbon->now()->subHour(2)->toTimeString(),
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
            'user_id' => 3,
            'tanggal' => $carbon->now()->subDays(3)->toDateString(),
            'absensi_masuk' => $carbon->now()->subHour(5)->toTimeString(),
            'absensi_keluar' => $carbon->now()->subHour(3)->toTimeString(),
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
            'user_id' => 3,
            'tanggal' => $carbon->now()->subDays(4)->toDateString(),
            'absensi_masuk' => $carbon->now()->subHour(6)->toTimeString(),
            'absensi_keluar' => $carbon->now()->subHour(4)->toTimeString(),
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
            'user_id' => 3,
            'tanggal' => $carbon->now()->subDays(5)->toDateString(),
            'absensi_masuk' => $carbon->now()->subHour(7)->toTimeString(),
            'absensi_keluar' => $carbon->now()->subHour(5)->toTimeString(),
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
            'tanggal' => $carbon->now()->subDays(6)->toDateString(),
            'absensi_masuk' => $carbon->now()->subHour(8)->toTimeString(),
            'absensi_keluar' => $carbon->now()->subHour(6)->toTimeString(),
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
            'user_id' => 3,
            'tanggal' => $carbon->now()->subDays(7)->toDateString(),
            'absensi_masuk' => $carbon->now()->subHour(9)->toTimeString(),
            'absensi_keluar' => $carbon->now()->subHour(7)->toTimeString(),
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
            'tanggal' => $carbon->now()->subDays(8)->toDateString(),
            'absensi_masuk' => $carbon->now()->subHour(10)->toTimeString(),
            'absensi_keluar' => $carbon->now()->subHour(8)->toTimeString(),
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
            'user_id' => 3,
            'tanggal' => $carbon->now()->subDays(9)->toDateString(),
            'absensi_masuk' => $carbon->now()->subHour(11)->toTimeString(),
            'absensi_keluar' => $carbon->now()->subHour(9)->toTimeString(),
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
            'tanggal' => $carbon->now()->subDays(10)->toDateString(),
            'absensi_masuk' => $carbon->now()->subHour(12)->toTimeString(),
            'absensi_keluar' => $carbon->now()->subHour(10)->toTimeString(),
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
            'user_id' => 3,
            'tanggal' => $carbon->now()->subDays(11)->toDateString(),
            'absensi_masuk' => $carbon->now()->subHour(13)->toTimeString(),
            'absensi_keluar' => $carbon->now()->subHour(11)->toTimeString(),
            'keterangan' => 'Absensi',
            'status' => 'terlambat',
            'foto_absensi_masuk' => 'masuk.jpg',
            'foto_absensi_keluar' => 'keluar.jpg',
            'latitude_absen_masuk' => '1.111',
            'longitude_absen_masuk' => '1.111',
            'latitude_absen_keluar' => '1.111',
            'longitude_absen_keluar' => '1.111',
        ]);

        Absensi::create([
            'user_id' => 3,
            'tanggal' => $carbon->now()->subDays(12)->toDateString(),
            'absensi_masuk' => $carbon->now()->subHour(13)->toTimeString(),
            'absensi_keluar' => $carbon->now()->subHour(11)->toTimeString(),
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
            'tanggal' => $carbon->now()->subDays(13)->toDateString(),
            'absensi_masuk' => $carbon->now()->subHour(13)->toTimeString(),
            'absensi_keluar' => $carbon->now()->subHour(11)->toTimeString(),
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
            'user_id' => 3,
            'tanggal' => $carbon->now()->subDays(14)->toDateString(),
            'absensi_masuk' => $carbon->now()->subHour(14)->toTimeString(),
            'absensi_keluar' => $carbon->now()->subHour(12)->toTimeString(),
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
