<?php

namespace App\Http\Controllers\Api;

use App\Absensi;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Lembur;

class LemburController extends Controller
{
    public function index()
    {
        $lates = Lembur::all();
        $latesStatusIsWaiting = Lembur::where('status', 'menunggu')->get();
        $latesStatusIsDeniedRejected = Lembur::where('status', '!=', 'menunggu')->get();
        foreach ($latesStatusIsWaiting as $key => $wait) {
            $latesStatusIsWaiting[$key]['name'] = User::select('name')->where('id', $wait->user_id)->get()->toArray();
            $latesStatusIsWaiting[$key]['name'] = $latesStatusIsWaiting[$key]['name'][0]['name'];
        }
        foreach ($latesStatusIsDeniedRejected as $key => $denied) {
            $latesStatusIsDeniedRejected[$key]['name'] = User::select('name')->where('id', $denied->user_id)->get()->toArray();
            $latesStatusIsDeniedRejected[$key]['name'] = $latesStatusIsDeniedRejected[$key]['name'][0]['name'];
        }
        return response()->json([
            'status' => 200, 'message' => 'Sukses', 'data' =>
            [
                'waiting' => $latesStatusIsWaiting,
                'others' => $latesStatusIsDeniedRejected
            ]
        ]);
    }

    public function create()
    {
        $carbon = new Carbon();
        $lembur = new Lembur();
        $check_absensi_today = Absensi::where('user_id', '=', Auth::user()->id)->where('tanggal', '=', $carbon->toDateString())->first();

        $check_lembur = Lembur::where('user_id', '=', Auth::user()->id)->where('absensi_id', '=', $check_absensi_today)->first();

        dd($check_lembur);

        if ($check_absensi_today === null) {
            return response()->json(['status' => 400, 'message' => 'Anda belum absensi hari ini!']);
        }

        if ($check_lembur > 1) {
            return response()->json(['status' => 400, 'message' => 'Anda sudah mengajukan lembur hari ini!']);
        }

        $lembur->user_id = Auth::user()->id;
        $lembur->absensi_id = $check_absensi_today['id'];
        $lembur->lembur_awal = $carbon->toTimeString();
        $lembur->lembur_akhir = $carbon->toTimeString();
        $lembur->konsumsi = 50000;
        $lembur->foto = 'lembur.jpg';
        $lembur->status = 'Menunggu';
        $lembur->save();

        return response()->json(['status' => 200, 'message' => 'Berhasil lembur!. Mohon tunggu admin untuk mempersetujuinya.']);
    }
}
