@extends('layouts.demo')
@section('title', 'List Pembelian')
@section('css')
@endsection
@section('breadcrumb-name')
Pembelian 
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-header">
                    <h4 class="m-0 text-dark">List Pembelian</h4>
                </div>
                <div class="card-body m-0">
                    <div class="mb-2">
                        <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#addModal">Tambah</button>
                    </div>
                    <div class="table-responsive ">
                        <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal Pembelian</th>
                                    <th>Nama Toko</th>
                                    <th>Total Pembelian</th>
                                    <th>Stok Barang</th>
                                    <th>Keterangan Anggaran</th>
                                    <th>Nota Pembelian</th>
                                    <th style="width:189px;">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pembelian as $key => $pb)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{\Carbon\Carbon::parse($pb->tgl_pembelian)->format('d F Y')}}</td>
                                    <td>{{$pb->nama_toko}}</td>
                                    <td>Rp {{number_format($pb->total_pembelian, 0, ',', '.')}}</td>
                                    <td>{{$pb->stok_barang}}</td>
                                    <td>{{$pb->keterangan_anggaran}}</td>
                                    <td  style="text-align: center; vertical-align: middle;">
                                        <a href="{{ asset('storage/nota_pembelian/' . $pb->nota_pembelian) }}" target="_blank">
                                            <i class="ni ni-folder-17 text-lg" style="display: inline-block; line-height: normal; vertical-align: middle;"></i>
                                        </a>
                                    </td>
                                    <td>
                                        @include('components.action-buttons', ['id' => $pb->id_pembelian, 'key' => $key,
                                        'route' => 'pembelian'])
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Tambah Pemelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm" action="{{route('pembelian.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="tgl_pembelian">Tanggal Pembelian</label>
                        <input type="date" name="tgl_pembelian" id="tgl_pembelian" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_toko">Nama Toko</label>
                        <input type="text" name="nama_toko" id="nama_toko" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="total_pembelian">Total Pembelian</label>
                        <input type="number" name="total_pembelian" id="total_pembelian" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="stok_barang">Stok Barang</label>
                        <input type="number" name="stok_barang" id="stok_barang" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan_anggaran">Keterangan Anggaran</label>
                        <textarea rows="3" type="date" name="keterangan_anggaran" id="keterangan_anggaran" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="nota_pembelian" class="form-label">Nota Pembelian</label>
                        <a href="{{asset('storage/nota_pembelian/')}}" target="_blank">
                            <img class="img-preview img-fluid mb-1 col-sm-3 d-block">
                        </a>
                        <input class="form-control @error('nota_pembelian') is-invalid @enderror" type="file" id="nota_pembelian"
                        name="nota_pembelian" onchange="previewImage()" accept="image/jpeg, image/jpg, image/png">
                        <small class="form-text text-muted">Allow file extensions : .jpeg .jpg .png</small>
                        @error('nota_pembelian') <span class="textdanger">{{$message}}</span> @enderror
                    </div>                   
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@foreach($pembelian as $key => $pb)
    
<div class="modal fade" id="editModal{{$pb->id_pembelian}}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{$pb->id_pembelian}}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Pembelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm" action="{{route('pembelian.update', $pb->id_pembelian)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="tgl_pembelian">Tanggal Pembelian</label>
                        <input type="date" name="tgl_pembelian" id="tgl_pembelian" class="form-control" 
                            value="{{old('tgl_pembelian', $pb->tgl_pembelian)}}" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_toko">Nama Toko</label>
                        <input type="text" name="nama_toko" id="nama_toko" class="form-control" 
                            value="{{old('nama_toko', $pb->nama_toko)}}" required>
                    </div>
                    <div class="form-group">
                        <label for="total_pembelian">Total Pembelian</label>
                        <input type="number" name="total_pembelian" id="total_pembelian" class="form-control" 
                            value="{{old('total_pembelian', $pb->total_pembelian)}}" required>
                    </div>
                    <div class="form-group">
                        <label for="stok_barang">Stok Barang</label>
                        <input type="number" name="stok_barang" id="stok_barang" class="form-control" 
                        value="{{old('stok_barang', $pb->stok_barang)}}" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan_anggaran">Keterangan Anggaran</label>
                        <textarea rows="3" type="date" name="keterangan_anggaran" id="keterangan_anggaran" 
                            class="form-control" required>{{old('keterangan_anggaran', $pb->keterangan_anggaran)}}</textarea>
                    </div>
                    {{-- <div class="form-group">
                        <label for="nota_pembelian">Nota Pembelian</label>
                        <a href="{{asset('storage/nota_pembelian/' . $pb->nota_pembelian)}}" target="_blank">
                            <img src="{{asset('storage/nota_pembelian/' . $pb->nota_pembelian)}}"   
                            class="img-thumbnail d-block mb-3" name="nota_pembelian_tampil" id="nota_pembelian_tampil" alt="Nota Pembelian" width="25%" >
                        </a>
                        <input type="file" name="nota_pembelian" id="nota_pembelian" class="form-control" 
                        accept="image/jpeg, image/jpg, image/png">
                        <small class="form-text text-muted">Allow file extensions : .jpeg .jpg .png</small>
                    </div> --}}
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@stop
@push('js')

<form action="" id="delete-form" method="post">
    @method('delete')
    @csrf
</form>
<script>
$(document).ready(function() {
    $('#myTable').DataTable({
        "responsive": true,
        "language": {
            "paginate": {
                "previous": "<",
                "next": ">"
            }
        }
    });
});


function previewImage() {
        const foto = document.querySelector('#nota_pembelian');
        const imgPreview = document.querySelector('.img-preview');

        imgPreview.style.display = 'block';

        const ofReader = new FileReader();
        ofReader.readAsDataURL(foto.files[0]);

        ofReader.onload = function(oFREvent) {
            imgPreview.src = oFREvent.target.result;
        }
    }</script>


@endpush