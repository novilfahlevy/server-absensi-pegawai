<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Absensi;
use App\Lembur;
use App\Http\Requests\AbsensiKeluarRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Requests\AbsensiMasukRequest;
use App\User;
use Illuminate\Support\Facades\DB;

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

    public function index()
    {
        $absensi = Absensi::where('tanggal', Carbon::now()->toDateString())->get();
        // $absensi = Absensi::all();
        foreach ($absensi as $key => $absen) {
            $absensi[$key]['name'] = $absen->user->name;
        }
        return response()->json(['status' => 200, 'message' => 'Sukses', 'absensi' => $absensi]);
    }

    public function show($id)
    {
        if ($absensi = Absensi::find($id)) {
            $lembur = Lembur::where('absensi_id', $absensi->id)->where('status', 'diterima')->first();
            $absensi->lembur = $lembur ? $lembur->id : null;
            return response()->json([
                'status' => 200,
                'absensi' => $absensi
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'Data tidak ditemukan'
        ]);
    }

    public function cari($keyword)
    {
        $users = User::where('name', 'LIKE', '%' . $keyword . '%')->get()->pluck('id')->toArray();

        $absensi = [];
        foreach ($users as $user_id) {
            $absensi[] = Absensi::where('user_id', '=', $user_id)->where('tanggal', Carbon::now()->toDateString())->get();
        }

        if (isset($absensi[0])) {
            $absensi = $absensi[0];

            foreach ($absensi as $key => $absen) {
                $absensi[$key]['name'] = User::find($absen->user_id)->name;
            }
            return response()->json(['status' => 200, 'absensi' => $absensi]);
        }
        return response()->json(['status' => 404, 'absensi' => []]);
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
        $this->absensi->status = 'tepat waktu';
        $this->absensi->foto_absensi_masuk = $hashNameImage;
        $this->absensi->latitude_absen_masuk = (float) -34.397;
        $this->absensi->longitude_absen_masuk = (float) 150.644;
        $this->absensi->save();

        return response()->json(['status' => 200, 'message' => 'Berhasil absensi masuk!', 'data' => $this->absensi]);
    }

    public function absensiKeluar(AbsensiKeluarRequest $request)
    {
        if (!File::isDirectory($this->imagePath)) {
            File::makeDirectory($this->imagePath);
        }

        $check_attendance_in = Absensi::where('user_id', '=', Auth::user()->id)->where('tanggal', '=', $this->carbon->toDateString())->get();

        if ($check_attendance_in->isEmpty()) {
            return response()->json(['message' => 'Anda belum absensi masuk!']);
        } else {
            $check_attendance_out = Absensi::where('user_id', '=', Auth::user()->id)->where('tanggal', '=', $this->carbon->toDateString())->where('absensi_keluar', '!=', null)->get();

            if (!$check_attendance_out->isEmpty()) {
                return response()->json(['message' => 'Anda sudah absensi keluar!']);
            }

            $input = $request->file('foto_absensi_keluar');
            $hashNameImage = time() . '_' . $input->getClientOriginalName();
            $canvas = Image::canvas(500, 500);
            $resizeImage = Image::make($input)->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            });
            $canvas->insert($resizeImage, 'center');
            $canvas->save($this->imagePath . '/' . $hashNameImage);

            $this->absensi->where(['user_id' => Auth::user()->id, 'tanggal' => $this->carbon->toDateString()])->update(['absensi_keluar' => $this->carbon->toTimeString(), 'foto_absensi_keluar' => $hashNameImage, 'keterangan' => request('keterangan'), 'latitude_absen_keluar' => (float) -34.397, 'longitude_absen_keluar' => (float) 150.644]);

            $data = Absensi::where(['user_id' => Auth::user()->id, 'tanggal' => $this->carbon->toDateString()])->first();

            return response()->json(['status' => 200, 'message' => 'Berhasil absensi keluar!', 'data' => $data]);
        }
    }

    public function history() {
        return Absensi::where('tanggal', '!=', Carbon::now()->toDateString());
    }

    public function absensiHistory() {
        $history = $this->history()->get()->sortByDesc('tanggal')->values();

        foreach ( $history as $i => $h ) {
            $history[$i]['name'] = User::find($h->user_id)->name;
        }

        return response()->json(['status' => 200, 'data' => $history]);
    }

    public function searchHistory($name) {
        $users = User::where('name', 'LIKE', "%$name%")->get();

        $absensi = [];
        $history = $this->history();
        foreach ( $history->get() as $absen ) {
            if ( $history->count() ) {
                foreach ( $users as $user ) {
                    if ( $user->id === $absen->user_id ) {
                        $userAbsen = $absen;
                        $userAbsen['name'] = User::find($absen->user_id)->name;
                        $absensi[] = $userAbsen;
                    }
                }
            }
        }

        return response()->json([
            'status' => 200, 
            'data' => $absensi
        ]);
    }

    public function getAvailableAbsenYears() {
        return response()->json([
            'status' => 200, 
            'data' => collect(DB::select(
                DB::raw("SELECT DISTINCT YEAR(tanggal) AS tahun FROM absensis ORDER BY tahun DESC")
            ))->map(function ($year) { return $year->tahun; })
        ]);
    }

    public function filterHistory($year, $month) {
        $query = "SELECT * FROM absensis WHERE MONTH(tanggal) = $month AND YEAR(tanggal) = $year";

        if ( $year === 'all' ) {
            $query = "SELECT * FROM absensis WHERE MONTH(tanggal) = $month";
        }

        if ( $month === 'all' ) {
            $query = "SELECT * FROM absensis WHERE YEAR(tanggal) = $year";
        }

        if ( $month === 'all' && $year === 'all' ) {
            $query = "SELECT * FROM absensis";
        }

        $absensi = [];
        foreach ( DB::select(DB::raw($query)) as $absen ) {
            $userAbsen = $absen;
            $userAbsen->name = User::find($absen->user_id)->name;
            $absensi[] = $userAbsen;
        }

        return response()->json([
            'status' => 200, 
            'data' => $absensi
        ]);
    }
}
