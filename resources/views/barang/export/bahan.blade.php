<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bahan Praktik SIJA</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Barang</th>
                <th>Merek</th>
                <th>Total Stok
                <th>Jumlah Barang</th>
                <th>Terinventarisasi</th>
            </tr>
        </thead>
        <tbody>
            {{-- @dd($dataDetail) --}}
            @php $nomorUrut = 1; @endphp
            @foreach($barang as $key => $br)
                <tr>
                    <td>{{ $nomorUrut++ }}</td>
                    <td>{{ $br->nama_barang}}</td>
                    <td>{{ $br->merek }}</td>
                    <td>{{ $br->stok_barang }}</td>
                    <td>{{ $updatedStokBarang[$br->id_barang] ?? 0}}</td>
                    <td>{{ $totals[$br->id_barang] ?? '-'}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Include the dompdf styles --}}
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0px; /* Menambahkan margin 20px di sekitar tabel */        }
        
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</body>
</html>
