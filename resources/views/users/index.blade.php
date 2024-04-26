@extends('layouts.demo')
@section('title', 'List User')
@section('css')
@endsection
@section('breadcrumb-name')
Users
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h4 class="m-0 text-dark">List User</h4>
                </div>
                <div class="card-body m-0">
                    <div class="d-flex">
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-primary mb-2" data-toggle="modal"
                                data-target="#addModal">Tambah</button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <form action="{{ route('user.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="d-md-flex justify-content-md-end">
                                    <div class="form-group ">
                                        <input type="file" name="file" id="file" class="form-control">
                                    </div>
                                    <div class="mb-2 mb-md-0">
                                        <button type="submit" class="btn btn-success" id="importButton">Import</button>
                                        <p><a href="https://docs.google.com/spreadsheets/d/1rZCIr4wTUS9CO8xWw6ktgn4RxlmsGigmyFVzbgqh4TI/edit#gid=0">Format Excel</a></p>
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
                                    <th>Nama User</th>
                                    <th>Email</th>
                                    <th>level</th>
                                    <th style="width:189px;">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user as $key => $user)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->level}}</td>
                                    <td>
                                        @include('components.action-buttons', ['id' => $user->id_users, 'key' => $key,
                                        'route' => 'user'])
                                    </td>
                                </tr>
                                <!-- Modal Edit User -->
                                <div class="modal fade" id="editModal{{$user->id_users}}" tabindex="-1" role="dialog"
                                    aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                                                <button type="button" class="btn-close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <i class="fa fa-close" style="color: black;"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('user.update', $user->id_users)}}" method="post">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="name">Nama User</label>
                                                        <input type="text" name="name" id="name" class="form-control"
                                                            value="{{ old('name', $user->name) }}" required>
                                                    </div>
                                                    <div class=" form-group">
                                                        <label for="exampleInputEmail">Email</label>
                                                        <input type="email" name="email" id="exampleInputEmail"
                                                            class="form-control"
                                                            value="{{ old('email', $user->email) }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputlevel">Level</label>
                                                        <select class="form-select @error('level') is-invalid @enderror"
                                                            id="exampleInputlevel" name="level">
                                                            <option value="teknisi" @if($user->level == 'teknisi' ||
                                                                old('level')=='teknisi'
                                                                )selected @endif>Teknisi</option>
                                                            <option value="kaprog" @if($user->level == 'kaprog'
                                                                ||old('level')=='kaprog' )selected
                                                                @endif>Kepala Program
                                                            </option>
                                                            <option value="kabeng" @if($user->level == 'kabeng' ||
                                                                old('level')=='kabeng' )selected
                                                                @endif>Kepala Bengkel
                                                            </option>
                                                            <option value="siswa" @if($user->level == 'siswa' ||
                                                                old('level')=='siswa' )selected
                                                                @endif>Siswa
                                                            </option>
                                                        </select>
                                                        @error('level')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="nis">NIS</label>
                                                        <input type="text" name="nis"
                                                            id="exampleInputPassword" class="form-control"   value="{{ old('nis', $user->nis) }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword">Password</label>
                                                        <input type="password" name="password" id="exampleInputPasword"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="password_confirmation">Konfirmasi Password</label>
                                                        <input type="password" name="password_confirmation"
                                                            id="exampleInputPassword" class="form-control" required>
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
                <h5 class="modal-title" id="addModalLabel">Tambah User</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class=" modal-body">
                <form id="addForm" action="{{route('user.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama User</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail">Email</label>
                        <input type="email" name="email" id="exampleInputEmail" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputlevel">Level</label>
                        <select class="form-select @error('level') is-invalid @enderror" id="exampleInputlevel"
                            name="level">
                            <option value="teknisi" @if( old('level')=='teknisi' )selected @endif>Teknisi
                            </option>
                            <option value="kaprog" @if( old('level')=='kaprog' )selected @endif>Kepala Program
                            </option>
                            <option value="kabeng" @if( old('level')=='kabeng' )selected @endif>Kepala Bengkel
                            </option>
                            <option value="siswa" @if( old('level')=='siswa' )selected @endif>Siswa
                            </option>
                        </select>
                        @error('level')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nis">NIS</label>
                        <input type="text" name="nis" id="nis"
                            class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword">Password</label>
                        <input type="password" name="password" id="exampleInputPasword" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="exampleInputPassword"
                            class="form-control" required>
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
document.getElementById('importButton').addEventListener('click', function(event) {
    var fileInput = document.getElementById('file');
    if (fileInput.files.length === 0) {
        event.preventDefault(); // Prevent form submission
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Masukkan file import terlebih dahulu!',
        });
    }
});

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