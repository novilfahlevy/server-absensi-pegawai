<?php

namespace App\Http\Controllers\Api;

use App\Absensi;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Lembur;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use App\ProjectManager;

class LemburController extends Controller
{
    protected $imagePath;

    public function __construct()
    {
        $this->imagePath = public_path() . '/storage/lembur/';
    }
    public function filter($month, $year)
    {
        $results =  DB::select(DB::raw("SELECT * FROM lemburs WHERE MONTH(tanggal) = " . $month . " AND YEAR(tanggal) = " . $year . " AND status != 'waiting' "));
        foreach ($results as $key => $result) {
            $results[$key]->name = User::select('name')->where('id', $result->user_id)->get()->toArray();
            $results[$key]->name = $results[$key]->name[0]['name'];
        }
        return response()->json(['status' => 200, 'message' => 'Berhasil lembur!. Mohon tunggu admin untuk mempersetujuinya.', 'data' => $results]);
    }
    public function index($role, $id)
    {
        $carbon = new Carbon();
        $lates = Lembur::all();
        $latesStatusIsWaiting = [];
        $latesStatusIsDeniedRejected = [];
        if ($role == 'Project Manager') {
            $users = ProjectManager::where('pm_id', $id)->get();
            $lemburs = [];
            foreach ($users as $key => $user) {
                $lemburs[$key] = Lembur::where('user_id', $user->id)->get();
            }
            dd(count($lemburs[0]));
        } else {
            $latesStatusIsWaiting = Lembur::where('status', 'menunggu')->where('tanggal', $carbon->now()->toDateString())->get();
            $latesStatusIsDeniedRejected = Lembur::where('status', '!=', 'menunggu')->get();
        }
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
