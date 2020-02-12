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
        $this->totalRows = count(User::all()) + 5;
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
    private function getMonthlyLemburHours($year, $month, $user_id)
    {
        $lembur_hours = [];
        $total_lemburs = DB::select(DB::raw("SELECT * FROM absensis WHERE MONTH(tanggal) = " . $month . " AND YEAR(tanggal) = " . $year . " AND user_id = " . $user_id));
        foreach ($total_lemburs as $key => $lembur) {
            $lembur_hours[$key] = $this->carbon->parse($lembur->lembur_akhir)->diffInHours($this->carbon->parse($lembur->lembur_awal));
        }
        return array_sum($lembur_hours);
    }

    public function view(): View
    {
        $date = $this->carbon->createFromDate($this->year, $this->month);
        $first_date = $date->firstOfMonth()->day;
        $last_date = $date->lastOfMonth()->day;
        $total_days = $date->daysInMonth;
        $users = User::all();
        $users_report = [];
        $i = 0;
        foreach ($users  as $user) {
            $total_hours = [];
            $user_absens = $this->getWeeklyAbsen($this->year, $this->month, $first_date, $last_date, $user->id);
            foreach ($user_absens as $key => $absen) {
                $total_hours[$key] = $this->carbon->parse($absen->absensi_keluar)->diffInHours($this->carbon->parse($absen->absensi_masuk));
            }
            $users_report[$i] = [
                'name' => $user->name,
                'total_terlambat' => count(DB::select(DB::raw("SELECT * FROM absensis WHERE MONTH(tanggal) = " . $this->month . " AND YEAR(tanggal) = " . $this->year . " AND status = 'terlambat' AND user_id = " . $user->id))) . ' Kali',
                'total_tepat_waktu' => count(DB::select(DB::raw("SELECT * FROM absensis WHERE MONTH(tanggal) = " . $this->month . " AND YEAR(tanggal) = " . $this->year . " AND status = 'tepat waktu' AND user_id = " . $user->id))) . ' Kali',
                'total_lembur' => count(DB::select(DB::raw("SELECT * FROM lemburs WHERE MONTH(tanggal) = " . $this->month . " AND YEAR(tanggal) = " . $this->year . " AND user_id = " . $user->id))) . ' Kali',
                'total_tidak_hadir' => ($total_days - count(DB::select(DB::raw("SELECT * FROM absensis WHERE MONTH(tanggal) = " . $this->month . " AND YEAR(tanggal) = " . $this->year . " AND user_id = " . $user->id)))) . ' Kali',
                'total_jam_lembur' => $this->getMonthlyLemburHours($this->month, $this->year, $user->id) . ' Jam',
                'total_jam_kerja' => array_sum($total_hours) . ' Jam',
            ];
            $i++;
        }
        return view('laporan', [
            'nama_bulan' => $date->format('F'),
            'total_jam_pegawai' => $users_report
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
                $event->sheet->getDelegate()->getStyle('A1:P1000')->applyFromArray([
                    'size' => 20,
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ]
                ]);
                $event->sheet->getDelegate()->getStyle('A5:P5')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ]
                ]);
                $event->sheet->getDelegate()->getStyle('A5:P5')->applyFromArray([
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
                $event->sheet->getDelegate()->getStyle('A5:P' . $this->totalRows)->applyFromArray($styleArray);
            }
        ];
    }
}
