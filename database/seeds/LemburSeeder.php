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
            'user_id' => 3,
            'absensi_id' => 2,
            'tanggal' => $carbon->now()->subDays(1)->toDateString(),
            'lembur_awal' => $carbon->now()->subHour(7)->toTimeString(),
            'lembur_akhir' => $carbon->now()->subHour(2)->toTimeString(),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 3,
            'absensi_id' => 3,
            'tanggal' => $carbon->now()->subDays(2)->toDateString(),
            'lembur_awal' => $carbon->now()->subHour(8)->toTimeString(),
            'lembur_akhir' => $carbon->now()->subHour(3)->toTimeString(),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 3,
            'absensi_id' => 4,
            'tanggal' => $carbon->now()->subDays(3)->toDateString(),
            'lembur_awal' => $carbon->now()->subHour(9)->toTimeString(),
            'lembur_akhir' => $carbon->now()->subHour(4)->toTimeString(),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 3,
            'absensi_id' => 5,
            'tanggal' => $carbon->now()->subDays(4)->toDateString(),
            'lembur_awal' => $carbon->now()->subHour(10)->toTimeString(),
            'lembur_akhir' => $carbon->now()->subHour(5)->toTimeString(),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 2,
            'absensi_id' => 6,
            'tanggal' => $carbon->now()->subDays(5)->toDateString(),
            'lembur_awal' => $carbon->now()->subHour(11)->toTimeString(),
            'lembur_akhir' => $carbon->now()->subHour(6)->toTimeString(),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 3,
            'absensi_id' => 7,
            'tanggal' => $carbon->now()->subDays(6)->toDateString(),
            'lembur_awal' => $carbon->now()->subHour(12)->toTimeString(),
            'lembur_akhir' => $carbon->now()->subHour(7)->toTimeString(),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 2,
            'absensi_id' => 13,
            'tanggal' => $carbon->now()->subDays(7)->toDateString(),
            'lembur_awal' => $carbon->now()->subHour(13)->toTimeString(),
            'lembur_akhir' => $carbon->now()->subHour(8)->toTimeString(),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);

        Lembur::create([
            'user_id' => 4,
            'absensi_id' => 15,
            'tanggal' => $carbon->now()->subDays(8)->toDateString(),
            'lembur_awal' => $carbon->now()->subHour(14)->toTimeString(),
            'lembur_akhir' => $carbon->now()->subHour(9)->toTimeString(),
            'keterangan' => 'Lembur',
            'konsumsi' => 100000,
            'foto' => 'lembur.jpg',
            'status' => 'menunggu'
        ]);
    }
}
