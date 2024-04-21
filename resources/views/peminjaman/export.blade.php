<table>
    <thead>
        <tr>
            <td colspan="6">Rekap Data Pemakaian Barang SIJA</td>
        </tr>
        @if($start_date && $end_date)
        <tr>
            <td colspan="6">Tanggal Awal: {{ $start_date }} Tanggal Akhir : {{ $end_date}}</td>
        </tr>
        @endif
        @if($id_barang)
        <tr>
        <td colspan="6">Barang: {{ $id_barang }} </td>
        </tr>
        @endif
        <tr></tr>
        <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Kelas Jurusan</th>
            <th>Tanggal Pinjam</th>
            <th>Nama Barang</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        {{-- @dd($dataDetail) --}}
        @php $nomorUrut = 1; @endphp
        @foreach($peminjamans as $key => $peminjaman)
            @foreach($dataDetail as $detail)
                @if(isset($detail['id_peminjaman']) && $detail['id_peminjaman'] == $peminjaman->id_peminjaman)
                    <tr>
                        <td>{{ $nomorUrut++ }}</td>
                        <td>
                            @if($peminjaman->id_users !== 1) 
                            {{ $peminjaman->users->name}}
                            @elseif($peminjaman->id_guru !== 1) 
                            {{ $peminjaman->guru->nama_guru}}
                            @elseif($peminjaman->id_karyawan !== 1) 
                            {{ $peminjaman->karyawan->nama_karyawan}}
                            @endif
                        </td>
                        <td>{{ $peminjaman->kelas }} {{ $peminjaman->jurusan }}</td>
                        <td>{{ $peminjaman->tgl_pinjam }}</td>
                        <td>{{ $detail->inventaris->barang['nama_barang'] }}</td>
                        <td>{{ $detail['tgl_kembali']}}</td>
                        <td>{{ $detail['status'] }}</td>
                    </tr>
                @endif
            @endforeach
        @endforeach
        </tbody>
</table>
