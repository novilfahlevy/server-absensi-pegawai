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
use App\Izin;
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

        $check_izin = Izin::join('users', 'users.id', '=', 'izins.user_id')
        ->where('users.id', $request->userId)
        ->where(DB::raw('UNIX_TIMESTAMP(tanggal_mulai)'), '<=', Carbon::parse($request->tanggal)->unix())
        ->where(DB::raw('(UNIX_TIMESTAMP(tanggal_selesai) + 60 * 60 * 24)'), '>=', Carbon::parse($request->tanggal)->unix());

        if ( $check_izin ) {
            return response()->json([
                'status' => 400, 
                'message' => "User melakukan izin pada tanggal absen ini."
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
        $absensi->absen_masuk_oleh_admin = Auth::user()->id;
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
                'tanggal' => Carbon::parse($absen->tanggal)->translatedFormat('l, d F Y'),
                'jamMasuk' => Carbon::parse("$absen->tanggal $absen->absensi_masuk")->translatedFormat('H:i')
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
                'profile' => $absen->user->profile,
                'jamMasuk' => Carbon::parse("$absen->tanggal $absen->absensi_masuk")->translatedFormat('H:i')
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
                if ( 
                    $absen->update([
                        'absensi_keluar' => $request->jamAbsen,
                        'absen_keluar_oleh_admin' => Auth::user()->id
                    ]) 
                ) {
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

    // public function belumAbsen() {
    //     $users = User::join('absensis', 'absensis.user_id', '=', 'users.id')
    //     ->select('users.id', 'users.name')
    //     ->get()
    //     ->filter(function($user) {
    //         return Absensi::where('user_id', $user->id)
    //         ->where(DB::raw('MONTH(tanggal)'), '03')
    //         ->where(DB::raw('YEAR(tanggal)'), '2020')
    //         ->count();
    //     })
    //     ->values();

    //     return response()->json([
    //         'status' => 200,
    //         'data' => $users
    //     ]);
    // }
}
