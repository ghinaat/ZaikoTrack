@extends('layouts.demo')
@section('title', 'List User')
@section('css')

@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-header">
                    <h4 class="m-0 text-dark">List User</h4>
                </div>
                <div class="card-body m-0">
                    <div class="mb-2">
                        <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#addModal">Tambah</button>
                    </div>
                    <div class="table-responsive p-0">
                        <table id="myTable" class="table table-bordered table-striped align-items-center mb-0" id="myTable ">
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<form action="" id="delete-form" method="post">
    @method('delete')
    @csrf
</form>
<script>
 $(document).ready( function () {
    $('#myTable').DataTable();
} );

</script>
@endpush