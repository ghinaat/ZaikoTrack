@extends('layouts.demo')
@section('title', 'List Jenis Barang')
@section('css')
@endsection
@section('breadcrumb-name')
Jenis Barang
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-header">
                    <h4 class="m-0 text-dark">List Jenis Barang</h4>
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
                                    <th>Jenis Barang</th>
                                    <th style="width:189px;">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jenisBarang as $key => $jb)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$jb->nama_jenis_barang}}</td>
                                    <td>
                                        @include('components.action-buttons', ['id' => $jb->id_jenis_barang, 'key' => $key,
                                        'route' => 'jenisbarang'])
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
                <h5 class="modal-title" id="editModalLabel">Tambah Jenis barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm" action="{{route('jenisbarang.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name">Jenis Barang</label>
                        <input type="text" name="nama_jenis_barang" id="nama_jenis_barang" class="form-control" required>
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

@foreach($jenisBarang as $key => $jb)
    
<div class="modal fade" id="editModal{{$jb->id_jenis_barang}}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{$jb->id_jenis_barang}}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Jenis barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm" action="{{route('jenisbarang.update', $jb->id_jenis_barang)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Jenis Barang</label>
                        <input type="text" name="nama_jenis_barang" id="nama_jenis_barang" 
                        class="form-control" value="{{ old('nama_jenis_barang', $jb->nama_jenis_barang) }}" required>
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