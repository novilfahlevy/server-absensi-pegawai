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
        $carbon = Carbon::now();

        Izin::create([
            'user_id' => 18,
            'tanggal_mulai' => Carbon::createFromDate($carbon->year, $carbon->month, 12)->toDateString(),
            'tanggal_selesai' => Carbon::createFromDate($carbon->year, $carbon->month, 25)->toDateString(),
            'alasan' => 'Liburan',
            'keterangan' => null,
            'izin_by' => 1
        ]);
    }
}
