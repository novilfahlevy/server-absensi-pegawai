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
            'tanggal' => $carbon->now()->subDays(1)->format('d F Y'),
            'lembur_awal' => $carbon->now()->subHour(7)->format('H:i'),
            'lembur_akhir' => $carbon->now()->subHour(2)->format('H:i'),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 3,
            'absensi_id' => 3,
            'tanggal' => $carbon->now()->subDays(2)->format('d F Y'),
            'lembur_awal' => $carbon->now()->subHour(8)->format('H:i'),
            'lembur_akhir' => $carbon->now()->subHour(3)->format('H:i'),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 2,
            'absensi_id' => 3,
            'tanggal' => $carbon->now()->subDays(3)->format('d F Y'),
            'lembur_awal' => $carbon->now()->subHour(9)->format('H:i'),
            'lembur_akhir' => $carbon->now()->subHour(4)->format('H:i'),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 3,
            'absensi_id' => 3,
            'tanggal' => $carbon->now()->subDays(4)->format('d F Y'),
            'lembur_awal' => $carbon->now()->subHour(10)->format('H:i'),
            'lembur_akhir' => $carbon->now()->subHour(5)->format('H:i'),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 2,
            'absensi_id' => 3,
            'tanggal' => $carbon->now()->subDays(5)->format('d F Y'),
            'lembur_awal' => $carbon->now()->subHour(11)->format('H:i'),
            'lembur_akhir' => $carbon->now()->subHour(6)->format('H:i'),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 3,
            'absensi_id' => 3,
            'tanggal' => $carbon->now()->subDays(6)->format('d F Y'),
            'lembur_awal' => $carbon->now()->subHour(12)->format('H:i'),
            'lembur_akhir' => $carbon->now()->subHour(7)->format('H:i'),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 2,
            'absensi_id' => 3,
            'tanggal' => $carbon->now()->subDays(1)->format('d F Y'),
            'lembur_awal' => $carbon->now()->subHour(13)->format('H:i'),
            'lembur_akhir' => $carbon->now()->subHour(8)->format('H:i'),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);
    }
}
