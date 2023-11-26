@extends('layouts.demo')
@section('title', 'List Barang')
@section('css')
@endsection
@section('breadcrumb-name')
Barang
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-header">
                    <h4 class="m-0 text-dark">List Barang</h4>
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
                                    <th>Nama Barang</th>
                                    <th>Merek</th>
                                    <th>Stok Barang</th>
                                    <th>Jenis Barang</th>
                                    <th style="width:189px;">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barang as $key => $br)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$br->nama_barang}}</td>
                                    <td>{{$br->merek}}</td>
                                    <td>{{$br->stok_barang}}</td>
                                    <td>{{$br->jenisbarang->nama_jenis_barang}}</td>
                                    <td>
                                        @include('components.action-buttons', ['id' => $br->id_barang, 'key' => $key,
                                        'route' => 'barang'])
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
                <h5 class="modal-title" id="editModalLabel">Tambah barang</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm" action="{{route('barang.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="nama_barang">Nama Barang</label>
                        <input type="text" name="nama_barang" id="nama_barang" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="merek">Merek</label>
                        <input type="text" name="merek" id="merek" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="stok_barang">Stok Barang</label>
                        <input type="number" name="stok_barang" id="stok_barang" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis_barang">Jenis Barang</label>
                        <select class="form-select" name="id_jenis_barang" id="id_jenis_barang" required>
                            @foreach($jenisBarang as $key => $jb)
                            <option value="{{$jb->id_jenis_barang}}" @if( old('id_jenis_barang')==$jb->
                                id_jenis_barang)selected @endif>
                                {{$jb->nama_jenis_barang}}
                            </option>
                            @endforeach
                        </select>
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

@foreach($barang as $key => $br)
<div class="modal fade" id="editModal{{$br->id_barang}}" tabindex="-1" role="dialog"
    aria-labelledby="editModalLabel{{$br->id_barang}}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Jenis Barang</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm" action="{{route('barang.update', $br->id_barang)}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama_barang">Nama Barang</label>
                        <input type="text" name="nama_barang" id="nama_barang" class="form-control"
                            value="{{old('nama_barang', $br->nama_barang)}}">
                    </div>
                    <div class="form-group">
                        <label for="merek">Merek</label>
                        <input type="text" name="merek" id="merek" class="form-control"
                            value="{{old('merek', $br->merek)}}" required>
                    </div>
                    <div class="form-group">
                        <label for="stok_barang">Stok Barang</label>
                        <input type="number" name="stok_barang" id="stok_barang" class="form-control"
                            value="{{old('stok_barang', $br->stok_barang)}}" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis_barang">Jenis Barang</label>
                        <select class="form-select" name="id_jenis_barang" id="id_jenis_barang" required>
                            @foreach($jenisBarang as $key => $jb)
                            <option value="{{$jb->id_jenis_barang}}" @if( old('id_jenis_barang')==$jb->
                                id_jenis_barang)selected @endif>
                                {{$jb->nama_jenis_barang}}
                            </option>
                            @endforeach
                        </select>
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
</script>
@endpush