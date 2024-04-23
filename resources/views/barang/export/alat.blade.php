<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alat & Perlengkapan SIJA</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th style="width: 5%">No.</th>
                <th style="width: 15%">Nama Barang</th>
                <th style="width: 15%">Merek</th>
                <th style="width: 20%">Kode Barang</th>    
                <th style="">Barcode</th>
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
                    <td>{{ $br->kode_barang }}</td>
                    <td><img src="{{ asset('/storage/barcode/' . $br->barqode_image) }}" width="500px"></td>
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
