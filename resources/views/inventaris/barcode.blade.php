@extends('layouts.demo')
@section('title', 'Tambah Barang')
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
Tambah Barang
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="m-0 text-dark text-center fs-3">Scan Barcode
                    </h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('inventaris.addBarcode', ['id_ruangan' => $id_ruangan->id_ruangan]) }}">
                        @csrf
                        @method('PUT') 
                        <div class="form-group">
                            <div class="responsive-video-wrapper">
                                <video id="previewKamera" style="width: 100%; height: auto;"></video>
                            </div>
                            <br>
                            <br>
                            <label for="ket_barang">Kode Barang</label>
                            <input type="text" id="hasilscan" name="kode_barang"  class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama_barang">Nama Barang</label>
                            <input type="text" name="nama_barang" id="nama_barang" class="form-control"
                            value="{{old('nama_barang')}}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputkondisi_barang">Kondisi Barang</label>
                            <select class="form-select @error('kondisi_barang') is-invalid @enderror"
                                id="exampleInputkondisi_barang" name="kondisi_barang">
                                <option value="lengkap" @if( old('kondisi_barang')=='lengkap' )selected @endif>Lengkap
                                </option>
                                <option value="tidak_lengkap" @if( old('kondisi_barang')=='tidak_lengkap' )selected
                                    @endif>
                                    Tidak Lengkap
                                </option>
                                <option value="rusak" @if( old('kondisi_barang')=='rusak' )selected @endif>Rusak
                                </option>
                            </select>
                            @error('kondisi_barang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="ket_barang">Ketarangan Barang</label>
                            <input type="text" name="ket_barang" id="ket_barang" class="form-control"
                                value="{{old('ket_barang')}}">
                            <small class="form-text text-muted">*wajib diisi
                                ketika
                                barang tidak lengkap/rusak. </small>
                            @error('ket_barang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="button-row d-flex justify-content-end mt-4">
                            <button class="btn btn-secondary mr-4" type="button" onclick="history.go(-1);" title="Prev">Batal</button>
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