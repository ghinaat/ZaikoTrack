<table>
    <thead>
        <tr>
            <td colspan="5">Data Alat dan Perlengkapan Barang SIJA</td>
        </tr>
        <tr></tr>
        <tr>
            <th>No.</th>
            <th>Nama Barang</th>
            <th>Kode Barang</th>
        </tr>
    </thead>
    <tbody>
        {{-- @dd($dataDetail) --}}
        @php $nomorUrut = 1; @endphp
        @foreach($barangs as $key => $barang)
                    <tr>
                        <td>{{ $nomorUrut++ }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->kode_barang }}</td>
                    </tr>
        @endforeach
        </tbody>
</table>
