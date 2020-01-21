<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WaktuKerja;
use Illuminate\Support\Facades\DB;

class WaktuKerjaController extends Controller
{
    private $WaktuKerja;

    public function __construct()
    {
        $this->WaktuKerja = new WaktuKerja();
    }

    public function tambahWaktuKerja(Request $request)
    {
        $this->WaktuKerja->waktu_kerja = $request->waktu_kerja;
        $this->WaktuKerja->hari_kerja = $request->hari_kerja;
        $this->WaktuKerja->save();

        return response()->json(['status' => 200, 'message' => 'Berhasil menambah waktu kerja!', 'data' => $this->WaktuKerja]);
    }
}
