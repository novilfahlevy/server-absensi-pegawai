<?php

use App\IzinPerjam;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IzinPerjamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carbon = Carbon::now();

        IzinPerjam::create([
            'user_id' => 32,
            'tanggal' => Carbon::createFromDate(2020, $carbon->month, 20)->toDateString(),
            'jam_mulai' => Carbon::createFromTime(13, 0, 0)->toTimeString(),
            'jam_selesai' => Carbon::createFromTime(16, 30, 0)->toTimeString(),
            'alasan' => 'Sakit',
            'keterangan' => null,
            'izin_by' => 1
        ]);

        IzinPerjam::create([
            'user_id' => 42,
            'tanggal' => Carbon::createFromDate(2020, $carbon->month, 24)->toDateString(),
            'jam_mulai' => Carbon::createFromTime(9, 0, 0)->toTimeString(),
            'jam_selesai' => Carbon::createFromTime(14, 30, 0)->toTimeString(),
            'alasan' => 'Urusan diluar',
            'keterangan' => 'membuat KTP',
            'izin_by' => 1
        ]);
    }
}
