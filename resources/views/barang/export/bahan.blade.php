<table>
    <thead>
        <tr>
            <td colspan="3">Data Bahan Praktik SIJA</td>
        </tr>
        <tr></tr>
        <tr>
            <th>No.</th>
            <th>Nama Barang</th>
            <th>Stok</th>
        </tr>
    </thead>
    <tbody>
        @php $nomorUrut = 1; @endphp
        @foreach($bahans as $key => $bahan)
                    <tr>
                        <td>{{ $nomorUrut++ }}</td>
                        <td>{{ $bahan->nama_barang }}</td>
                        <td>{{ $bahan->stok_barang }}</td>
                    </tr>
        @endforeach
        </tbody>
</table>
