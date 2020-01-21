<?php

use Illuminate\Database\Seeder;
use App\WaktuKerja;

class WaktuKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WaktuKerja::create([
            'waktu_kerja' => 8,
            'hari_kerja' => "Senin, Selasa, Rabu, Kamis, Jum'at, Sabtu"
        ]);
    }
}
