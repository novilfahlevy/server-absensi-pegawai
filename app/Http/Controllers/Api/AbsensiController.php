<?php

namespace App\Http\Controllers\Api;

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
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

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
        ], 404);
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

    // public function absensiMasuk(AbsensiMasukRequest $request)
    // {
    //     $check_duplicate_data = Absensi::where(['user_id' => Auth::user()->id, 'tanggal' => $this->carbon->toDateString()])->count();

    //     if ($check_duplicate_data > 0) {
    //         return response()->json(['status' => 400, 'message' => 'Absensi masuk hanya boleh 1 kali!'], 400);
    //     }

    //     if (!File::isDirectory($this->imagePath)) {
    //         File::makeDirectory($this->imagePath);
    //     }

    //     // if ($this->carbon->now()->format('H') === 8) {
    //     //     $status = 'tepat waktu';
    //     // } else if ($this->carbon->now()->format('H') < 8) {
    //     //     $status = 'kecepatan';
    //     // } else {
    //     //     $status = 'terlambat';
    //     // }

    //     $input = $request->file('foto_absensi_masuk');
    //     $hashNameImage = time() . '_' . $input->getClientOriginalName();
    //     $canvas = Image::canvas(500, 500);
    //     $resizeImage = Image::make($input)->resize(500, 500, function ($constraint) {
    //         $constraint->aspectRatio();
    //     });
    //     $canvas->insert($resizeImage, 'center');
    //     $canvas->save($this->imagePath . '/' . $hashNameImage);
    //     $path = '/storage/attendances_photo/' . $hashNameImage;

    //     $this->absensi->user_id = Auth::user()->id;
    //     $this->absensi->tanggal = $this->carbon->toDateString();
    //     $this->absensi->absensi_masuk = $this->carbon->toTimeString();
    //     $this->absensi->keterangan = request('keterangan');
    //     $this->absensi->status = 'tepat waktu';
    //     $this->absensi->foto_absensi_masuk = $hashNameImage;
    //     $this->absensi->latitude_absen_masuk = request('latitude_absensi_masuk');
    //     $this->absensi->longitude_absen_masuk = request('longitude_absensi_masuk');
    //     $this->absensi->save();
    //     $this->absensi->tanggal = Carbon::parse($this->absensi->tanggal)->translatedFormat('l, d F Y');
    //     $this->absensi->url_absensi_masuk = url($path);

    //     return response()->json(['status' => 200, 'message' => 'Berhasil absensi masuk!', 'data' => $this->absensi]);
    // }

    // public function absensiKeluar(AbsensiKeluarRequest $request)
    // {
    //     if (!File::isDirectory($this->imagePath)) {
    //         File::makeDirectory($this->imagePath);
    //     }

    //     $check_attendance_in = Absensi::where('user_id', '=', Auth::user()->id)->where('tanggal', '=', $this->carbon->toDateString())->get();

    //     if ($check_attendance_in->isEmpty()) {
    //         return response()->json(['status' => 400, 'message' => 'Anda belum absensi masuk!'], 400);
    //     } else {
    //         $check_attendance_out = Absensi::where('user_id', '=', Auth::user()->id)->where('tanggal', '=', $this->carbon->toDateString())->where('absensi_keluar', '!=', null)->get();

    //         if (!$check_attendance_out->isEmpty()) {
    //             return response()->json(['status' => 400, 'message' => 'Absensi masuk hanya boleh 1 kali!'], 400);
    //         }

    //         $input = $request->file('foto_absensi_keluar');
    //         $hashNameImage = time() . '_' . $input->getClientOriginalName();
    //         $canvas = Image::canvas(500, 500);
    //         $resizeImage = Image::make($input)->resize(500, 500, function ($constraint) {
    //             $constraint->aspectRatio();
    //         });
    //         $canvas->insert($resizeImage, 'center');
    //         $canvas->save($this->imagePath . '/' . $hashNameImage);
    //         $path = $this->imagePath . '/' . $hashNameImage;

    //         $this->absensi->where(['user_id' => Auth::user()->id, 'tanggal' => $this->carbon->toDateString()])->update(['absensi_keluar' => $this->carbon->toTimeString(), 'foto_absensi_keluar' => $hashNameImage, 'keterangan' => request('keterangan'), 'latitude_absen_keluar' => request('latitude_absensi_keluar'), 'longitude_absen_keluar' => request('longitude_absensi_keluar')]);

    //         $data = Absensi::where(['user_id' => Auth::user()->id, 'tanggal' => $this->carbon->toDateString()])->first();
    //         $res = [
    //             'id' => $data->id,
    //             'user_id' => $data->user_id,
    //             'tanggal' => Carbon::parse($data->tanggal)->translatedFormat('l, d F Y'),
    //             'absensi_masuk' => $data->absensi_masuk,
    //             'absensi_keluar' => $data->absensi_keluar,
    //             'keterangan' => $data->keterangan,
    //             'status' => $data->status,
    //             'foto_absensi_masuk' => $data->foto_absensi_masuk,
    //             'foto_absensi_keluar' => $data->foto_absensi_keluar,
    //             'latitude_absen_masuk' => $data->latitude_absen_masuk,
    //             'longitude_absen_masuk' => $data->longitude_absen_masuk,
    //             'latitude_absen_keluar' => $data->latitude_absen_keluar,
    //             'longitude_absen_keluar' => $data->longitude_absen_keluar,
    //             'created_at' => $data->created_at,
    //             'updated_at' => $data->updated_at,
    //         ];

    //         return response()->json(['status' => 200, 'message' => 'Berhasil absensi keluar!', 'data' => $res]);
    //     }
    // }

    public function history()
    {
        return Absensi::where('tanggal', '!=', Carbon::now()->toDateString());
    }

    public function absensiHistory()
    {
        $history = $this->history()->get()->sortByDesc('tanggal')->values();

        foreach ($history as $i => $h) {
            $history[$i]['name'] = User::find($h->user_id)->name;
        }

        return response()->json(['status' => 200, 'data' => $history]);
    }

    public function searchHistory($name)
    {
        $users = User::where('name', 'LIKE', "%$name%")->get();

        $absensi = [];
        $history = $this->history();
        foreach ($history->get() as $absen) {
            if ($history->count()) {
                foreach ($users as $user) {
                    if ($user->id === $absen->user_id) {
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

    public function getAvailableAbsenYears()
    {
        return response()->json([
            'status' => 200,
            'data' => collect(DB::select(
                DB::raw("SELECT DISTINCT YEAR(tanggal) AS tahun FROM absensis ORDER BY tahun DESC")
            ))->map(function ($year) {
                return $year->tahun;
            })
        ]);
    }

    public function filterHistory($year, $month)
    {
        $query = "SELECT * FROM absensis WHERE MONTH(tanggal) = $month AND YEAR(tanggal) = $year";

        if ($year === 'all') {
            $query = "SELECT * FROM absensis WHERE MONTH(tanggal) = $month";
        }

        if ($month === 'all') {
            $query = "SELECT * FROM absensis WHERE YEAR(tanggal) = $year";
        }

        if ($month === 'all' && $year === 'all') {
            $query = "SELECT * FROM absensis";
        }

        $absensi = [];
        foreach (DB::select(DB::raw($query)) as $absen) {
            $userAbsen = $absen;
            $userAbsen->name = User::find($absen->user_id)->name;
            $absensi[] = $userAbsen;
        }

        return response()->json([
            'status' => 200,
            'data' => $absensi
        ]);
    }

    public function myAbsensi()
    {
        $myAbsensi = Absensi::where('user_id', '=', Auth::user()->id)->get();
        return response()->json(['status' => 200, 'message' => 'Data telah diambil!', 'data' => $myAbsensi]);
    }

    public function getUsersAbsenByAdmin() {
        $users = User::all()
        ->filter(function ($user) {
            return Role::find($user->id)['id'] !== 1;
        })
        ->values()
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'profile' => $user->profile
            ];
        });

        return response()->json([
            'status' => 200, 
            'message' => 'Data user berhasil diambil',
            'data' => $users
        ]);
    }

    public function searchUsersAbsenByAdmin($name) {
        $users = User::where('name', 'LIKE', "%$name%")->get()
        ->filter(function ($user) {
            return Role::find($user->id)['id'] !== 1;
        })
        ->values()
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'profile' => $user->profile
            ];
        });

        return response()->json([
            'status' => 200, 
            'message' => 'Data user berhasil diambil',
            'data' => $users
        ]);
    }

    public function absenMasukByAdmin(Request $request) {
        $check_duplicate_data = Absensi::where([
            'user_id' => $request->userId, 
            'tanggal' => Carbon::parse($request->tanggal . ' ' . $request->jamAbsen)->toDateString()
        ])->count();

        if ( $check_duplicate_data > 0 ) {
            $date = Carbon::parse($request->tanggal)->translatedFormat('l, d F Y');

            return response()->json([
                'status' => 400, 
                'message' => "User telah melakukan absen masuk pada $date.",
                'data' => [
                    'absen_id' => Absensi::where('user_id', $request->userId)->first()->id
                ]
            ]);
        }

        $tanggal = $request->tanggal . ' ' . $request->jamAbsen;
        if (
            Carbon::parse($tanggal)->format('H:i') >= '08:00' 
            && 
            Carbon::parse($tanggal)->format('H:i') <= '08:20'
        ) {
            $status = 'tepat waktu';
        } else if ( Carbon::parse($tanggal)->format('H:i') < '08:00' ) {
            $status = 'kecepatan';
        } else {
            $status = 'terlambat';
        }

        $absensi = new Absensi();
        $absensi->user_id = $request->userId;
        $absensi->tanggal = $request->tanggal;
        $absensi->absensi_masuk = $request->jamAbsen;
        $absensi->keterangan = $request->keterangan;
        $absensi->status = $status;
        $absensi->absen_oleh_admin = Auth::user()->id;
        if ( $absensi->save() ) {
            return response()->json(['status' => 200, 'message' => 'Berhasil absensi masuk!', 'data' => $absensi]);
        }

        return response()->json(['status' => 400, 'message' => 'Gagal Absen user!']);
    }

    public function getAbsensiByAdmin() {
        $absensi = Absensi::where('absensi_keluar', null)->get();

        $absensi = $absensi->map(function($absen) {
            return [
                'id' => $absen->id,
                'name' => $absen->user->name,
                'profile' => $absen->user->profile,
                'tanggal' => Carbon::parse($absen->tanggal)->translatedFormat('l, d F Y')
            ];
        });

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil diambil',
            'data' => $absensi
        ]);
    }

    public function searchUsersAbsensiByAdmin($name) {
        $users = User::where('name', 'LIKE', "%$name%")->get();

        $absensi = $users
        ->filter(function ($user) {
            return Absensi::where('user_id', $user->id)
            ->where('absensi_keluar', null)
            ->count();
        })
        ->values()
        ->map(function ($user) {
            $absen = Absensi::where('user_id', $user->id)
                ->where('absensi_keluar', null)
                ->first();

            return [
                'id' => $absen->id,
                'name' => $absen->user->name,
                'tanggal' => Carbon::parse($absen->tanggal)->translatedFormat('l, d F Y'),
                'profile' => $absen->user->profile
            ];
        });

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil diambil',
            'data' => $absensi
        ]);
    }

    public function absenKeluarByAdmin(Request $request) {
        $absen = $this->absensi->where(['id' => $request->absenId]);
        if ( $absen->count() ) {
            if ( $absen->where('absensi_keluar', null)->count() ) {
                if ( $absen->update(['absensi_keluar' => $request->jamAbsen]) ) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Absen keluar berhasil'
                    ]);
                }
                return response()->json([
                    'status' => 400,
                    'message' => 'Absen keluar gagal'
                ]);
            }
            return response()->json([
                'status' => 400,
                'message' => 'User telah melakukan absen keluar.'
            ]);
        }
        return response()->json([
            'status' => 404,
            'message' => 'Absen tidak ditemukan'
        ]);
    }
    
    public function file($name)
    {
        $file = Storage::get($name);
        $mime = Storage::getMimeType($name);
        
        return response($file, 200)->header('Content-Type', $mime);
    }
}
