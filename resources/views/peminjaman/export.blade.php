<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Jurusan</th>
            <th>Tanggal Pinjam</th>
            <th>Nama Barang</th>
            <th>Terlambat Mengembalikan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($peminjaman['data'] as $key => $peminjaman)
        <tr>
            <td>{{$key + 1}}</td>
            <td>{{ $peminjaman->user->nama_pegawai }}</td>
            <td>{{ $peminjaman->user->jabatan->nama_jabatan }}</td>
            <td>{{ $peminjaman->tanggal }}</td>
            <td>{{ $peminjaman->jam_mulai }}</td>
            <td>{{ $peminjaman->jam_selesai }}</td>
            <td>{{ $peminjaman->jam_peminjaman }}</td>
            <td>{{ $peminjaman->tugas }}</td>
        </tr>
        @endforeach
    </tbody>
</table>