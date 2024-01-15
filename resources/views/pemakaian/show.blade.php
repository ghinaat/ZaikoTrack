@extends('layouts.demo')
@section('title', 'List Detail Pemakaian')
@section('css')
<link rel="stylesheet" href="{{asset('fontawesome-free-6.4.2-web\css\all.min.css')}}">
@endsection
@section('breadcrumb-name')
Detail Pemakaian 
@endsection
@section('content')

<div class="container-fluid py-4">
    <div class="row">
      <div class="col-sm-12 col-md-4 mt-4">
        <div class="card h-30 mb-4">
          <div class="card-header pb-0 px-3">
            <div class="row">
              <div class="d-flex align-items-center">
                <div class="">
                  <i class="fa-solid fa-boxes-stacked fa-lg"></i>
                </div>
                <div class="d-flex flex-column  ms-3">
                  <h5 class="mb-0">Pemakaian</h5>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body pt-4 p-3">
            <ul class="list-group">
              <li class=" justify-content-between ps-0 border-radius-lg">
                <div class="col-6">
                  <div class="d-flex flex-column">
                    <h6 class="mt-2 align-item-center text-dark text-sm">Nama</h6>
                  </div>
                </div>
                <div class="col-6">
                  <div class="d-flex flex-column">
                    <h6 class="mt-2 align-item-center text-secondary text-sm text-end">{{$pemakaian->nama_lengkap ?? old('nama_lengkap')}}</h6>
                  </div>
                </div>
              </li>
              <li class="border-0 d-flex justify-content-between ps-0  border-radius-lg">
                <div class="col-6">
                  <div class="d-flex flex-column">
                    <h6 class="mt-2 text-dark text-sm">Kelas</h6>
                  </div>
                </div>
                <div class="col-6 text-secondary text-sm font-weight-bold text-end">
                  {{$pemakaian->kelas ?? old('kelas')}} {{$pemakaian->jurusan ?? old('jurusan')}}
                </div>
              </li>
              <li class="border-0 d-flex justify-content-between ps-0 border-radius-lg">
                <div class="col-6">
                  <div class="d-flex flex-column">
                    <h6 class="mt-2 text-dark text-sm">Tanggal Pemakaian</h6>
                  </div>
                </div>
                <div class="col-6 text-secondary text-sm font-weight-bold text-end">
                  {{\Carbon\Carbon::parse($pemakaian->tgl_pakai)->format('d F Y') ?? old('tgl_pakai')}}
                </div>
              </li>
              <li class="border-0 d-flex justify-content-between ps-0 border-radius-lg">
                <div class="col-6">
                  <div class="d-flex flex-column">
                    <h6 class="mt-2 text-dark text-sm">Keterangan Pemakaian</h6>
                  </div>
                </div>
                <div class="col-6 text-secondary text-sm font-weight-bold text-end">
                  @if ($pemakaian->keterangan_pemakaian)
                  {{$pemakaian->keterangan_pemakaian ?? old('keterangan_pemakaian')}}
                  @else
                  -
                  @endif
                </div>
              </li>
            </div>
          </div>
          
        </div>
        <div class="col-md-8 mt-4">
          <div class="card">
            <div class="card-header pb-0 px-3">
              <div class="row">
                <div class="">
                  <button class="btn btn-primary mb-0" data-toggle="modal" data-target="#addModal">Tambah</button>
                </div>
              </div>
            </div>
              <div class="card-body pt-4 p-3">
                  <div class="table-responsive ">
                    <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th style="width:120px;">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detailpemakaian as $key => $detail)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$detail->inventaris->barang->nama_barang}}</td>
                                <td>{{$detail->jumlah_barang}}</td>
                                <td>
                                  <a href="{{ route('pemakaian.destroyDetail', $detail->id_pemakaian  ) }}" onclick="notificationBeforeDelete(event, this, {{$key+1}})"
                                  class="btn btn-danger btn-xs mx-1">
                                  <i class="fa fa-trash"></i>
                              </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
              </div>
              <div class=" text-end mt-3">
                <a href="{{route('pemakaian.index')}}" class="btn btn-primary ">
                  Kembali
                </a>
              </div>
              </div>
              
          </div>
        </div>
        {{-- {{Modal Tambah}} --}}
        
      <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Tambah barang pemakaian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                  <form id="addFormCart" action="{{route('pemakaian.storeDetail',  ['id_pemakaian' => $pemakaian->id_pemakaian])}}" method="post">
                    @csrf
                    <div class="multisteps-form__panel" data-animation="scaleIn" id="panel_tambah">
                        <div class="multisteps-form__content">
                            <div class="form-row mt-2"> 
                                <div class="row">
                                    <div class="form-group">
                                        <label for="id_barang">Nama Barang</label>
                                        <select class="form-select" name="id_barang" id="id_barang">
                                            @foreach($barang as $key => $br)
                                            <option value="{{$br->id_barang}}" @if( old('id_barang')==$br->id_barang)selected @endif>
                                                {{$br->barang->nama_barang}}
                                            </option>
                                            @endforeach
                                        </select>                                
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="id_ruangan">Ruangan</label>
                                            <select class="form-select" name="id_ruangan" id="id_ruangan">
                                                
                                            </select>                                
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="jumlah_barang">Jumlah Barang</label>
                                            <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group mt-2" style="text-align: right;">
                                <button class="btn btn-secondary js-btn-prev" type="button" title="Prev">Batal</button>
                                <button class="btn btn-primary " type="submit" title="Next">Pilih</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
      {{-- {{Close}} --}}

      {{-- {{Edit Modal}} --}}
      {{-- @foreach($detailPembelian as $key =>$dp)
    
      <div class="modal fade" id="editModal{{$dp->id_detail_pembelian}}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{$dp->id_detail_pembelian}}" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="editModalLabel">Edit Detail Pembelian</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <form id="addForm" action="{{route('detailpembelian.update',$dp->id_detail_pembelian)}}" method="POST" enctype="multipart/form-data">
                          @csrf
                          @method('PUT')
                          <input type="hidden" id="id_pembelian" name="id_pembelian" value="{{old('id_pembelian', $pembelian->id_pembelian)}}">
                          <div class="form-group">
                            <label for="id_barang">Nama Barang</label>
                            <select class="form-select" name="id_barang" id="id_barang" required>
                              @foreach($barang->all() as $id_barang => $nama_barang)
                              <option value="{{ $id_barang }}" @if($dp->id_barang == $id_barang) selected @endif>
                                {{ $nama_barang }}</option>
                              @endforeach
                            </select> 
                          </div>
                          <div class="form-group">
                              <label for="jumlah_barang">Jumlah Barang</label>
                              <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control"
                                value="{{old('jumlah_barang',$dp->jumlah_barang)}}" required>
                          </div>
                          <div class="form-group">
                              <label for="subtotal_pembelian">Subtotal Pembelian</label>
                              <input type="text" name="subtotal_pembelian" id="subtotal_pembelian_edit" class="form-control" 
                              value="{{old('subtotal_pembelian',$dp->subtotal_pembelian)}}" data-format="rupiah" required>
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
      @endforeach --}}
      {{-- {{close}}       --}}

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

document.querySelectorAll('select[name=id_barang]').forEach(select => select.addEventListener('change',
    function() {
        const id_barangSelect = this.closest('.form-group').nextElementSibling.querySelector(
            'select[name=id_ruangan]');
        const selectedIdRuangan = this.value;

        // Fetch id_barang options for the selected id_ruangan
        fetch(`/get-ruangan-options/${selectedIdRuangan}`)
            .then(response => response.json())
            .then(data => {
                
                // Clear existing options
                id_barangSelect.innerHTML = '';

                // Populate options based on the received data
                data.forEach(option => {
                    const newOption = document.createElement('option');
                    newOption.value = option.ruangan.id_ruangan;
                    newOption.text =
                        option.ruangan.nama_ruangan;
                    id_barangSelect.add(newOption);
                });

                // Show or hide the id_barang select based on whether options are available
                id_barangSelect.style.display = data.length > 0 ? 'block' : 'none';
                id_barangSelect.setAttribute('required', data.length > 0 ? 'true' : 'false');
            })
            .catch(error => console.error('Error:', error));
    }));
</script>


@endpush