@extends('layouts.demo')
@section('title', 'List Detail Pembelian')
@section('css')
@endsection
@section('breadcrumb-name')
Detail Pembelian 
@endsection
@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-7 mt-4">
          <div class="card">
            <div class="card-header pb-0 px-3">
              <div class="row">
                <div class="col-6 d-flex align-items-center">
                  <h6 class="mb-0">Detail Pembelian</h6>
                </div>
                <div class="col-6 text-end">
                  <button class="btn bg-gradient-primary mb-0" data-toggle="modal" data-target="#addModal"><i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                </div>
              </div>
            </div>
            @if($detailPembelian->isEmpty())
            <div class="card-body pt-4 p-3">
                <div class="container" style="height:460px;" >
                <ul class="list-group">
                  <li class="list-group-item border-0  p-10 bg-gray-100 border-radius-lg">
                    <div class="d-flex flex-column">
                      <h6 class="mb-3 text-sm"></h6>
                      <p class="text-center">No data available</p>                   
                    </div>
                  </li>
                </ul>
              </div>
            @else
              <div class="card-body pt-4 p-3">
                <div class="container" id="dynamicContainer" >
                <ul class="list-group">
                  @foreach($detailPembelian as $key => $dp)
                  <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                    <div class="d-flex flex-column">
                      <h6 class="mb-3 text-sm">{{$dp->barang->nama_barang}}</h6>
                      <span class="mb-2 text-xs">Jumlah Barang :<span class="text-dark font-weight-bold ms-sm-2">{{$dp->jumlah_barang}}</span></span>
                      <span class="mb-2 text-xs">Subtotal Pembelian :<span class="text-dark ms-sm-2 font-weight-bold">Rp. {{number_format($dp->subtotal_pembelian, 0, ',', '.')}}</span></span>
                      <span class="text-xs">Harga Per-barang :<span class="text-dark ms-sm-2 font-weight-bold">Rp. {{number_format($dp->harga_perbarang, 0 ,',', '.')}}</span></span>
                    </div>
                    <div class="ms-auto text-end">
                      <a class="btn btn-link text-danger text-gradient px-3 mb-0" href="{{ route('detailpembelian' . '.destroy',$dp->id_detail_pembelian) }}" onclick="notificationBeforeDelete(event, this, {{$key+1}})">
                        <i class="far fa-trash-alt me-2"></i>Delete</a>
                      <a class="btn btn-link text-dark px-3 mb-0" href="javascript:;" data-toggle="modal" data-target="#editModal{{$dp->id_detail_pembelian}}">
                        <i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Edit</a>
                    </div>
                  </li>
                  @endforeach
                </ul>
              </div>
            @endif
              </div>
          </div>
        </div>
        {{-- {{Modal Tambah}} --}}
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="editModalLabel">Tambah Detail Pemelian</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <form id="addForm" action="{{route('detailpembelian.store', $pembelian->id_pembelian)}}" method="post" enctype="multipart/form-data">
                          @csrf
                          <input type="hidden" id="id_pembelian" name="id_pembelian" value="{{old('id_pembelian', $pembelian->id_pembelian)}}">
                          <div class="form-group">
                            <label for="id_barang">Nama Barang</label>
                            <select class="form-select" name="id_barang" id="id_barang" required>
                              @foreach($barangs as $key => $br)
                                <option value="{{$br->id_barang }}" @if( old('id_barang') == $br->id_barang)selected @endif>
                                  {{$br->nama_barang}}
                                </option>  
                              @endforeach
                            </select> 
                          </div>
                          <div class="form-group">
                              <label for="jumlah_barang">Jumlah Barang</label>
                              <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" required>
                          </div>
                          <div class="form-group">
                              <label for="subtotal_pembelian">Subtotal Pembelian</label>
                              <input type="text" name="subtotal_pembelian" id="subtotal_pembelian" class="form-control" required>
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
      {{-- {{Close}} --}}

      {{-- {{Edit Modal}} --}}
      @foreach($detailPembelian as $key =>$dp)
    
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
      @endforeach
      {{-- {{close}}       --}}

        <div class="col-md-5 mt-4">
            <div class="card h-20 mb-4">
            <div class="card-header pb-0 px-3">
                <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-0">Transaksi Pembelian</h6>
                </div>
                <div class="col-md-6 d-flex justify-content-end align-items-center">
                    <i class="far fa-calendar-alt me-2"></i>
                    <small>{{\Carbon\Carbon::parse($pembelian->tgl_pembelian)->format('d F Y') ?? old('tgl_pembelian')}}</small>
                </div>
                </div>
              </div>
              <div class="card-body pt-4 p-3">
                <ul class="list-group">
                  <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                    <div class="d-flex align-items-center">
                      <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-shop text-primary text-lg opacity-20"></i>
                      </div>
                      <div class="d-flex flex-column">
                        <h6 class="mt-3 text-dark text-sm">Nama Toko </h6>
                      </div>
                    </div>
                    <div class="d-flex align-items-center text-primary text-gradient text-sm font-weight-bold">
                    {{$pembelian->nama_toko ?? old('nama_toko')}}
                    </div>
                  </li>
                  <li class="list-group-item border-0 d-flex justify-content-between ps-0  border-radius-lg">
                    <div class="d-flex align-items-center">
                      <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-cart text-primary text-lg opacity-20"></i>
                      </div>
                      <div class="d-flex flex-column">
                        <h6 class="mt-3 text-dark text-sm">Total Pembelian</h6>
                      </div>
                    </div>
                    <div class="d-flex align-items-center text-primary text-gradient text-sm font-weight-bold">
                    Rp. {{number_format($pembelian->total_pembelian ?? old('total_pembelian'), 0, ',', '.')}}
                    </div>
                  </li>
                  <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                    <div class="d-flex align-items-center">
                      <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-app text-primary text-lg opacity-20"></i>
                      </div>
                      <div class="d-flex flex-column">
                        <h6 class="mt-3 text-dark text-sm">Stok Barang</h6>
                      </div>
                    </div>
                    <div class="d-flex align-items-center text-primary text-gradient text-sm font-weight-bold">
                    {{$pembelian->stok_barang ?? old('stok_barang')}}
                    </div>
                  </li>
                  <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                    <div class="d-flex align-items-center">
                      <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-copy-04 text-primary text-lg opacity-20"></i>
                      </div>
                      <div class="d-flex flex-column">
                        <h6 class="mt-3 text-dark text-sm">Keterangan Anggaran</h6>
                      </div>
                    </div>
                    <div class="d-flex align-items-center text-primary text-gradient text-sm font-weight-bold">
                    {{$pembelian->keterangan_anggaran ?? old('keterangan_anggaran')}}
                    </div>
                  </li>
                  <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                    <div class="d-flex align-items-center">
                      <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-folder-17 text-primary text-lg opacity-20"></i>
                      </div>
                      <div class="d-flex flex-column">
                        <h6 class="mt-3 text-dark text-sm">Nota Pembelian</h6>
                      </div>
                    </div>
                    <div class="d-flex align-items-center text-primary text-sm font-weight-bold">
                      <a href="{{ asset('storage/nota_pembelian/' . $pembelian->nota_pembelian) }}" download>
                        <button class="btn btn-link text-sm mb-0 px-0 ms-4"><i class="fas fa-file-pdf text-lg me-1"></i>Nota</button>
                      </a>
                    </div>
                  </li>
                </div>
              </div>
              <div class=" text-end">
                <a href="{{route('pembelian.index')}}" class="btn btn-primary ">
                  Kembali
              </a>
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

var subtotal_pembelian = document.getElementById('subtotal_pembelian');
  subtotal_pembelian.addEventListener('keyup', function(e)
    {
      subtotal_pembelian.value = formatRupiah(this.value, 'Rp. ');
    });

    function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}

    var subtotal_pembelian_inputs = document.querySelectorAll('[data-format="rupiah"]');

    subtotal_pembelian_inputs.forEach(function (subtotal_pembelian_edit) {
    // Inisialisasi nilai pada saat halaman dimuat
    subtotal_pembelian_edit.value = formatRupiah(subtotal_pembelian_edit.value, 'Rp. ');

    // Tambahkan event listener untuk mengubah format saat ada perubahan nilai
    subtotal_pembelian_edit.addEventListener('keyup', function (e) {
        this.value = formatRupiah(this.value, 'Rp. ');
    });

    function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}
});
</script>
<script>
  // Mengambil elemen container
  var container = document.getElementById('dynamicContainer');

  var liElements = document.querySelectorAll('#dynamicContainer ul.list-group li');
  var liCount = liElements.length;

  var totalHeight = 0;
  liElements.forEach(function(li) {
      totalHeight += li.clientHeight;
  });

  console.log('Total Height:', totalHeight); // Tambahkan ini untuk memeriksa nilai totalHeight

  if (totalHeight < 438) {
      container.style.height = totalHeight + 10 + 'px';
  } else if (totalHeight > 438) {
      container.style.height = '460px';
      container.style.overflowY = 'auto';
  }

</script>

@endpush