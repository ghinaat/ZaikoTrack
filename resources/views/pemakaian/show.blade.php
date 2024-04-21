@extends('layouts.demo')
@section('title', 'List Detail Pemakaian')
@section('css')
<link rel="stylesheet" href="{{asset('fontawesome-free-6.4.2-web\css\all.min.css')}}">
<style>
.border-divider {
    border-bottom: 0.3vh solid #e5e5e5;
}
</style>
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
              <li class=" d-flex mb-2 border-divider">
                <div class="col-12">
                  <div class="d-flex flex-column">
                    <h6 class="mt-2 text-secondary text-xs">Nama Lengkap</h6>
                    <div class="col-12 text-dark text-sm font-weight-bold mb-3">
                      @if($pemakaian->status == 'siswa')
                          {{ $pemakaian->users->name ?? '' }}
                      @elseif($pemakaian->status == 'guru')
                          {{ $pemakaian->guru->nama_guru ?? '' }}
                      @elseif($pemakaian->status == 'karyawan')
                          {{ $pemakaian->karyawan->nama_karyawan ?? '' }}
                      @endif
                  </div>
                  
                  </div>
                </div>
              </li>
              @if($pemakaian->status == 'siswa')
              <li class=" d-flex justify-content-betweenborder-radius-lg mb-2 border-divider">
                <div class="col-12">
                  <div class="d-flex flex-column">
                    <h6 class="mt-2 text-secondary text-xs">Kelas</h6>
                    <div class="col-12 text-dark text-sm font-weight-bold mb-3">
                      {{ $pemakaian->kelas ?? '' }} {{ $pemakaian->jurusan ?? '' }}
                    </div>
                  </div>
                </div>
              </li>
              @elseif($pemakaian->status == 'guru')
              <li class=" d-flex justify-content-betweenborder-radius-lg mb-2 border-divider">
                <div class="col-12">
                  <div class="d-flex flex-column">
                    <h6 class="mt-2 text-secondary text-xs">Jurusan</h6>
                    <div class="col-12 text-dark text-sm font-weight-bold mb-3">
                      {{ $pemakaian->jurusan ?? '' }}
                    </div>
                  </div>
                </div>
              </li>
              @endif
              <li class=" d-flex justify-content-betweenborder-radius-lg mb-2 border-divider">
                <div class="col-12">
                  <div class="d-flex flex-column">
                    <h6 class="mt-2 text-secondary text-xs">Tanggal Pemakaian</h6>
                    <div class="col-12 text-dark text-sm font-weight-bold mb-3">
                      {{\Carbon\Carbon::parse($pemakaian->tgl_pakai)->format('d F Y') ?? '' }}
                    </div>
                  </div>
                </div>
              </li>
              <li class="border-0 d-flex justify-content-between ps-0  border-radius-lg mb-2">
                <div class="col-6">
                  <div class="d-flex flex-column">
                    <h6 class="mt-2 text-secondary text-xs">Keterangan</h6>
                    <div class="col-12 text-dark text-sm font-weight-bold mb-2">
                      @if($pemakaian->keterangan_pemakaian)
                        {{ $pemakaian->keterangan_pemakaian ?? '' }}
                      @else
                        -
                      @endif
                    </div>
                  </div>
                </div>
              </li>
            </ul>
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
                                <th>Ruangan</th>
                                <th>Stok</th>
                                <th style="width:120px;">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                          @php
                              $groupedDetails = $detailpemakaian->groupBy('id_inventaris');
                          @endphp
                      
                          @foreach($detailpemakaian as $key => $dp)
                              @php
                                  $totalJumlahBarang = $dp->sum('jumlah_barang');
                              @endphp
                      
                              <tr>
                                  <td>{{$key+1}}</td>
                                  <td>{{$dp->inventaris->barang->nama_barang}}</td>
                                  <td>{{$dp->inventaris->ruangan->nama_ruangan}}</td>
                                  <td>{{$totalJumlahBarang}}</td>
                                  <td>
                                    <a href="#" class="btn btn-primary btn-xs edit-button" data-toggle="modal" data-target="#editModal{{$dp->id_detail_pemakaian}}"
                                      data-id="{{$dp->id_detail_pemakaian}}">
                                      <i class="fa fa-edit"></i>
                                    </a>
                                      <a href="{{ route('pemakaian.destroyDetail', $dp->id_detail_pemakaian) }}" onclick="notificationBeforeDelete(event, this, {{$loop->iteration}})" class="btn btn-danger btn-xs mx-1">
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
        {{-- Modal Tambah --}}
        
      <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Tambah barang pemakaian</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                      <i class="fa fa-close" style="color: black;"></i>
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
                                          <option value="" selected hidden>-- Pilih Barang --</option>
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
                                        <label for="id_ruangan">Ruangan</label>
                                        <select class="form-select" name="id_ruangan" id="id_ruangan">
                                        </select>
                                        <small id="stok_info" style="display: none;">Stok: <span id="stok_value"></span></small>
                                    </div>
                                    
                                    </div>
                                </div>
                                
                            </div>
                            <div class="modal-footer mt-2" style="text-align: right;">
                              <button class="btn btn-primary " type="submit" title="Next">Pilih</button>
                              <button class="btn btn-danger js-btn-prev" type="button" title="Prev">Batal</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
      </div>

      @foreach($detailpemakaian as $key => $dp)
      <div class="modal fade" id="editModal{{$dp->id_detail_pemakaian}}" tabindex="-1" role="dialog"
          aria-labelledby="editModalLabel{{$dp->id_detail_pemakaian}}" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="editModalLabel">Edit Barang Pemakaian</h5>
                      <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                          <i class="fa fa-close" style="color: black;"></i>
                      </button>
                  </div>
                  <div class="modal-body">
                    <form id="addForm" action="{{route('pemakaian.updateDetail', $dp->id_detail_pemakaian)}}" method="POST"
                      enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
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
                          <div class="row">
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
                                  <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" >
                              </div>
                            </div>
                          </div>
                            </div>
                              <div class="modal-footer">
                              <button type="submit" class="btn btn-primary">Simpan</button>
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                          </div>
                      </div>
                  </form>
                  </div>
              </div>
          </div>
      </div>
      @endforeach

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
        const stokInfo = this.closest('.form-group').querySelector('small#stok_info');
        const stokValue = stokInfo.querySelector('span#stok_value');

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
                    newOption.text = option.ruangan.nama_ruangan;
                    id_barangSelect.add(newOption);
                });

                // Show or hide the id_barang select based on whether options are available
                id_barangSelect.style.display = data.length > 0 ? 'block' : 'none';
                id_barangSelect.setAttribute('required', data.length > 0 ? 'true' : 'false');

                // Set the stock information
                if (data.length > 0) {
                    stokValue.textContent = data[0].jumlah_barang;
                    stokInfo.style.display = 'block'; // Show the stock info
                } else {
                    stokInfo.style.display = 'none'; // Hide the stock info
                }
            })
            .catch(error => console.error('Error:', error));
    }));

</script>


@endpush