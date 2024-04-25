@extends('layouts.demo')
@section('title', 'Peminjaman Barang')
@section('css')
<link rel="stylesheet" href="{{asset('css\kamera.css')}}">
<style>
.btn-secondary{
  margin-right: 10px;
}

.btn-danger{
  margin-right: 10px;
}
</style>
@endsection
@section('breadcrumb-name')
/ Peminjaman / Tambah Barang
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="m-0 text-dark text-center fs-3">Scan Barcode
                    </h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('detailPeminjaman.AddQrcode', ['id_peminjaman' => $id_peminjaman->id_peminjaman]) }}" method="POST">                        
                        @csrf

                        <div class="form-group">
                            <div class="responsive-video-wrapper">
                                <video id="previewKamera" style="width: 100%; height: auto;"></video>
                            </div>
                            <br>
                           
                            <br>
                            <label for="ket_barang">Kode Barang</label>
                            <input type="text" id="hasilscan" name="kode_barang"  class="form-control" readonly>
                        </div>
                       
                        <div class="form-group mt-2">
                            <label for="ket_barang">Keterangan Barang</label>
                            <input type="text" name="ket_barang" id="ket_barang" class="form-control">
                            <small class="form-text text-muted">*wajib diisi ketika
                                barang tidak lengkap/rusak. </small>
                            @error('ket_barang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>
                    
                        <div class="button-row d-flex justify-content-end mt-4">
                            <a href="{{ route('peminjaman.showDetail', $id_peminjaman) }}" class="btn btn-danger ml-3">
                                Batal
                            </a>        
                            <button class="btn btn-primary" type="submit" title="Prev">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop


<script type="text/javascript" src="https://unpkg.com/@zxing/library@latest"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="{{ asset('js/barcode-scanner.js') }}"></script>


@push('js')
<script>

</script>
@endpush