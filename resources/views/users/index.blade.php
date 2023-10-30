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
                    <div class="mb-2">
                        <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#addModal">Tambah</button>
                    </div>
                    <div class="table-responsive ">
                        <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama User</th>
                                    <th>Email</th>
                                    <th style="width:189px;">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user as $key => $user)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>
                                        @include('components.action-buttons', ['id' => $user->id_users, 'key' => $key,
                                        'route' => 'user'])
                                    </td>
                                </tr>
                                <!-- Modal Edit Pegawai -->
                                <div class="modal fade" id="editModal{{$user->id_users}}" tabindex="-1" role="dialog"
                                    aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Pegawai</h5>
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
                                                        </select>
                                                        @error('level')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                        @enderror
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
    <div class="modal-dialog modal-lg" role="document">
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
                        </select>
                        @error('level')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
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