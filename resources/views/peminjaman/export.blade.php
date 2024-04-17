<table>
    <thead>
        <tr><td>Rekap Peminjaman Barang SIJA</td></tr>
        <tr><td>Tanggal Awal: {{ $peminjaman['tglawal'] }} Tanggal Akhir : {{ $peminjaman['tglakhir'] }}</td></tr>
        <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Kelas Jurusan</th>
            <th>Tanggal Pinjam</th>
            <th>Nama Barang</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($peminjaman['peminjamanData'] as $key => $peminjaman)
        <tr>
            <td>{{$key + 1}}</td>
            <td> 
                @if($peminjaman->id_siswa !== 1) 
                {{ $peminjaman->siswa->nama_siswa}}
                @elseif($peminjaman->id_guru !== 1) 
                {{ $peminjaman->guru->nama_guru}}
                @elseif($peminjaman->id_karyawan !== 1) 
                {{ $peminjaman->karyawan->nama_karyawan}}
                @endif
            </td>
            <td>{{ $peminjaman->kelas }} {{ $peminjaman->jurusan }}</td>
            <td>{{ $peminjaman->tgl_pinjam }}</td>
            @foreach($peminjaman->detailPeminjaman as $detail)
                <td>{{ $detail->inventaris->barang->nama_barang }}</td>
                <td>{{ $detail->status }}</td>
            @endforeach
        </tr>
        @endforeach
</table>