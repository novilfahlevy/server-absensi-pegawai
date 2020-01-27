<table align="center" width="100%">
<tr><th colspan="12"><h1>Laporan Absensi Bulan {{$nama_bulan}}</h1></th></tr>
    <tr></tr>
    <tr><th colspan="12">Data Status Pegawai</th></tr>
    <tr>
        <th colspan="4">Terlambat</th>
        <th colspan="4">Tepat Waktu</th>
        <th colspan="4">Overwork</th>
    </tr>
    <tr>
    <th colspan="4">{{$status_pegawai['terlambat']}} Kali</th>
        <th colspan="4">{{$status_pegawai['tepat_waktu']}} Kali</th>
        <th colspan="4">{{$status_pegawai['overwork']}} Kali</th>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr><th colspan="12">Data Total Jam Kerja Pegawai</th></tr>
    <tr>
        <th colspan="2">Minggu 1</th>
        <th colspan="2">Minggu 2</th>
        <th colspan="2">Minggu 3</th>
        <th colspan="2">Minggu 4</th>
        <th colspan="4">Total Jam</th>
    </tr>
    <tr>
        <th colspan="2">{{$total_jam_per_bulan[0]}} Jam</th>
        <th colspan="2">{{$total_jam_per_bulan[1]}} Jam</th>
        <th colspan="2">{{$total_jam_per_bulan[2]}} Jam</th>
        <th colspan="2">{{$total_jam_per_bulan[3]}} Jam</th>
        <th colspan="4">{{array_sum($total_jam_per_bulan)}} Jam</th>
    </tr>
    <tr></tr>
    <tr></tr>
    @isset($total_jam_pegawai)
        <tr>
            <th colspan="2">No.</th>
            <th colspan="2">Nama Pegawai</th>
            <th colspan="2">Total Terlambat</th>
            <th colspan="2">Total Tepat Waktu</th>
            <th colspan="2">Total Lembur</th>
            <th colspan="2">Total Jam Kerja</th>
        </tr>
            @foreach ($total_jam_pegawai as $pegawai)
            <tr>
                <th colspan="2">{{$loop->iteration}}</th>
                <th colspan="2">{{$pegawai['name']}}</th>
            <th colspan="2">{{$pegawai['total_terlambat']}}</th>
            <th colspan="2">{{$pegawai['total_tepat_waktu']}}</th>
            <th colspan="2">{{$pegawai['total_lembur']}}</th>
            <th colspan="2">{{$pegawai['total_jam_kerja']}}</th>
            </tr>
        @endforeach
    @endisset
</table>