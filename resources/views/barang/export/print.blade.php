<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{public_path('/css/label.css')}}">
</head>
<body>
    <table class="w-full">
        @foreach($barang as $key => $br)
        <tr>
            <td class="w-half">
                <img src="{{ public_path('/storage/qrcode/' . $br->qrcode_image) }}" style="width: 2cm;">  
            </td>
            <td class="w-half" style="font-size: 25px;">
                <p>{{ $br->kode_barang }}/{{ date('Y', strtotime($br->detailPembelian->pembelian->tgl_pembelian)) }}</p>       
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>
