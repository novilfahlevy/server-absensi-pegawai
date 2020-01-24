<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Lembur;

class LemburSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carbon = new Carbon();

        Lembur::create([
            'user_id' => 2,
            'absensi_id' => 3,
            'tanggal' => $carbon->now()->subDays(1),
            'lembur_awal' => $carbon->now()->subHour(2),
            'lembur_akhir' => $carbon->now()->subHour(7),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 3,
            'absensi_id' => 3,
            'tanggal' => $carbon->now()->subDays(2),
            'lembur_awal' => $carbon->now()->subHour(3),
            'lembur_akhir' => $carbon->now()->subHour(8),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 2,
            'absensi_id' => 3,
            'tanggal' => $carbon->now()->subDays(1),
            'lembur_awal' => $carbon->now()->subHour(4),
            'lembur_akhir' => $carbon->now()->subHour(9),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 3,
            'absensi_id' => 3,
            'tanggal' => $carbon->now()->subDays(1),
            'lembur_awal' => $carbon->now()->subHour(5),
            'lembur_akhir' => $carbon->now()->subHour(10),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 2,
            'absensi_id' => 3,
            'tanggal' => $carbon->now()->subDays(1),
            'lembur_awal' => $carbon->now()->subHour(6),
            'lembur_akhir' => $carbon->now()->subHour(11),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 3,
            'absensi_id' => 3,
            'tanggal' => $carbon->now()->subDays(1),
            'lembur_awal' => $carbon->now()->subHour(7),
            'lembur_akhir' => $carbon->now()->subHour(12),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 2,
            'absensi_id' => 3,
            'tanggal' => $carbon->now()->subDays(1),
            'lembur_awal' => $carbon->now()->subHour(28),
            'lembur_akhir' => $carbon->now()->subHour(13),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);
    }
}
