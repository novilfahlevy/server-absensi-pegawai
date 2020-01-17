<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Absensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Requests\AbsensiMasukRequest;

class AbsensiController extends Controller
{
    protected $carbon;
    protected $attendance;
    protected $imagePath;

    public function __construct()
    {
        $this->carbon = new Carbon();
        $this->absensi = new Absensi();
        $this->imagePath = public_path() . '/storage/attendances_photo/';
    }

    public function absensiMasuk(AbsensiMasukRequest $request)
    {
        $check_duplicate_data = Absensi::where(['user_id' => Auth::user()->id, 'tanggal' => $this->carbon->toDateString()])->count();

        if ($check_duplicate_data > 0) {
            return response()->json(['message' => 'Anda sudah absensi masuk!']);
        }

        if (!File::isDirectory($this->imagePath)) {
            File::makeDirectory($this->imagePath);
        }

        $input = $request->file('foto_absensi_masuk');
        $hashNameImage = time() . '_' . $input->getClientOriginalName();
        $canvas = Image::canvas(500, 500);
        $resizeImage = Image::make($input)->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
        });
        $canvas->insert($resizeImage, 'center');
        $canvas->save($this->imagePath . '/' . $hashNameImage);

        $this->absensi->user_id = Auth::user()->id;
        $this->absensi->tanggal = $this->carbon->toDateString();
        $this->absensi->absensi_masuk = $this->carbon->toTimeString();
        $this->absensi->keterangan = request('keterangan');
        $this->absensi->foto_absensi_masuk = $hashNameImage;
        $this->absensi->latitude = '1.111';
        $this->absensi->longitude = '1.111';
        $this->absensi->save();

        return response()->json(['status' => 200, 'message' => 'Berhasil absensi masuk', 'data' => $this->absensi]);
    }
}
