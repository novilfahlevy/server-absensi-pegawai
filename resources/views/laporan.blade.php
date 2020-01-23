<table class="table">
    <thead>
        <th style="background:red;">#</th>
        <th>Absen Masuk</th>
        <th>Absen Keluar</th>
    </thead>
    <tbody>
        @foreach ($absensis as $absensi)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$absensi->absensi_masuk}}</td>
                <td>{{$absensi->absensi_keluar}}</td>
            </tr>
        @endforeach
    </tbody>
</table>