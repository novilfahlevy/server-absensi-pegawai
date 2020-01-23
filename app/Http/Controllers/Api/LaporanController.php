<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Absensi;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
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

        function getWeeklyAbsen($startDate, $endDate, $user_id = null)
        {
            // Get current month
            $current_month = Carbon::now()->month;
            if ($user_id == null) {
                return DB::select(DB::raw("SELECT * FROM absensis WHERE MONTH(tanggal) = " . $current_month . " AND DAY(tanggal) BETWEEN " . $startDate . " AND " . $endDate));
            }
            return DB::select(DB::raw("SELECT * FROM absensis WHERE MONTH(tanggal) = " . $current_month . " AND user_id = " . $user_id . " AND DAY(tanggal) BETWEEN " . $startDate . " AND " . $endDate));
        }
        // Get the last date of the current month
        $first_date = $this->carbon->now()->firstOfMonth()->day;
        $last_date = $this->carbon->now()->lastOfMonth()->day;
        // Data Pegawai
        $users = User::all();
        $users_report = [];
        foreach ($users as $key => $user) {
            $total_hours = [];
            $user_absens = getWeeklyAbsen($first_date, $last_date, $user->id);
            foreach ($user_absens as $key => $absen) {
                $total_hours[$key] = $this->carbon->parse($absen->absensi_keluar)->diffInHours($this->carbon->parse($absen->absensi_masuk));
            }
            $users_report[$key] = [
                'name' => $user->name,
                'total_jam_kerja' => array_sum($total_hours),
                'total_terlambat' => Absensi::where(['status' => 'terlambat', 'user_id' => $user->id])->get()->count(),
                'total_tepat_waktu' => Absensi::where(['status' => 'tepat waktu', 'user_id' => $user->id])->get()->count(),
                'total_lembur' => 7
            ];
        }

        // Total jam dalam satu bulan dihitung per minggu
        // Get the fourth week start date
        $fourth_week_start = $this->carbon->now()->firstOfMonth()->addDays(21)->day;
        $third_week_start = $this->carbon->now()->firstOfMonth()->addDays(14)->day;
        $second_week_start = $this->carbon->now()->firstOfMonth()->addDays(7)->day;
        // Array of all hours
        $first_week_hours = [];
        $second_week_hours = [];
        $third_week_hours = [];
        $fourth_week_hours = [];
        // Absens of all weeks
        $first_week_absen = getWeeklyAbsen($first_date, $second_week_start);
        $second_week_absen = getWeeklyAbsen($second_week_start, $third_week_start);
        $third_week_absen = getWeeklyAbsen($third_week_start, $fourth_week_start);
        $fourth_week_absen = getWeeklyAbsen($fourth_week_start, $last_date);
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


        // Data for responses
        $total_jam_per_bulan = [$first_week_hours_total, $second_week_hours_total, $third_week_hours_total, $fourth_week_hours_total];
        // Status Pegawai
        $total_terlambat = Absensi::where('status', '=', 'terlambat')->get()->count();
        $total_tepat_waktu = Absensi::where('status', '=', 'tepat waktu')->get()->count();
        $total_kecepatan = Absensi::where('status', '=', 'kecepatan')->get()->count();

        return response()->json(['status' => 200, 'message' => 'Sukses', 'data' => [
            'nama_bulan' => Carbon::now()->month,
            'total_jam_pegawai' => $users_report,
            'total_jam_per_bulan' => $total_jam_per_bulan,
            'status_pegawai' => [
                'terlambat' => $total_terlambat,
                'tepat_waktu' => $total_tepat_waktu,
                'overwork' => $total_kecepatan
            ]
        ]]);
    }
}
