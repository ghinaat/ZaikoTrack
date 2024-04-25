<!DOCTYPE html>
<html lang="en">
<body>
    @foreach($barang as $key => $br)
        
    <div class="form-group d-flex">
        <img src="{{asset('/storage/barcode/' . $br->qrcode_image)}}">
        @foreach($br->detailPembelian as $detail)
        <p>{{ $br->kode_barang }}/{{ $detail->pembelian->tgl_pembelian }}</p>       
        @endforeach
    </div>
    @endforeach

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
</html>a
