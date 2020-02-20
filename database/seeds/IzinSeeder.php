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
            'user_id' => 32,
            'tanggal_mulai' => Carbon::createFromDate(2020, $carbon->month, 12)->toDateString(),
            'tanggal_selesai' => Carbon::createFromDate(2020, $carbon->month, 25)->toDateString(),
            'alasan' => 'Liburan',
            'keterangan' => null,
            'izin_by' => 1
        ]);

        Izin::create([
            'user_id' => 35,
            'tanggal_mulai' => Carbon::createFromDate(2020, $carbon->month, 4)->toDateString(),
            'tanggal_selesai' => Carbon::createFromDate(2020, $carbon->month, 19)->toDateString(),
            'alasan' => 'Liburan',
            'keterangan' => null,
            'izin_by' => 1
        ]);
    }
}
