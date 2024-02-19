@extends('layouts.demo')
@section('title', 'List Karyawan')
@section('css')
@endsection
@section('breadcrumb-name')
Karyawan
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h4 class="m-0 text-dark">List Karyawan</h4>
                </div>
                <div class="card-body m-0">
                    <div class="d-flex">
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-primary mb-2" data-toggle="modal"
                                data-target="#addModal">Tambah</button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <form action="{{ route('karyawan.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="d-md-flex justify-content-md-end">
                                    <div class="form-group ">
                                        <input type="file" name="file" id="file" class="form-control">
                                    </div>
                                    <div class="mb-2 mb-md-0">
                                        <button type="submit" class="btn btn-success">Import</button>
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
                                    <th style="width:189px;">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($karyawan as $key => $karyawan)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$karyawan->nama_karyawan}}</td>
                                    <td>
                                        @include('components.action-buttons', ['id' => $karyawan->id_karyawan, 'key' =>
                                        $key,
                                        'route' => 'karyawan'])
                                    </td>
                                </tr>
                                <!-- Modal Edit Pegawai -->
                                <div class="modal fade" id="editModal{{$karyawan->id_karyawan}}" tabindex="-1"
                                    role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
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
                                                <form action="{{ route('karyawan.update', $karyawan->id_karyawan)}}"
                                                    method="post">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="nama_karyawan">Nama Siswa</label>
                                                        <input type="text" name="nama_karyawan" id="nama_karyawan"
                                                            class="form-control"
                                                            value="{{ old('nama_karyawan', $karyawan->nama_karyawan) }}"
                                                            required>
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
                <form id="addForm" action="{{route('karyawan.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="nama_karyawan">Nama Siswa</label>
                        <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control"
                            value="{{ old('nama_karyawan') }}" required>
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