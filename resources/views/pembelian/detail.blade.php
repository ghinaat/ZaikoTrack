@extends('layouts.demo')
@section('title', 'List Detail Pembelian')
@section('css')
<style>
  @media (max-width:512px) {
  h6 {
    font-size: 15px;
    }

  .tanggal{
    font-size: 15px;
  }
}
</style>

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
                  <button class="btn btn-primary mb-0" data-toggle="modal" data-target="#addModal">Tambah</button>
                </div>
              </div>
            </div>
            @if($detailPembelian->isEmpty())
            <div class="card-body pt-4 p-3">
                <div class="container" style="height:200px;" >
                <ul class="list-group">
                  <li class="list-group-item border-0 p-5 bg-gray-100 border-radius-lg">
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
                  <li class="list-group-item border-0 p-4 mb-2 bg-gray-100 border-radius-lg">
                    <div class="row">
                      <div class="col-sm-6 col-lg-6">
                        @if ($dp->id_barang)
                          @foreach($barangs as $barang)
                            @if($barang->id_barang == $dp->id_barang)
                            <h6 class="mb-3 text-sm"><a href="{{ route('barang.index') }}" onclick="scrollToTable()">{{ $barang->nama_barang }}</a></h6>
                            @endif
                          @endforeach
                        @else
                          @php
                            $firstBarang = $dp->barang->first(); // Mendapatkan barang pertama saja
                          @endphp
                          <h6 class="mb-3 text-sm"><a href="{{ route('barang.index') }}" onclick="scrollToTable()">{{ $firstBarang->nama_barang }}</a></h6>
                        @endif
                        <div class="flex-column d-flex">
                          <span class="mb-2 text-xs">Jumlah Barang <span class="text-dark font-weight-bold ms-sm-2 value-css">{{$dp->jumlah_barang}}</span></span>
                          <span class="mb-2 text-xs">Subtotal Pembelian <span class="text-dark ms-sm-2 font-weight-bold value-css">Rp. {{number_format($dp->subtotal_pembelian, 0, ',', '.')}}</span></span>
                          <span class="text-xs">Harga Per-barang <span class="text-dark ms-sm-2 font-weight-bold value-css">Rp. {{number_format($dp->harga_perbarang, 0 ,',', '.')}}</span></span>
                        </div>
                      </div>
                      <div class="col-sm-6 col-lg-6 text-end">
                        <a class="btn btn-link mt-0 text-danger text-gradient " href="{{ route('detailpembelian' . '.destroy',$dp->id_detail_pembelian) }}" onclick="notificationBeforeDelete(event, this, {{$key+1}})">
                          <i class="far fa-trash-alt me-2"></i><span>Hapus</span></a>
                          <a class="btn btn-link text-dark editButton" data-toggle="modal" data-target="#editModal{{$dp->id_detail_pembelian}}" data-id="{{$dp->id_detail_pembelian}}">
                            <i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Edit
                        </a>
                      </div>
                    </div>
                  </li>
                  @endforeach
                </ul>
              </div>
            @endif
              </div>
          </div>
        </div>
        {{-- Modal tambah --}}
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="editModalLabel">Tambah Detail Pembelian</h5>
                      <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-close" style="color: black;"></i>
                      </button>
                  </div>
                  <div class="modal-body">
                      <form id="addForm" action="{{route('detailpembelian.store', $pembelian->id_pembelian)}}" method="post" enctype="multipart/form-data">
                          @csrf
                          <input type="hidden" id="id_pembelian" name="id_pembelian" value="{{old('id_pembelian', $pembelian->id_pembelian)}}">
                          <div class="form-group">
                            <label for="jenis_barang">Jenis Barang</label>
                            <select class="form-select" name="id_jenis_barang" id="id_jenis_barang" >
                            
                              @foreach($jenisBarang as $key => $jb)
                              <option value="{{$jb->id_jenis_barang}}" @if( old('id_jenis_barang')==$jb->
                                  id_jenis_barang)selected @endif>
                                  {{$jb->nama_jenis_barang}}
                              </option>
                              @endforeach
                            </select> 
                          </div>
                          <div class="form-group" style="display: block;" id="alatPerlengkapan">
                            <label for="id_barang">Nama Barang</label>
                            <input type="text" class="form-control" name="id_barang_perlengkapan" id="id_barang_alat" >
                          </div>
                          <div class="form-group" style="display: none;" id="bahanPraktik">
                            <label for="id_barang">Nama Barang</label>
                            <select class="form-select" name="id_barang_bahan" id="id_barang_bahan" >
                              <option value="" selected disabled>Pilih Nama Barang</option>
                              @foreach($bahanPraktik as $key => $br)
                                <option value="{{$br->id_barang }}" @if( old('id_barang') == $br->id_barang)selected @endif>
                                  {{$br->nama_barang}}
                                </option>  
                              @endforeach
                            </select> 
                          </div>
                          <div class="form-group" id="merekBarang" style="display: block;">
                            <label for="merek">Merek</label>
                            <input type="text" name="merek" id="merek" class="form-control">
                          </div>
                          <div class="form-group" id="jumlah_barang_js" style="display: block;">
                              <label for="jumlah_barang">Jumlah Barang</label>
                              <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" min="0">
                          </div>
                          <div class="form-group">
                              <label for="subtotal_pembelian" id="subtotal" style="display: none;">Subtotal Pembelian</label>
                              <label for="harga_barang" id="harga" style="display: block;">Harga Barang</label>
                              <input type="text" name="subtotal_pembelian" id="subtotal_pembelian" class="form-control" >
                          </div>
                          <div class="modal-footer">
                            <button type="3" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                          </div>  
                      </form>
                  </div>
              </div>
          </div>
      </div>
      {{-- Close --}}

        <div class="col-md-5 mt-4">
            <div class="card h-20 mb-4">
            <div class="card-header pb-0 px-3">
                <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-0">Transaksi Pembelian</h6>
                </div>
                <div class="tanggal col-md-6 d-flex justify-content-end align-items-center">
                    <i class="far fa-calendar-alt me-2"></i>
                    <small>{{\Carbon\Carbon::parse($pembelian->tgl_pembelian)->format('d F Y') ?? old('tgl_pembelian')}}</small>
                </div>
                </div>
              </div>
              <div class="card-body pt-4 p-3">
                <ul class="list-group">
                  <li class="list-group-item border-0 ps-0 border-radius-lg">
                    <div class="row justify-content-between">
                        <div class="col-6 col-md-7">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-shop text-primary text-lg opacity-20"></i>
                                </div>
                                <div class="">
                                    <h6 class="mt-3 text-dark text-sm">Nama Toko</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-5 d-flex align-items-center justify-content-end">
                            <div class="text-primary text-gradient text-sm font-weight-bold">
                                {{$pembelian->nama_toko ?? old('nama_toko')}}
                            </div>
                        </div>
                    </div>
                </li>
                  <li class="list-group-item border-0 ps-0 border-radius-lg">
                    <div class="row justify-content-between">
                        <div class="col-6 col-md-7">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                  <i class="ni ni-cart text-primary text-lg opacity-20"></i>
                                </div>
                                <div class="">
                                  <h6 class="mt-3 text-dark text-sm">Total Pembelian</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-5 d-flex align-items-center justify-content-end">
                            <div class="text-primary text-gradient text-sm font-weight-bold">
                              Rp. {{number_format($subtotalPembelian, 0, ',', '.')}}
                            </div>
                        </div>
                    </div>
                </li>
                  <li class="list-group-item border-0 ps-0 border-radius-lg">
                    <div class="row justify-content-between">
                        <div class="col-7 col-md-7">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                  <i class="ni ni-app text-primary text-lg opacity-20"></i>
                                </div>
                                <div class="">
                                  <h6 class="mt-3 text-dark text-sm">Stok Barang</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-5 col-md-5 d-flex align-items-center justify-content-end">
                            <div class="text-primary text-gradient text-sm font-weight-bold">
                              {{$jumlahPembelian}}
                            </div>
                        </div>
                    </div>
                </li>
                  <li class="list-group-item border-0 ps-0 border-radius-lg">
                    <div class="row justify-content-between">
                        <div class="col-6 col-md-7">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                  <i class="ni ni-single-copy-04 text-primary text-lg opacity-20"></i>
                                </div>
                                <div class="">
                                  <h6 class="mt-3 text-dark text-sm">Keterangan Anggaran</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-5 d-flex align-items-center justify-content-end">
                            <div class="text-primary text-gradient text-sm font-weight-bold" style="text-align: right">
                              {{$pembelian->keterangan_anggaran ?? old('keterangan_anggaran')}}
                            </div>
                        </div>
                    </div>
                </li>
                  <li class="list-group-item border-0 ps-0 border-radius-lg">
                    <div class="row justify-content-between">
                        <div class="col-7 col-md-7">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                  <i class="ni ni-folder-17 text-primary text-lg opacity-20"></i>
                                </div>
                                <div class="">
                                  <h6 class="mt-3 text-dark text-sm">Nota Pembelian</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-5 col-md-5 d-flex align-items-center justify-content-end">
                          <div class="text-primary text-sm font-weight-bold">
                              @if ($pembelian->nota_pembelian)
                              <a href="{{ asset('/storage/nota_pembelian/'. $pembelian->nota_pembelian) }}" target="_blank" class="btn btn-link text-sm mb-0 px-0 ms-4">{{$pembelian->nota_pembelian}}</a>

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
              <div class=" text-end">
                <a href="{{route('pembelian.index')}}" class="btn btn-primary ">
                  Kembali
              </a>
              </div>
        </div>
      </div>
    </div>

    @foreach($detailPembelian as $key => $dp)
    @php
    $barangBahan = App\Models\Barang::where('id_jenis_barang', 3)->where('nama_barang', $dp->nama_barang)->get();
    $barangAlat = App\Models\Barang::where('id_detail_pembelian', $dp->id_detail_pembelian)->first();
    @endphp

<div class="modal fade" id="editModal{{$dp->id_detail_pembelian}}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{$dp->id_detail_pembelian}}" aria-hidden="true" >
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="editModalLabel">Edit Jenis Barang</h5>
              <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                  <i class="fa fa-close" style="color: black;"></i>
              </button>
          </div>
          <div class="modal-body">
              <form id="addForm" action="{{route('detailpembelian.update', $dp->id_detail_pembelian)}}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  <input type="hidden" id="id_pembelian" name="id_pembelian" value="{{old('id_pembelian', $pembelian->id_pembelian)}}">
                  <div class="form-group">
                      <label for="jenis_barang">Jenis Barang</label>
                      <select class="form-select" name="id_jenis_barang" id="id_jenis_barang_update_{{$dp->id_detail_pembelian}}" onchange="toggleFormElements('{{$dp->id_detail_pembelian}}')">
                          @if(!empty($barangAlat))
                              @foreach($jenisBarang as $key => $jb)
                                  <option value="{{$jb->id_jenis_barang}}" @if($jb->id_jenis_barang == $barangAlat->id_jenis_barang) selected @endif>
                                      {{$jb->nama_jenis_barang}}
                                  </option>
                              @endforeach
                          @else
                              @foreach($jenisBarang as $jb)
                                  <option value="{{$jb->id_jenis_barang}}" selected>
                                      {{$jb->nama_jenis_barang}}
                                  </option>
                              @endforeach
                          @endif
                      </select>
                  </div>
                  <div class="form-group" style="display: block;" id="alatPerlengkapan_update_{{$dp->id_detail_pembelian}}">
                      <label for="id_barang">Nama Barang</label>
                      <input type="text" class="form-control" @if(!empty($barangAlat)) value="{{old('nama_barang',$dp->nama_barang)}}" @endif name="id_barang_perlengkapan" id="id_barang_alat">
                  </div>
                  <div class="form-group" style="display: none;" id="bahanPraktik_update_{{$dp->id_detail_pembelian}}">
                      <label for="id_barang">Nama Barang</label>
                      <select class="form-select" name="id_barang_bahan" id="id_barang_bahan_update">
                          @foreach($bahanPraktik as $key => $br)
                              <option value="{{$br->id_barang }}" @if($br->id_barang == old('id_barang', $br->id_barang) ) selected @endif>
                                  {{$br->nama_barang}}
                              </option>
                          @endforeach
                      </select>
                  </div>
                  <div class="form-group" id="merekBarang_update_{{$dp->id_detail_pembelian}}" style="display: none;">
                      <label for="merek">Merek</label>
                      <input type="text" name="merek" id="merek" class="form-control" @if(!empty($barangAlat)) value="{{$barangAlat->merek}}" @endif>
                  </div>
                  <div class="form-group" style="display: block;" id="jumlah_barang_update_{{$dp->id_detail_pembelian}}">
                      <label for="jumlah_barang">Jumlah Barang</label>
                      <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" value="{{old('jumlah_barang',$dp->jumlah_barang)}}" required>
                  </div>
                  <div class="form-group">
                      <label for="harga_barang" id="harga_edit" style="display: ;">Harga Barang</label>
                      <input type="text" name="subtotal_pembelian" id="subtotal_pembelian_edit" class="form-control" value="{{old('subtotal_pembelian',$dp->subtotal_pembelian)}}" data-format="rupiah" required>
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

   
</div>

      {{-- {{close}}       --}}





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


  // Mengambil elemen container
  document.addEventListener('DOMContentLoaded', function () {
      var container = document.getElementById('dynamicContainer');

      if (container) {
        var liElements = container.querySelectorAll('ul.list-group li');
        var liCount = liElements.length;

        var totalHeight = 0;
        liElements.forEach(function (li) {
          totalHeight += li.clientHeight;
        });

        console.log('Total Height:', totalHeight);

        if (totalHeight < 438) {
          container.style.height = totalHeight + 10 + 'px';
        } else if (totalHeight > 438) {
          container.style.height = '460px';
          container.style.overflowY = 'auto';
        }
      }
    });

  document.querySelectorAll('select[id=id_jenis_barang]').forEach(select => select.addEventListener('click', function() {
    const PerlengkapanElement = document.querySelector('#alatPerlengkapan');
    const bahanElemnet = document.querySelector('#bahanPraktik');
    const jumlahBarangElement = document.querySelector('#jumlah_barang_js');
    const LabelSutotal = document.querySelector('#subtotal');
    const LabelHarga = document.querySelector('#harga');
    const merekElement = document.querySelector('#merekBarang');

    // Inisialisasi style display sebagai 'none'

    PerlengkapanElement.style.display = 'none';
    bahanElemnet.style.display = 'none';
    jumlahBarangElement.style.display = 'block';
    LabelSutotal.style.display = 'none'
    LabelHarga.style.display = 'block'
    merekElement.style.display = 'block'
    

    if (this.value === '3') { // Pastikan untuk membandingkan dengan string
        bahanElemnet.style.display = 'block';
        LabelSutotal.style.display = 'block';
        LabelHarga.style.display = 'none';
        merekElement.style.display = 'none'
    } else {
        PerlengkapanElement.style.display = 'block';
    }
}));

// Deklarasikan fungsi toggleFormElements di luar event listener
// Deklarasikan fungsi toggleFormElements di luar event listener
function toggleFormElements(modalId) {
    var jenisBarangSelect = document.getElementById(`id_jenis_barang_update_${modalId}`);
    var alatPerlengkapanDiv = document.getElementById(`alatPerlengkapan_update_${modalId}`);
    var bahanPraktikDiv = document.getElementById(`bahanPraktik_update_${modalId}`);
    var merekBarangDiv = document.getElementById(`merekBarang_update_${modalId}`);
    var jumlahBarangDiv = document.getElementById(`jumlah_barang_update_${modalId}`);
  console.log(modalId);
    if (jenisBarangSelect.value == 3) {
        alatPerlengkapanDiv.style.display = 'none';
        bahanPraktikDiv.style.display = 'block';
        merekBarangDiv.style.display = 'none';
    } else {
        alatPerlengkapanDiv.style.display = 'block';
        bahanPraktikDiv.style.display = 'none';
        merekBarangDiv.style.display = 'block';
    }
    jumlahBarangDiv.style.display = 'block';
}

document.addEventListener('DOMContentLoaded', function() {
    // Panggil fungsi untuk setiap modal saat halaman dimuat
    @foreach($detailPembelian as $dp)
        toggleFormElements('{{$dp->id_detail_pembelian}}');
    @endforeach

    // Tambahkan event listener untuk mendeteksi perubahan pada elemen select
    @foreach($detailPembelian as $dp)
        document.getElementById(`id_jenis_barang_update_{{$dp->id_detail_pembelian}}`).addEventListener('change', function() {
            // Panggil fungsi setiap kali ada perubahan pada select di modal
            toggleFormElements('{{$dp->id_detail_pembelian}}');
        });
    @endforeach
});






  // document.addEventListener('DOMContentLoaded', function() {
  //   var editButtons = document.querySelectorAll('.editButton');
  //   console.log(editButtons);
  //   editButtons.forEach(function(button) { button.addEventListener('click', function() {
  //     var idDetailPembelian = button.getAttribute('data-id');
  //     console.log(idDetailPembelian);

  //     fetch(`/fetch-id-barang/${idDetailPembelian}`)
  //         .then(response => {
  //             if (!response.ok) {
  //                 throw new Error('Network response was not ok');
  //             }
  //              // Mengembalikan respons sebagai JSON
  //               return response.json();
  //           })
  //           .then(data => {
              
  //             const PerlengkapanElementUpdate = document.querySelector('#alatPerlengkapan_update' + idDetailPembelian);
  //             const BahanElementUpdate = document.querySelector('#bahanPraktik_update' + idDetailPembelian);
  //             const JumlahBrgElementUpdate = document.querySelector('#jumlah_barang_update' + idDetailPembelian);
  //             const LabelSubtotalUpdate = document.querySelector('#subtotal_edit' + idDetailPembelian);
  //             const LabelHargaUpdate = document.querySelector('#harga_edit' + idDetailPembelian);

               
  //             PerlengkapanElementUpdate.style.display = 'none';
  //             BahanElementUpdate.style.display = 'none';
  //             JumlahBrgElementUpdate.style.display = 'none';
  //             LabelSubtotalUpdate.style.display = 'none';
  //             LabelHargaUpdate.style.display = 'none';
  //             console.log('Data:', alatPerlengkapan);

  //               if (alatPerlengkapan === '3') {
  //                 PerlengkapanElementUpdate.style.display = 'none';
  //                 BahanElementUpdate.style.display = 'block';
  //                 JumlahBrgElementUpdate.style.display = 'block';
  //                 LabelSubtotalUpdate.style.display = 'block';
  //                 LabelHargaUpdate.style.display = 'none';
  //               } 
  //               else{
  //                 PerlengkapanElementUpdate.style.display = 'block';
  //                 LabelHargaUpdate.style.display = 'block';
  //                 //  $('#id_jenis_barang_update').val('1');

  //               }
  //             // console.log('data terkirim');
  //             const SelectAlat = document.getElementById('id_barang_alat_update' + idDetailPembelian);
  //             data.selectUpdate.forEach(option => {
  //                 const newOption = document.createElement('option');
  //                 newOption.value = option.id_barang; // Menggunakan id_barang langsung
  //                 newOption.text = option.nama_barang;
  //                 if (data.barangSebelumnya.includes(option.id_barang)) {
  //                     newOption.selected = true; // Jika ya, tandai opsi sebagai terpilih
  //                 }
  //                 SelectAlat.appendChild(newOption); // Menambahkan opsi ke dalam select
  //             });


              
          

          
          // document.querySelectorAll('select[id=id_jenis_barang_update]').forEach(select => select.addEventListener('click', function() {
          //   if (this.value === '3') { // Pastikan untuk membandingkan dengan string
          //     BahanElementUpdate.style.display = 'block';
          //     JumlahBrgElementUpdate.style.display = 'block';
          //   } else {
          //     PerlengkapanElementUpdate.style.display = 'block';

          //   }
          // }));


//                 // Lakukan sesuatu dengan data yang diterima, misalnya, tampilkan ID barang
//             })
//             .catch(error => {
//                 // Tangani kesalahan jika permintaan gagal
//                 console.error('Error:', error);
//             });
//     });
// });
//   });

</script>

@endpush