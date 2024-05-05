<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ public_path('/css/label.css') }}">
    <style>
        .kodeTahun {
            padding-right: 80px; /* Atur jarak kanan sesuai kebutuhan */
            padding-bottom: 15px;
        }
        .qr{
            padding-left: 20px
            padding-bottom: 15px;

        }
        /* .qr,
        .kodeTahun {
            border: 1px solid black; 
            padding: 5px; 
        } */
        
        </style>
</head>
<body>
    <table class="w-full">
        @php $count = 0 @endphp <!-- Menghitung jumlah item yang ditampilkan -->
        @foreach($barang as $key => $br)
            @if($count % 2 == 0) <!-- Mulai baris baru setiap 2 item -->
                <tr>
            @endif
            <td class="w-half qr">
                <img src="{{ public_path('/storage/qrcode/' . $br->qrcode_image) }}" style="width: 2cm;">
            </td>
            <td class="w-half kodeTahun" style="font-size: 22px;">
                <p>{{ $br->kode_barang }}/{{ date('Y', strtotime($br->detailPembelian->pembelian->tgl_pembelian)) }}</p>
            </td>
            @php $count++ @endphp <!-- Menambah hitungan item -->
            @if($count % 2 == 0 || $loop->last) <!-- Menutup baris setiap 2 item atau pada item terakhir -->
                </tr>
            @endif
        @endforeach
    </table>
</body>
</html>
