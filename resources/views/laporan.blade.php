<table align="center" width="100%">
    <tr><th colspan="16"><h1>Laporan Absensi Bulan {{$nama_bulan}}</h1></th></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    @isset($total_jam_pegawai)
        <tr>
            <th colspan="1">No.</th>
            <th colspan="3">Nama Pegawai</th>
            <th colspan="2">Total Terlambat</th>
            <th colspan="2">Total Tepat Waktu</th>
            <th colspan="2">Total Tidak Hadir</th>
            <th colspan="2">Total Lembur</th>
            <th colspan="2">Total Jam Lembur</th>
            <th colspan="2">Total Jam Kerja</th>
        </tr>
        @foreach ($total_jam_pegawai as $pegawai)
            <tr>
                <th colspan="1">{{$loop->iteration}}</th>
                <th colspan="3">{{$pegawai['name']}}</th>
                <th colspan="2">{{$pegawai['total_terlambat']}}</th>
                <th colspan="2">{{$pegawai['total_tepat_waktu']}}</th>
                <th colspan="2">{{$pegawai['total_tidak_hadir']}}</th>
                <th colspan="2">{{$pegawai['total_lembur']}}</th>
                <th colspan="2">{{$pegawai['total_jam_lembur']}}</th>
                <th colspan="2">{{$pegawai['total_jam_kerja']}}</th>
            </tr>
        @endforeach
    @endisset
</table>