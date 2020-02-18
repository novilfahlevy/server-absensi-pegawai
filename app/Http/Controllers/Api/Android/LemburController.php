<?php

namespace App\Http\Controllers\Api\Android;

use App\Absensi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lembur;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class LemburController extends Controller
{
    private $lemburPath;

    public function __construct()
    {
        $this->lemburPath = public_path() . '/storage/lembur';
    }

    public function lembur(Request $request)
    {
        $check_absensi_today = Absensi::where('user_id', Auth::user()->id)->where('tanggal', Carbon::now()->toDateString())->first();

        $check_lembur = Lembur::where('user_id', Auth::user()->id)->where('absensi_id', $check_absensi_today['id'])->first();

        if ($check_absensi_today === null) {
            return response()->json(['status' => 400, 'message' => 'Anda belum absensi hari ini!'], 400);
        }

        if ($check_lembur !== null) {
            return response()->json(['status' => 400, 'message' => 'Anda sudah mengajukan lembur hari ini!'], 400);
        }

        if (!File::isDirectory($this->lemburPath)) {
            File::makeDirectory($this->lemburPath);
        }

        $input = $request->file('foto_lembur');
        $hashNameImage = time() . '_' . $input->getClientOriginalName();
        Image::make($input)->save($this->lemburPath . '/' . $hashNameImage);

        $lembur = new Lembur();
        $lembur->user_id = Auth::user()->id;
        $lembur->absensi_id = $check_absensi_today['id'];
        $lembur->tanggal = Carbon::now()->toDateString();
        $lembur->lembur_awal = $request->lembur_awal;
        $lembur->lembur_akhir = $request->lembur_akhir;
        $lembur->konsumsi = $request->konsumsi_lembur;
        $lembur->keterangan = $request->keterangan;
        $lembur->foto = $hashNameImage;
        $lembur->status = 'menunggu';
        $lembur->save();

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil lembur!. Mohon tunggu admin untuk mempersetujuinya.',
            'data' => [
                'foto' => url('/storage/lembur/' . $hashNameImage),
                'jam_mulai' => $lembur->lembur_awal,
                'jam_selesai' => $lembur->lembur_akhir,
                'konsumsi_lembur' => $lembur->konsumsi,
                'keterangan' => $request->keterangan
            ]
        ]);
    }

    public function riwayatLembur($user_id)
    {
        return response()->json(['status' => 200, 'message' => 'Berhasil mengambil riwayat lembur!', 'data' => Lembur::where('user_id', $user_id)->get()]);
    }

    public function detailLembur($user_id, $tanggal)
    {
        $lemburs = Lembur::where('user_id', $user_id)->where('tanggal', $tanggal)->get();

        if (count($lemburs) > 0) {
            foreach ($lemburs as $key => $lembur) {
                $data[$key] = [
                    'user_id' => $lembur->user_id,
                    'jam_mulai' => $lembur->lembur_awal,
                    'jam_selesai' => $lembur->lembur_akhir,
                    'konsumsi' => $lembur->konsumsi,
                    'keterangan' => $lembur->keterangan,
                    'status' => $lembur->status,
                    'tanggal' => Carbon::parse($lembur->tanggal)->translatedFormat('l, d F Y'),
                    'foto_lembur' => url('/storage/lembur/' . $lembur->foto)
                ];
            }
            return response()->json(['status' => 200, 'message' => 'Berhasil mengambil detail absensi!', 'data' => $data]);
        }
        return response()->json(['status' => 200, 'message' => 'Berhasil mengambil detail absensi!', 'data' => []]);
    }
}
