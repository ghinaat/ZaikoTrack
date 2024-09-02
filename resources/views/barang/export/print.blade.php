<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Label</title>
<head>
    <link rel="stylesheet" href="{{public_path('/css/label.css')}}">
</head>
<body>
    <table class="w-full">
        @foreach($barang as $key => $br)
        <td>
        <div class="label">
                <div class="label-content">
                    <div class="qr">
                        <img src="{{ public_path('/storage/qrcode/' . $br->qrcode_image) }}" style="width: 1.0cm;">
                    </div>
                    <div class="kodeTahun" style="font-size: 10px;">
                        <p>{{ $br->kode_barang }}</p>
                        <p>{{ $br->nama_barang }}</p>   
                </div>
            </div>
        </td>
        @endforeach
    </table>
</body>
</html>
