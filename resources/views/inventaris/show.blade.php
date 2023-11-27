@extends('layouts.demo')
@section('title', 'List Barang')
@section('css')
<style>
.table-container {
    display: flex;
    justify-content: center;
    /* Centers the element horizontally */
    align-items: center;
    /* Centers the element vertically */
    width: 100%;
}
</style>
@endsection
@section('breadcrumb-name')
List Barang
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h4 class="m-0 text-dark">
                        Ruangan: {{$ruangan->nama_ruangan}} </h4>
                </div>
                <div class="card-body m-0">
                    <div class="card-body m-0">
                        <div class="table-container">
                            <div class="table-responsive">
                                <div class="mb-2">
                                    <button class="btn btn-primary mb-2" data-toggle="modal"
                                        data-target="#addModal">Tambah</button>
                                </div>
                                <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Barang</th>
                                            <th>Stok</th>
                                            <th>Kondisi</th>
                                            <th style="width:130px;">Keterangan</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($inventaris)
                                        @foreach($inventaris as $key => $barang)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$barang->barang->nama_barang}}</td>
                                            <td>{{$barang->jumlah_barang}}</td>
                                            <td>
                                                @if($barang->kondisi_barang == 'rusak')
                                                <span class="badge bg-gradient-danger">Rusak</span>
                                                @elseif($barang->kondisi_barang == 'tidak_lengkap')
                                                <span class="badge bg-gradient-secondary">Tidak Lengkap</span>
                                                @else
                                                <span class="badge bg-gradient-success">Lengkap</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($barang->ket_barang)
                                                {{$barang->ket_barang}}
                                                @else
                                                <div style='display: flex; justify-content: center;'>
                                                    <span>-</span>
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                @include('components.action-buttons', ['id' => $barang->id_inventaris,
                                                'key'
                                                =>
                                                $key, 'route' => 'inventaris'])
                                            </td>
                                        </tr>
                                        <!-- Modal Edit Pegawai -->
                                        <div class="modal fade" id="editModal{{$barang->id_inventaris}}" tabindex="-1"
                                            role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
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
                                                        <form
                                                            action="{{ route('inventaris.update', $barang->id_inventaris)}}"
                                                            method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="id_ruangan" id="id_ruangan"
                                                                class="form-control" value="{{ $ruangan->id_ruangan }}">
                                                            <div class="form-group">
                                                                <label for="id_barang">Nama Barang</label>
                                                                <select class="form-select" name="id_barang"
                                                                    id="id_barang" required>
                                                                    @foreach($barangEdit->all() as $id_barang =>
                                                                    $nama_barang)
                                                                    <option value="{{ $id_barang }}" @if($barang->
                                                                        id_barang
                                                                        == $id_barang) selected @endif>
                                                                        {{ $nama_barang }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('id_barang')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="jumlah_barang">Stok Barang</label>
                                                                <input type="number" name="jumlah_barang"
                                                                    id="jumlah_barang" class="form-control"
                                                                    value="{{old('jumlah_barang', $barang->jumlah_barang)}}"
                                                                    required>
                                                                @error('jumlah_barang')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputkondisi_barang">Kondisi
                                                                    Barang</label>
                                                                <select
                                                                    class="form-select @error('kondisi_barang') is-invalid @enderror"
                                                                    id="exampleInputkondisi_barang"
                                                                    name="kondisi_barang">
                                                                    <option value="lengkap" @if($barang->kondisi_barang
                                                                        ==
                                                                        'lengkap' ||
                                                                        old('kondisi_barang')=='lengkap'
                                                                        )selected @endif>
                                                                        Lengkap
                                                                    </option>
                                                                    <option value="tidak_lengkap" @if($barang->
                                                                        kondisi_barang
                                                                        ==
                                                                        'tidak_lengkap' ||
                                                                        old('kondisi_barang')=='tidak_lengkap'
                                                                        )selected @endif>
                                                                        Tidak Lengkap
                                                                    </option>
                                                                    <option value="rusak" @if($barang->kondisi_barang
                                                                        ==
                                                                        'rusak' ||
                                                                        old('kondisi_barang')=='rusak'
                                                                        )selected @endif>
                                                                        Rusak
                                                                    </option>
                                                                </select>
                                                                @error('kondisi_barang')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="ket_barang">Ketarangan Barang</label>
                                                                <input type="text" name="ket_barang" id="ket_barang"
                                                                    class="form-control"
                                                                    value="{{old('ket_barang', $barang->ket_barang)}}">
                                                                <small class="form-text text-muted">*wajib diisi ketika
                                                                    barang tidak lengkap/rusak. </small>
                                                                @error('ket_barang')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                                @enderror
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit"
                                                                    class="btn btn-primary">Simpan</button>
                                                                <button type="button" class="btn btn-danger"
                                                                    data-dismiss="modal">Batal</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{route('inventaris.index')}}" class="btn btn-primary ">
                            Kembali
                        </a>
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
                <h5 class="modal-title" id="addModalLabel">Tambah Inventaris</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class=" modal-body">
                <form id="addForm" action="{{ route('inventaris.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_ruangan" id="id_ruangan" class="form-control"
                        value="{{ $ruangan->id_ruangan }}">
                    <div class="form-group">
                        <label for="id_barang">Nama Barang</label>
                        <select class="form-select" name="id_barang" id="id_barang" required>
                            @foreach($barangs as $key => $br)
                            <option value="{{$br->id_barang }}" @if( old('id_barang')==$br->id_barang)selected @endif>
                                {{$br->nama_barang}}
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
                            <option value="lengkap" @if( old('kondisi_barang')=='lengkap' )selected @endif>Lengkap
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