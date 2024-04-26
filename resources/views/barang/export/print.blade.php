<!DOCTYPE html>
<html lang="en">
<body>
    @foreach($barang as $key => $br)
    <div class="form-group d-flex">
        <img src="{{asset('/storage/qrcode/' . $br->qrcode_image)}}">
        @foreach($br->detailPembelian as $detail)
        <p>{{ $br->kode_barang }}/{{ $detail->pembelian->tgl_pembelian }}</p>       
        @endforeach
    </div>
    @endforeach
</body>
</html>

