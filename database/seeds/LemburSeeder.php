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
        $status_data = ['diterima', 'menunggu', 'ditolak'];

        for ($i = 1; $i <= 12; $i++) {
            Lembur::create([
                'user_id' => rand(4, 19),
                'absensi_id' => rand(1, 23),
                'tanggal' => $carbon->createFromDate(2020, rand(2, 12), rand(8, 32))->toDateString(),
                'lembur_awal' => $carbon->createFromTime(rand(8, 12), rand(1, 59), rand(1, 59))->toTimeString(),
                'lembur_akhir' => $carbon->createFromTime(rand(15, 17), rand(1, 59), rand(1, 59))->toTimeString(),
                'keterangan' => 'Lembur',
                'konsumsi' => random_int(50000, 100000),
                'foto' => uniqid() . '_' . 'lembur.jpg',
                'status' => array_random($status_data)
            ]);
        }
    }
}
