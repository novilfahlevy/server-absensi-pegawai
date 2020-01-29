<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;
use App\Jobdesc;
use Intervention\Image\Facades\Image;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function __construct()
    {
        $this->carbon = new Carbon();
        $this->imagePath = public_path() . '/storage/profiles/';
    }
    public function index()
    {
        $users = User::all();

        foreach ($users as $key => $user) {
            $role = $user->roles()->pluck('name');
            $users[$key]['job'] = Jobdesc::find($user->jobdesc_id)->name;
            $users[$key]['role'] = $role;
        }

        return response()->json(['status' => '200', 'message' => 'Sukses', 'user' => $users]);
    }
    private function getWeeklyAbsen($year, $month, $startDate, $endDate, $user_id = null)
    {
        // Get current month
        if ($user_id == null) {
            return DB::select(DB::raw("SELECT * FROM absensis WHERE MONTH(tanggal) = " . $month . " AND YEAR(tanggal) = " . $year . " AND DAY(tanggal) BETWEEN " . $startDate . " AND " . $endDate));
        }
        return DB::select(DB::raw("SELECT * FROM absensis WHERE MONTH(tanggal) = " . $month . " AND YEAR(tanggal) = " . $year . " AND user_id = " . $user_id . " AND DAY(tanggal) BETWEEN " . $startDate . " AND " . $endDate));
    }
    private function getMonthAbsenHours($date, $id)
    {
        // Get the last date of the current month

        $first_date = $date->firstOfMonth()->day;
        $last_date = $date->lastOfMonth()->day;
        $fourth_week_start = $date->firstOfMonth()->addDays(21)->day;
        $third_week_start = $date->firstOfMonth()->addDays(14)->day;
        $second_week_start = $date->firstOfMonth()->addDays(7)->day;
        // Array of all hours
        $first_week_hours = [];
        $second_week_hours = [];
        $third_week_hours = [];
        $fourth_week_hours = [];
        // Absens of all weeks
        $year = $date->year;
        $month = $date->month;
        $first_week_absen = $this->getWeeklyAbsen($year, $month, $first_date, $second_week_start, $id);
        $second_week_absen = $this->getWeeklyAbsen($year, $month, $second_week_start, $third_week_start, $id);
        $third_week_absen = $this->getWeeklyAbsen($year, $month, $third_week_start, $fourth_week_start, $id);
        $fourth_week_absen = $this->getWeeklyAbsen($year, $month, $fourth_week_start, $last_date, $id);
        // foreach and input it to array above
        foreach ($first_week_absen as $key => $absen) {
            $first_week_hours[$key] = $this->carbon->parse($absen->absensi_keluar)->diffInHours($this->carbon->parse($absen->absensi_masuk));
        }
        foreach ($second_week_absen as $key => $absen) {
            $second_week_hours[$key] = $this->carbon->parse($absen->absensi_keluar)->diffInHours($this->carbon->parse($absen->absensi_masuk));
        }
        foreach ($third_week_absen as $key => $absen) {
            $third_week_hours[$key] = $this->carbon->parse($absen->absensi_keluar)->diffInHours($this->carbon->parse($absen->absensi_masuk));
        }
        foreach ($fourth_week_absen as $key => $absen) {
            $fourth_week_hours[$key] = $this->carbon->parse($absen->absensi_keluar)->diffInHours($this->carbon->parse($absen->absensi_masuk));
        }
        // Final output in hour
        $first_week_hours_total = array_sum($first_week_hours);
        $second_week_hours_total = array_sum($second_week_hours);
        $third_week_hours_total = array_sum($third_week_hours);
        $fourth_week_hours_total = array_sum($fourth_week_hours);
        return  [$first_week_hours_total, $second_week_hours_total, $third_week_hours_total, $fourth_week_hours_total];
    }
    private function getDataByStatus($year, $month, $status)
    {
        return count(DB::select(DB::raw("SELECT * FROM absensis WHERE MONTH(tanggal) = " . $month . " AND YEAR(tanggal) = " . $year . " AND status = " . "'$status'")));
    }
    public function filter(Request $request)
    {
        $users = User::all();

        foreach ($users as $key => $user) {
            $role = $user->roles()->pluck('name');
            $users[$key]['job'] = Jobdesc::find($user->jobdesc_id)->name;
            $users[$key]['role'] = $role;
        }

        $users = $users->filter(function ($data) use ($request) {
            if ($request->job !== 'all' && $request->role !== 'all') {
                return $data->job === $request->job && $data->role[0] === $request->role;
            }

            if ($request->job !== 'all') {
                return $data->job === $request->job;
            }

            if ($request->role !== 'all') {
                return $data->role[0] === $request->role;
            }

            return true;
        })->values();

        return response()->json(['status' => 200, 'data' => $users]);
    }

    public function show($id)
    {
        $user = User::with('roles')->where('id', $id)->first();
        $total_jam_per_bulan = $this->getMonthAbsenHours(Carbon::now(), $id);
        $current_month = Carbon::now()->month;
        $current_year = Carbon::now()->year;
        $user['jam_kerja'] = [
            'minggu1' => $total_jam_per_bulan[0],
            'minggu2' => $total_jam_per_bulan[1],
            'minggu3' => $total_jam_per_bulan[2],
            'minggu4' => $total_jam_per_bulan[3],
            'performance' => [
                'total_jam_per_minggu' => array_sum($total_jam_per_bulan),
                'terlambat' => count(DB::select(DB::raw("SELECT * FROM absensis WHERE MONTH(tanggal) = " . $current_month . " AND YEAR(tanggal) = " . $current_year . " AND status = 'terlambat' AND user_id = " . $id))),
                'total_lembur' => count(DB::select(DB::raw("SELECT * FROM lemburs WHERE MONTH(tanggal) = " . $current_month . " AND YEAR(tanggal) = " . $current_year . " AND user_id = " . $id)))
            ]
        ];
        return response()->json(['status' => '200', 'message' => 'Sukses', 'user' => $user]);
    }

    public function login()
    {
        if (Auth::attempt(['email' => request('keyword'), 'password' => request('password')]) || Auth::attempt(['username' => request('keyword'), 'password' => request('password')])) {
            $user = Auth::user();
            $roles = User::with('roles')->where('id', $user->id)->first();
            $success['id'] = $user->id;
            $success['name'] = $user->name;
            $success['role'] = $roles->roles[0]['name'];
            $success['token'] = $user->createToken('Passport Token')->accessToken;
            Passport::personalAccessTokensExpireIn(now()->addHours(12));
            return response()->json(['status' => 200, 'message' => $success]);
        } else {
            return response()->json(['status' => 401, 'message' => 'Email atau password salah!']);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Logout berhasil!']);
    }

    public function store(RegisterUserRequest $request)
    {
        $input = $request->all();
        $input['username'] = strtolower($request->username);
        $input['profile'] = 'default.jpg';
        $input['password'] = bcrypt($input['password']);
        $input['jobdesc_id'] = (int) $input['jobdesc_id'];
        $user = User::create($input);
        $role = Role::find($request->role_id);
        $user->assignRole($role);

        return response()->json(['status' => '200', 'message' => 'Sukses', 'user' => $user]);
    }

    public function editPassword(Request $request)
    {
        $request->validate(
            [
                'user_id' => ['required'],
                'current_password' => ['required', new MatchOldPassword],
                'new_password' => ['required'],
            ],
            [
                'current_password.required' => 'Masukkan password anda terlebih dahulu!',
                'new_password.required' => 'Masukkan password baru anda terlebih daulu!',
            ]
        );

        $id = $request->user_id;
        $user = User::find($id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        if ($user) {
            return response()->json(['code' => 200, 'message' => 'Berhasil mengganti password!', 'data' => $user]);
        }

        return response()->json(['code' => 400, 'message' => 'Password sekarang anda salah!']);
    }

    public function editProfile(Request $request)
    {
        $request->validate(
            [
                'profile' => 'required|image|mimes:jpeg,png,svg|max:2048',
            ],
            [
                'profile.required' => 'Masukkan gambar terlebih dahulu!',
                'profile.image' => 'File harus gambar!',
                'profile.mimes' => 'Ekstensi gambar tidak valid!',
                'profile.max' => 'Profile anda sudah melebihi batas ukuran!'
            ]
        );

        if (!File::isDirectory($this->imagePath)) {
            File::makeDirectory($this->imagePath);
        }

        $user = new User();

        $input = $request->file('profile');
        $hashNameImage = time() . '_' . $input->getClientOriginalName();
        $canvas = Image::canvas(500, 500);
        $resizeImage = Image::make($input)->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
        });
        $canvas->insert($resizeImage, 'center');
        $canvas->save($this->imagePath . '/' . $hashNameImage);
        $user->profile = $hashNameImage;
        $user->where('email', '=', Auth::user()->email)->update(['profile' => $hashNameImage]);

        return response()->json(['status' => 200, 'message' => 'Profil anda berhasil diupdate!', 'data' => url('/storage/profiles/' . $hashNameImage)]);
    }

    public function editKredensial(Request $request, $id)
    {
        $user = User::find($id);
        $user->syncRoles([Role::findById($request->role_id)]);
        $user->fill($request->all())->save();

        return response()->json(['status' => 200, 'message' => 'Berhasil edit kredensial!', 'data' => $user]);
    }

    public function cari($name)
    {

        $user = User::where('name', 'LIKE', '%' . $name . '%')->get();

        if (!$user->isEmpty()) {
            return response()->json(['code' => 200, 'message' => 'Berhasil mencari data!', 'data' => $user]);
        }

        return response()->json(['code' => 400, 'message' => 'Kata yang anda cari tidak ditemukan!']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['status' => 200, 'message' => 'Berhasil menghapus user!']);
    }

    public function unauthorized()
    {
        return response()->json(['status' => 'Unauthorized'], 401);
    }
}
