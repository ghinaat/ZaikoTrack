<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ public_path('/css/label.css') }}">
    <style>
        .label {
            width: 6cm; /* Mengatur lebar maksimal menjadi 6 cm */
            height: auto; /* Mengatur tinggi agar otomatis */
            display: inline-block; /* Membuat label dalam satu baris */
            border: 0.8px solid black; /* Tambahkan border untuk memisahkan label */
            padding-top: 3px;
            padding-bottom: 3px;
            box-sizing: border-box; /* Memastikan padding tidak menambah ukuran elemen */
            position: relative; /* Tambahkan posisi relatif untuk penyesuaian posisi */
            /* margin-bottom: -1.8px */
        }
        .label-content {
            display: flex; /* Menggunakan flexbox untuk tata letak internal */
            justify-content: space-between; /* Memisahkan elemen dengan jarak yang konsisten */
            align-items: center; /* Menyelaraskan elemen secara vertikal ke tengah */
            width: 100%; /* Memastikan elemen mengambil seluruh lebar kontainer */
            padding: 0.4cm;
        }
        .kodeTahun {
            text-align: start;
            display: inline-block;
            vertical-align: baseline; /* Menyelaraskan secara vertikal ke tengah */
            max-width: 4cm;
            margin-right: 0.3cm;
        }
        .kodeTahun p {
            margin: 0; /* Menghilangkan margin default dari elemen <p> */
            line-height: 1.2; /* Mengatur tinggi baris untuk mengurangi jarak antar <p> */
            word-wrap: break-word; /* Membungkus kata yang panjang */
        }
        .qr {
            text-align: right;
            display: inline-block;
            vertical-align: middle; /* Menyelaraskan secara vertikal ke tengah */
            padding-right: 20px;
            vertical-align: top; /* Menyelaraskan elemen di atas */
        }
        .qr img {
            vertical-align: middle; /* Menyelaraskan gambar secara vertikal di tengah */
        }
        </style>
</head>
<body>
    <table class="w-full">
        @php $count = 0 @endphp <!-- Menghitung jumlah item yang ditampilkan -->
        @foreach($barang as $key => $br)
            @if($count % 3 == 0) <!-- Mulai baris baru setiap 2 item -->
                <tr>
            @endif
            <td>
            <div class="label">
                <div class="label-content">
                    <div class="w-half qr">
                        <img src="{{ public_path('/storage/qrcode/' . $br->qrcode_image) }}" style="width: 1.7cm;">
                    </div>
                    <div class="w-half kodeTahun" style="font-size: 18px;">
                        <p>{{ $br->kode_barang }}</p>
                        <p>{{ $br->nama_barang }}</p>
                    </div>
                    
                </div>
            </div>
            </td>
            @php $count++ @endphp <!-- Menambah hitungan item -->
            @if($count % 3 == 0 || $loop->last) <!-- Menutup baris setiap 2 item atau pada item terakhir -->
                </tr>
            @endif
        @endforeach
    </table>
</body>
</html>
