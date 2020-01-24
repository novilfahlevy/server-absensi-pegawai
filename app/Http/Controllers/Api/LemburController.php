<?php

namespace App\Http\Controllers\Api;

use App\Absensi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Lembur;
use App\User;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class LemburController extends Controller
{
    protected $imagePath;

    public function __construct()
    {
        $this->imagePath = public_path() . '/storage/lembur/';
    }

    public function index()
    {
        return response()->json([
            'status' => 200, 'message' => 'Sukses', 'data' =>
            [
                [
                    'user_id' => 1,
                    'absensi_id' => 2,
                    'lembur_awal' => '17:00:00',
                    'lembur_akhir' => '12:00:00',
                    'konsumsi' => 100000,
                    'keterangan' => 'lembur',
                    'foto' => 'lembur.jpg',
                    'status' => 'Menunggu'
                ],
                [
                    'user_id' => 2,
                    'absensi_id' => 3,
                    'lembur_awal' => '17:00:00',
                    'lembur_akhir' => '11:00:00',
                    'konsumsi' => 120000,
                    'keterangan' => 'lembur',
                    'foto' => 'lembur.jpg',
                    'status' => 'Menunggu'
                ],
                [
                    'user_id' => 3,
                    'absensi_id' => 5,
                    'lembur_awal' => '18:00:00',
                    'lembur_akhir' => '11:00:00',
                    'konsumsi' => 190000,
                    'keterangan' => 'lembur',
                    'foto' => 'lembur.jpg',
                    'status' => 'Disetujui'
                ],
                [
                    'user_id' => 4,
                    'absensi_id' => 8,
                    'lembur_awal' => '15:00:00',
                    'lembur_akhir' => '04:00:00',
                    'konsumsi' => 120000,
                    'foto' => 'lembur.jpg',
                    'status' => 'Ditolak'
                ]
            ]
        ]);
    }

    public function create(Request $request)
    {
        $carbon = new Carbon();
        $lembur = new Lembur();
        $check_absensi_today = Absensi::where('user_id', '=', Auth::user()->id)->where('tanggal', '=', $carbon->toDateString())->first();

        $check_lembur = Lembur::where('user_id', '=', Auth::user()->id)->where('absensi_id', '=', $check_absensi_today['id'])->first();

        if ($check_absensi_today === null) {
            return response()->json(['status' => 400, 'message' => 'Anda belum absensi hari ini!']);
        }

        if ($check_lembur !== null) {
            return response()->json(['status' => 400, 'message' => 'Anda sudah mengajukan lembur hari ini!']);
        }

        if (!File::isDirectory($this->imagePath)) {
            File::makeDirectory($this->imagePath);
        }

        $input = $request->file('foto');
        $hashNameImage = time() . '_' . $input->getClientOriginalName();
        $canvas = Image::canvas(500, 500);
        $resizeImage = Image::make($input)->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
        });
        $canvas->insert($resizeImage, 'center');
        $canvas->save($this->imagePath . '/' . $hashNameImage);

        $lembur->user_id = Auth::user()->id;
        $lembur->absensi_id = $check_absensi_today['id'];
        $lembur->tanggal = $carbon->toDateString();
        $lembur->lembur_awal = $carbon->toTimeString();
        $lembur->lembur_akhir = $carbon->toTimeString();
        $lembur->konsumsi = 50000;
        $lembur->keterangan = 'Lembur';
        $lembur->foto = $hashNameImage;
        $lembur->status = 'Menunggu';
        $lembur->save();

        return response()->json(['status' => 200, 'message' => 'Berhasil lembur!. Mohon tunggu admin untuk mempersetujuinya.']);
    }

    public function edit(Request $request, $id)
    {
        Lembur::where('id', '=', $id)->update(['status' => $request->status]);

        return response()->json(['status' => 200, 'message' => 'Berhasil update status lembur!']);
    }

    public function show($id)
    {
        $detail_lembur = Lembur::findOrFail($id);
        $detail_lembur->user->name;


        return response()->json(['status' => 200, 'data' => [
            'detail_lembur' => $detail_lembur
        ]]);
    }
}
