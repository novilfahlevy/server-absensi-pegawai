<?php

namespace App\Exports;

use App\Absensi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanViewExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        
        return view('laporan', [
            'absensis' => Absensi::all()
        ]);
    }
}
