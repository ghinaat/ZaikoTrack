@extends('layouts.demo')
@section('title', 'List Pemakaian')
@section('css')
<link rel="stylesheet" href="{{ asset('css/pemakaian.css') }}">

@endsection
@section('breadcrumb-name')
Pemakaian
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-header pb-0">
                    <h4 class="m-0 text-dark">List Pemakaian</h4>
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
                                    <th>Jumlah Barang</th>
                                    <th>Nama Pemakai</th>
                                    <th>Kelas</th>
                                    <th>Jurusan</th>
                                    <th>Tanggal Pemakaian</th>
                                    <th>Keterangan</th>
                                    <th style="width:189px;">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pemakaian as $key => $pakai)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$pakai->inventaris->barang->nama_barang}}</td>
                                    <td>{{$pakai->jumlah_barang}}</td>
                                    <td>{{$pakai->nama_lengkap}}</td>
                                    <td>{{$pakai->kelas}}</td>
                                    <td>{{$pakai->jurusan}}</td>
                                    <td>{{$pakai->tgl_pakai}}</td>
                                    <td>{{$pakai->keterangan_pemakaian}}</td>
                                    <td>
                                        @include('components.action-buttons', ['id' => $pakai->id_pemakaian, 'key' => $key,
                                        'route' => 'pemakaian'])
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
                <h5 class="modal-title" id="editModalLabel">Tambah Pemakaian</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            

  <!--PEN CONTENT     -->
  <div class="content">
    <!--content inner-->
    <div class="content__inner">
        <div class="container">
            <!--content title-->
            <h1 class="content__title mt-4">Form Pemakaian</h1>
        </div>
        <div class="container overflow-hidden">
            <!--multisteps-form-->
            <div class="multisteps-form">
            <!--progress bar-->
            <div class="row">
                <div class="col-12 col-lg-8 mx-auto mb-4">                            
                    <div class="multisteps-form__progress">
                        {{-- <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">User Info</button> --}}
                        <button class="multisteps-form__progress-btn js-active" type="button" title="Address">Data Pribadi</button>
                        <button class="multisteps-form__progress-btn" type="button" title="Address">Info Barang</button>
                        <button class="multisteps-form__progress-btn" type="button" title="Order Info">Detail Pemakaian</button>
                    </div>
                </div>
            </div>
            <!--form panels-->
            <div class="row">
                <div class="m-auto">
                <form class="multisteps-form__form">
                    <!--single form panel-->
                    <div class="multisteps-form__panel" data-animation="scaleIn">
                        <h4 class="multisteps-form__title">Info Data Siswa</h4>
                        <div class="multisteps-form__content">
                            <div class="form-row mt-2">
                                <div class="form-group">
                                    <label for="nama_lengkap" class="mb-0">Nama Pemakai</label>
                                    <input name="nama_lengkap" id="nama_lengkap" class="multisteps-form__input form-control" type="text" placeholder="Contoh : Bryan Domani" required>            
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="kelas" class="mb-0">Kelas</label>
                                            <input class="multisteps-form__input form-control" type="text" name="kelas" id="kelas" placeholder="Contoh : 12" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="jurusan" class="mb-0">Jurusan</label>
                                            <input class="multisteps-form__input form-control" type="text" name="jurusan" id="jurusan" placeholder="Contoh : SIJA" required/>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group mt-2" style="text-align: right;">
                            <button class="btn btn-primary js-btn-next " type="button" title="Next">Selanjutnya</button>
                            </div>
                        </div>
                    </div>
                    <div class="multisteps-form__panel" data-animation="scaleIn" id="panel_order_list">
                        <h4 class="multisteps-form__title">Info Barang</h4>
                        <div class="multisteps-form__content">
                            <div class="form-row mt-2">
                                <button class="btn btn-primary js-btn-plus" >Tambah</button>
                                <div class="table-responsive ">
                                    <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Barang</th>
                                                <th>Jumlah Barang</th>
                                                <th style="width:189px;">Opsi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group mt-2" style="text-align: right;">
                                <button class="btn btn-secondary js-btn-prev" type="button" title="Prev">Kembali</button>
                                <button class="btn btn-success js-btn-next " type="button" title="Next">Simpan</button>
                            </div>
                        </div>
                    </div>
                    <!--single form panel-->
                    <div class="multisteps-form__panel" data-animation="scaleIn" id="panel_tambah">
                        <h4 class="multisteps-form__title">Pilih Barang</h4>
                        <div class="multisteps-form__content">
                            <div class="form-row mt-2">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="id_inventaris">Ruangan</label>
                                        <select class="form-select" name="id_inventaris" id="id_inventaris" required>
                                            {{-- @foreach($barang as $key => $br)
                                            <option value="{{$br->id_inventaris}}" @if( old('id_inventaris')==$br->id_inventaris)selected @endif>
                                                {{$br->barang->nama_barang}}
                                            </option>
                                            @endforeach --}}
                                        </select>                                
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="id_inventaris">Nama Barang</label>
                                            <select class="form-select" name="id_inventaris" id="id_inventaris" required>
                                                @foreach($barang as $key => $br)
                                                <option value="{{$br->id_inventaris}}" @if( old('id_inventaris')==$br->id_inventaris)selected @endif>
                                                    {{$br->barang->nama_barang}}
                                                </option>
                                                @endforeach
                                            </select>                                
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="jumlah_barang">Jumlah Barang</label>
                                            <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan_pemakaian">Keterangan Pemakaian</label>
                                    <textarea rows="3" name="keterangan_pemakaian" id="keterangan_pemakaian" class="form-control" ></textarea>
                                </div>
                            </div>
                            <div class="form-group mt-2" style="text-align: right;">
                                <button class="btn btn-secondary js-btn-prev" type="button" title="Prev">Batal</button>
                                <button class="btn btn-primary js-btn-next " type="button" title="Next">Pilih</button>
                            </div>
                        </div>
                    </div>
                <!--single form panel-->
                    <div class="multisteps-form__panel" data-animation="scaleIn">
                    <h3 class="multisteps-form__title">Info Pemakaian</h3>
                        <div class="mt-4">
                            <button class="btn bg-gradient-primary mb-0" data-toggle="modal" data-target="#addModal"><i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        </div>
                    <div class="multisteps-form__content">
                        <div class="row mt-3">
                            <div class="col-12 col-md-6">
                                <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Item Title</h5>
                                    <p class="card-text">Small and nice item description</p><a class="btn btn-primary" href="#" title="Item Link">Item Link</a>
                                </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Item Title</h5>
                                    <p class="card-text">Small and nice item description</p><a class="btn btn-primary" href="#" title="Item Link">Item Link</a>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="button-row d-flex mt-4 col-12">
                            <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                            <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                        </div>
                        </div>
                    </div>
                    </div>
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                  <h3 class="multisteps-form__title">Additional Comments</h3>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <textarea class="multisteps-form__textarea form-control" placeholder="Additional Comments and Requirements"></textarea>
                    </div>
                    <div class="button-row d-flex mt-4">
                      <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                      <button class="btn btn-success ml-auto" type="button" title="Send">Send</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



@stop
@push('js')
<script src="{{ asset('js/pemakaian.js ') }}"></script>

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