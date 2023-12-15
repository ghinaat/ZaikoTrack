@extends('layouts.demo')
@section('title', 'List Inventaris')
@section('css')
<link rel="stylesheet" href="{{asset('fontawesome-free-6.4.2-web\css\all.min.css')}}">
@endsection
@section('breadcrumb-name')
Inventaris
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h4 class="m-0 text-dark">List Inventaris</h4>
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
                                    <th>Ruangan</th>
                                    <th>List Barang</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inventaris as $key => $inventaris)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$inventaris->ruangan->nama_ruangan}}</td>
                                    <td>
                                        <a href="{{ route('inventaris.showDetail', $inventaris->id_ruangan) }}"
                                            class="btn btn-info btn-xs mx-1">
                                            <i class="fa fa-rectangle-list"></i>
                                        </a>

                                    </td>
                                    <td>
                                        <a href="{{ route('inventaris.destroyRuangan', $inventaris->id_ruangan) }}"
                                            onclick="notificationBeforeDelete(event, this, {{$key+1}})"
                                            class="btn btn-danger btn-xs mx-1">
                                            <i class="fa fa-trash"></i>
                                        </a>


                                    </td>
                                </tr>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach
    </tbody>
    </table>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Inventaris</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class=" modal-body">
                <form id="addForm" action="{{ route('inventaris.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="id_barang">Nama Barang</label>
                        <select class="form-select" name="id_barang" id="id_barang" required>
                            @foreach($barang as $key => $b)
                            <option value="{{$b->id_barang}}" @if( old('id_barang')==$b->
                                id_barang)selected @endif>
                                {{$b->nama_barang}}
                            </option>
                            @endforeach
                        </select>
                        @error('id_barang')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="id_ruangan">Ruangan</label>
                        <select class="form-select" name="id_ruangan" id="id_ruangan" required>
                            @foreach($ruangan as $key => $r)
                            <option value="{{$r->id_ruangan}}" @if( old('id_ruangan')==$r->
                                id_ruangan)selected @endif>
                                {{$r->nama_ruangan}}
                            </option>
                            @endforeach
                        </select>
                        @error('id_ruangan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jumlah_barang">Stok Barang</label>
                        <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" required>
                        @error('jumlah_barang')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputkondisi_barang">Kondisi Barang</label>
                        <select class="form-select @error('kondisi_barang') is-invalid @enderror"
                            id="exampleInputkondisi_barang" name="kondisi_barang">
                            <option value="lengkap" @if( old('kondisi_barang')=='lengkap' )selected @endif>lengkap
                            </option>
                            <option value="tidak_lengkap" @if( old('kondisi_barang')=='tidak_lengkap' )selected @endif>
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
                        <label for="ket_barang">Keterangan Barang</label>
                        <input type="text" name="ket_barang" id="ket_barang" class="form-control">
                        <small class="form-text text-muted">*wajib diisi ketika
                            barang tidak lengkap/rusak. </small>
                        @error('ket_barang')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
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

@endpush