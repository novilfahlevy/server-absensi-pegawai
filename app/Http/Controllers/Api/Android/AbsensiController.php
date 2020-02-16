<?php

namespace App\Http\Controllers\Api\Android;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Absensi;
use App\Lembur;
use App\Http\Requests\AbsensiKeluarRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Requests\AbsensiMasukRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class AbsensiController extends Controller
{
    public function absensiMasuk(Request $request)
    {
        $check_duplicate_data = Absensi::where(['user_id' => Auth::user()->id, 'tanggal' => Carbon::now()->toDateString()])->count();

        if ($check_duplicate_data > 0) {
            return response()->json(['status' => 400, 'message' => 'Absensi masuk hanya boleh 1 kali!'], 400);
        }

        $imagePath = public_path() . '/storage/attendances_photo/';

        if (!File::isDirectory($imagePath)) {
            File::makeDirectory($imagePath);
        }

        if (Carbon::now()->format('H:i') >= '08:00' && Carbon::now()->format('H:i') <= '08:20') {
            $status = 'tepat waktu';
        } else if (Carbon::now()->format('H:i') <= '08:00') {
            $status = 'kecepatan';
        } else {
            $status = 'terlambat';
        }

        $input = $request->file('foto_absensi_masuk');
        $hashNameImage = time() . '_' . $input->getClientOriginalName();
        $canvas = Image::canvas(500, 500);
        $resizeImage = Image::make($input)->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
        });
        $canvas->insert($resizeImage, 'center');
        $canvas->save($imagePath . $hashNameImage);
        $path = '/storage/attendances_photo/' . $hashNameImage;

        $absensi = new Absensi();
        $absensi->user_id = Auth::user()->id;
        $absensi->tanggal = Carbon::now()->toDateString();
        $absensi->absensi_masuk = Carbon::now()->toTimeString();
        $absensi->keterangan = request('keterangan');
        $absensi->status = $status;
        $absensi->foto_absensi_masuk = $hashNameImage;
        $absensi->latitude_absen_masuk = request('latitude_absensi_masuk');
        $absensi->longitude_absen_masuk = request('longitude_absensi_masuk');
        $absensi->save();
        $absensi->tanggal = Carbon::parse($absensi->tanggal)->translatedFormat('l, d F Y');
        $absensi->url_foto_absensi_masuk = url($path);

        return response()->json(['status' => 200, 'message' => 'Berhasil absensi masuk!', 'data' => $absensi]);
    }

    public function cekAbsensi($user_id)
    {
        $absensi = Absensi::where('user_id', $user_id)->get(['absensi_masuk', 'absensi_keluar']);

        if (count($absensi) > 0) {

            if ($absensi[0]['absensi_masuk'] !== null) {
                $absensi[0]['absensi_masuk'] = true;
            } else {
                $absensi[0]['absensi_masuk'] = false;
            }

            if ($absensi[0]['absensi_keluar'] !== null) {
                $absensi[0]['absensi_keluar'] = true;
            } else {
                $absensi[0]['absensi_keluar'] = false;
            }

            return response()->json(['status' => 200, 'message' => 'Berhasil mengecek absensi!', 'data' => $absensi]);
        }
        return response()->json(['status' => 400, 'message' => 'Gagal mengecek absensi!'], 400);
    }

    public function getRiwayatAbsensi($user_id)
    {
        $absensi = Absensi::where('user_id', $user_id)->get();

        foreach ($absensi as $key => $absen) {
            $data[$key] = [
                'user_id' => $absen->user_id,
                'absensi_masuk' => $absen->absensi_masuk,
                'absensi_keluar' => $absen->absensi_keluar,
                'tanggal' => Carbon::parse($absen->tanggal)->translatedFormat('l, d F Y'),
                'foto_absensi_masuk' => url('storage/attendances_photo/' . $absen->foto_absensi_masuk),
                'foto_absensi_keluar' => url('storage/attendances_photo/' . $absen->foto_absensi_keluar),
            ];
        }
        return response()->json(['status' => 200, 'message' => 'Berhasil mengambil riwayat absensi!', 'data' => $data]);
    }

    public function getDetailAbsensiTodayDate($user_id)
    {
        $absensi = Absensi::where('user_id', $user_id)->where('tanggal', Carbon::now()->toDateString())->get();

        if (count($absensi) > 0) {
            foreach ($absensi as $key => $absen) {
                $data[$key] = [
                    'user_id' => $absen->user_id,
                    'absensi_masuk' => $absen->absensi_masuk,
                    'absensi_keluar' => $absen->absensi_keluar,
                    'tanggal' => Carbon::parse($absen->tanggal)->translatedFormat('l, d F Y'),
                    'foto_absensi_masuk' => url('storage/attendances_photo/' . $absen->foto_absensi_masuk),
                    'foto_absensi_keluar' => url('storage/attendances_photo/' . $absen->foto_absensi_keluar),
                ];
            }
            return response()->json(['status' => 200, 'message' => 'Berhasil mengambil riwayat absensi!', 'data' => $data]);
        }
    }

    public function getDetailAbsensi($user_id, $tanggal)
    {
        $absensi = Absensi::where('user_id', $user_id)->where('tanggal', $tanggal)->get();

        if (count($absensi) > 0) {
            foreach ($absensi as $key => $absen) {
                $data[$key] = [
                    'user_id' => $absen->user_id,
                    'absensi_masuk' => $absen->absensi_masuk,
                    'absensi_keluar' => $absen->absensi_keluar,
                    'tanggal' => Carbon::parse($absen->tanggal)->translatedFormat('l, d F Y'),
                    'foto_absensi_masuk' => url('storage/attendances_photo/' . $absen->foto_absensi_masuk),
                    'foto_absensi_keluar' => url('storage/attendances_photo/' . $absen->foto_absensi_keluar),
                ];
            }
            return response()->json(['status' => 200, 'message' => 'Berhasil mengambil riwayat absensi!', 'data' => $data]);
        }
    }
}
