<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Absensi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use App\User;

class LaporanByMonthViewExport implements FromView, WithEvents
{
    protected $carbon;
    protected $attendance;
    protected $imagePath;
    protected $month;
    protected $year;
    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
        $this->carbon = new Carbon();
        $this->absensi = new Absensi();
        $this->imagePath = public_path() . '/storage/attendances_photo/';
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    private function getWeeklyAbsen($year, $month, $startDate, $endDate, $user_id = null)
    {
        // Get current month
        if ($user_id == null) {
            return DB::select(DB::raw("SELECT * FROM absensis WHERE MONTH(tanggal) = " . $month . " AND YEAR(tanggal) = " . $year . " AND DAY(tanggal) BETWEEN " . $startDate . " AND " . $endDate));
        }
        return DB::select(DB::raw("SELECT * FROM absensis WHERE MONTH(tanggal) = " . $month . " AND YEAR(tanggal) = " . $year . " AND user_id = " . $user_id . " AND DAY(tanggal) BETWEEN " . $startDate . " AND " . $endDate));
    }
    private function getMonthAbsenHours($date)
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
        $first_week_absen = $this->getWeeklyAbsen($year, $month, $first_date, $second_week_start);
        $second_week_absen = $this->getWeeklyAbsen($year, $month, $second_week_start, $third_week_start);
        $third_week_absen = $this->getWeeklyAbsen($year, $month, $third_week_start, $fourth_week_start);
        $fourth_week_absen = $this->getWeeklyAbsen($year, $month, $fourth_week_start, $last_date);
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
    public function view(): View
    {
        $date = $this->carbon->createFromDate($this->year, $this->month);
        $total_terlambat = $this->getDataByStatus($this->year, $this->month, 'terlambat');
        $total_tepat_waktu = $this->getDataByStatus($this->year, $this->month, 'tepat waktu');
        $total_kecepatan = $this->getDataByStatus($this->year, $this->month, 'kecepatan');
        $total_jam_kerja = $this->getMonthAbsenHours($date);
        return view('laporan', [
            'nama_bulan' => $date->format('F'),
            'total_jam_per_bulan' => $total_jam_kerja,
            'status_pegawai' => [
                'terlambat' => $total_terlambat,
                'tepat_waktu' => $total_tepat_waktu,
                'overwork' => $total_kecepatan
            ]
        ]);
    }
    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Apply array of styles for header title
                $event->sheet->getDelegate()->getStyle('A1:L100')->applyFromArray([
                    'size' => 20,
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ]
                ]);
                $event->sheet->getDelegate()->getStyle('A5:L5')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ]
                ]);
                $event->sheet->getDelegate()->getStyle('A5:L5')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ]
                ]);
                // Apply array of styles for table body
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ]
                    ]
                ];
                $event->sheet->getDelegate()->getStyle('A3:L5')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('A8:L10')->applyFromArray($styleArray);
            }
        ];
    }
}
