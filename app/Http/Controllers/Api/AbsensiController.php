<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Absensi;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    protected $carbon;
    protected $attendance;

    public function __construct()
    {
        $this->carbon = new Carbon();
        $this->absensi = new Absensi();
    }

    public function absensiMasuk()
    {
        $check_duplicate_data = Absensi::where(['user_id' => Auth::user()->id, 'tanggal' => $this->carbon->toDateString()])->count();

        if ($check_duplicate_data > 0) {
            return response()->json(['message' => 'Anda sudah absensi masuk!']);
        }

        $this->absensi->user_id = Auth::user()->id;
        $this->absensi->tanggal = $this->carbon->toDateString();
        $this->absensi->absensi_masuk = $this->carbon->toTimeString();
        $this->absensi->keterangan = request('keterangan');
        $this->absensi->foto_bukti = request('foto_bukti');
        $this->absensi->latitude = '1.111';
        $this->absensi->longitude = '1.111';
        $this->absensi->save();

        return response()->json(['status' => 200, 'message' => 'Berhasil absensi masuk', 'data' => $this->absensi]);
    }
}
