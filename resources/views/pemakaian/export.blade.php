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
        <tr></tr>
        <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Kelas Jurusan</th>
            <th>Tanggal Pakai</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        {{-- @dd($dataDetail) --}}
        @php $nomorUrut = 1; @endphp
        @foreach($pemakaians as $key => $pemakaian)
            @foreach($dataDetail as $detail)
                @if(isset($detail['id_pemakaian']) && $detail['id_pemakaian'] == $pemakaian->id_pemakaian)
                    <tr>
                        <td>{{ $nomorUrut++ }}</td>
                        <td>
                            @if($pemakaian->id_users !== 1) 
                            {{ $pemakaian->users->name}}
                            @elseif($pemakaian->id_guru !== 1) 
                            {{ $pemakaian->guru->nama_guru}}
                            @elseif($pemakaian->id_karyawan !== 1) 
                            {{ $pemakaian->karyawan->nama_karyawan}}
                            @endif
                        </td>
                        <td>{{ $pemakaian->kelas }} {{ $pemakaian->jurusan }}</td>
                        <td>{{ $pemakaian->tgl_pakai }}</td>
                        <td>{{ $detail->inventaris->barang['nama_barang'] }}</td>
                        <td>{{ $detail['jumlah_barang'] }}</td>
                    </tr>
                @endif
            @endforeach
        @endforeach
        </tbody>
</table>
