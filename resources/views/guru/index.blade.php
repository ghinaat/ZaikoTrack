@extends('layouts.demo')
@section('title', 'List Guru')
@section('css')
@endsection
@section('breadcrumb-name')
Guru
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h4 class="m-0 text-dark">List Guru</h4>
                </div>
                <div class="card-body m-0">
                    <div class="d-flex">
                        <div class="col-4 col-md-6 mb-2">
                            <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#addModal">Tambah</button>
                        </div>
                        <div class="col-8 col-md-6 mb-2">
                            <form action="{{ route('guru.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="d-flex justify-content-md-end">
                                    <div class="mr-2">
                                        <input type="file" name="file" id="file" class="form-control" accept=".xls, .xlsxs">
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-success">Import</button>
                                    </div>
                                </div>
                            
                        </div>
                    </div>
                    <div class="table-responsive ">
                        <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>NIP</th>
                                    <th>Nama Guru</th>
                                    <th style="width:189px;">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($guru as $key => $guru)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$guru->nip}}</td>
                                    <td>{{$guru->nama_guru}}</td>
                                    <td>
                                        @include('components.action-buttons', ['id' => $guru->id_guru, 'key' =>
                                        $key,
                                        'route' => 'guru'])
                                    </td>
                                </tr>
                                <!-- Modal Edit Pegawai -->
                                <div class="modal fade" id="editModal{{$guru->id_guru}}" tabindex="-1" role="dialog"
                                    aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Guru</h5>
                                                <button type="button" class="btn-close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <i class="fa fa-close" style="color: black;"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('guru.update', $guru->id_guru)}}"
                                                    method="post">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="nip">NIP</label>
                                                        <input type="number" name="nip" id="nip" class="form-control"
                                                            value="{{ old('nis', $guru->nip) }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="nama_guru">Nama Guru</label>
                                                        <input type="text" name="nama_guru" id="nama_guru" class="form-control"
                                                            value="{{ old('nama_guru', $guru->nama_guru) }}" required>
                                                        @error('nama_guru')
                                                            <span class="text-danger">{{$message}}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                        <button type="button" class="btn btn-danger"
                                                            data-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Guru</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class=" modal-body">
                <form id="addForm" action="{{route('guru.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="number" name="nip" id="nip" class="form-control"
                            value="{{ old('nip') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_guru">Nama Guru</label>
                        <input type="text" name="nama_guru" id="nama_guru" class="form-control" 
                            value="{{ old('nama_guru') }}" required>
                        @error('nama_guru')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
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
@if(count($errors))
<script>
Swal.fire({
    title: 'Input tidak sesuai!',
    text: 'Pastikan inputan sudah sesuai',
    icon: 'error',
});
</script>
@endif
@endpush