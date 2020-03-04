<?php

use App\Izin;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Izin::create([
            'user_id' => 13,
            'tanggal_mulai' => Carbon::now()->addDays(3)->toDateString(),
            'tanggal_selesai' => Carbon::now()->addDays(6)->toDateString(),
            'alasan' => 'Liburan',
            'keterangan' => null,
            'izin_by' => 1
        ]);
    }
}
