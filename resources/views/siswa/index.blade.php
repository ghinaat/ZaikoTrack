@extends('layouts.demo')
@section('title', 'List Siswa')
@section('css')
@endsection
@section('breadcrumb-name')
Siswa
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h4 class="m-0 text-dark">List Siswa</h4>
                </div>
                <div class="card-body m-0">
                    <div class="mb-2">
                        <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#addModal">Tambah</button>
                    </div>
                    <div class="d-flex">
                        <div class="col-md-6 mb-3">
                            <form action="{{ route('presensi.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input type="file" name="file" id="file" class="form-control">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Import</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive ">
                        <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Siswa</th>
                                    <th>NIS</th>
                                    <th style="width:189px;">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswa as $key => $siswa)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$siswa->nama_siswa}}</td>
                                    <td>{{$siswa->nis}}</td>
                                    <td>
                                        @include('components.action-buttons', ['id' => $siswa->id_siswa, 'key' =>
                                        $key,
                                        'route' => 'siswa'])
                                    </td>
                                </tr>
                                <!-- Modal Edit Pegawai -->
                                <div class="modal fade" id="editModal{{$siswa->id_siswa}}" tabindex="-1" role="dialog"
                                    aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Siswa</h5>
                                                <button type="button" class="btn-close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <i class="fa fa-close" style="color: black;"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('siswa.update', $siswa->id_siswa)}}"
                                                    method="post">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="nama_siswa">Nama Siswa</label>
                                                        <input type="text" name="nama_siswa" id="nama_siswa"
                                                            class="form-control"
                                                            value="{{ old('nama_siswa', $siswa->nama_siswa) }}"
                                                            required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="nis">NIS</label>
                                                        <input type="number" name="nis" id="nis" class="form-control"
                                                            value="{{ old('nis', $siswa->nis) }}" required>
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
                <h5 class="modal-title" id="addModalLabel">Tambah Siswa</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class=" modal-body">
                <form id="addForm" action="{{route('siswa.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="nama_siswa">Nama Siswa</label>
                        <input type="text" name="nama_siswa" id="nama_siswa" class="form-control"
                            value="{{ old('nama_siswa') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nis">NIS</label>
                        <input type="number" name="nis" id="nis" class="form-control" required>
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
@endpush