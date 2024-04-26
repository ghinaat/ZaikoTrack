@extends('layouts.demo')
@section('title', 'Tambah Pemakaian')
@section('css')
<link rel="stylesheet" href="{{ asset('css/pemakaian.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endsection
@section('breadcrumb-name')
Tambah Pemakaian
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card mb-2">
                <div class="card-body m-0">
                    <div class="content">
                        <div class="content__inner">
                            <h6 class="content__title mt-3">Form Pemakaian</h6>
                            <div class="container overflow-hidden">
                                <div class="multisteps-form">
                                    <div class="row">
                                        <div class="col-12 col-lg-8 mx-auto mb-4 mt-1">                            
                                            <div class="multisteps-form__progress">
                                                <button class="multisteps-form__progress-btn first" type="button" title="Address">Data Pemakai</button>
                                                <button class="multisteps-form__progress-btn" type="button" title="Order Info">Detail Pemakaian</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 m-auto">
                                        <div class="multisteps-form__form">
                                                <form id="addFormPemkaian" action="{{route('pemakaian.store')}}" method="POST">
                                                        @csrf
                                                    <div class="multisteps-form__panel  js-active first" data-animation="scaleIn">
                                                        <h4 class="multisteps-form__title">Data Diri</h4>
                                                        <div class="multisteps-form__content">
                                                            <div class="form-row mt-3">
                                                                <div class="form-group">
                                                                    <label for="status">Status</label>
                                                                        <select class="form-select" name="status" id="status">
                                                                                <option value="siswa">Siswa</option>
                                                                                <option value="guru">Guru</option>
                                                                                <option value="karyawan">Karyawan</option>
                                                                        </select>                                
                                                                </div>  
                                                                <div class="form-group " style="display: block;" id="id_siswa">
                                                                    <label for="id_siswa">Nama Lengkap</label>
                                                                        <select class="form-select" data-live-search="true" name="id_users" id="id_siswa" >
                                                                            <option value="" selected disabled>Pilih Nama</option>
                                                                            @foreach($siswa as $key => $sw)
                                                                            <option value="{{$sw->id_users}}" @if( old('id_users')==$sw->id_users)selected @endif>
                                                                                {{$sw->name}}
                                                                            </option>
                                                                            @endforeach
                                                                        </select>                                
                                                                </div>
                                                                <div class="form-group " style="display: none;" id="id_guru">
                                                                    <label for="id_guru">Nama Lengkap</label>
                                                                        <select class="form-select" data-live-search="true" name="id_guru" id="id_guru" >
                                                                            <option value="" selected disabled>Pilih Nama</option>
                                                                            @foreach($guru as $key => $gr)
                                                                            <option value="{{$gr->id_guru}}" @if( old('id_guru')==$gr->id_guru)selected @endif>
                                                                                {{$gr->nama_guru}}
                                                                            </option>
                                                                            @endforeach
                                                                        </select>                                
                                                                </div>
                                                                <div class="form-group " style="display: none;" id="id_karyawan">
                                                                    <label for="id_karyawan">Nama Lengkap</label>
                                                                        <select class="form-select" data-live-search="true" name="id_karyawan" id="id_karyawan" >
                                                                            <option value="" selected disabled>Pilih Nama</option>
                                                                            @foreach($karyawan as $key => $krywn)
                                                                            <option value="{{$krywn->id_karyawan}}" @if( old('id_karyawan')==$krywn->id_karyawan)selected @endif>
                                                                                {{$krywn->nama_karyawan}}
                                                                            </option>
                                                                            @endforeach
                                                                        </select>                                
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-6 col-md-6 kelas">
                                                                        <div class="form-group">
                                                                            <label for="kelas" class="mb-0">Kelas</label>
                                                                            <input class="multisteps-form__input form-control" type="text" name="kelas" id="kelas" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 col-md-6 jurusan">
                                                                        <div class="form-group">
                                                                            <label for="jurusan" class="mb-0">Jurusan</label>
                                                                            <input class="multisteps-form__input form-control" type="text" name="jurusan" id="jurusan" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="keterangan_pemakaian">Keterangan Pemakaian</label>
                                                                    <input type="text"  name="keterangan_pemakaian" id="keterangan_pemakaian" class="multisteps-form__input form-control"  ></input>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="tgl_pakai">Tanggal Pakai</label>
                                                                    <input type="date" name="tgl_pakai" id="tgl_pakai" class="multisteps-form__input form-control" ></input>
                                                                </div>
                                                            </div>
                                                            <div class="form-group text-end justify-content-end mb-0 mt-4" >
                                                                <button class="btn btn-danger js-btn-cancel mx-2" type="click" title="Prev">Batal</button>
                                                                <button class="btn btn-primary js-btn-save" type="click" title="Next">Selnjutnya</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="multisteps-form__panel" data-animation="scaleIn" id="panel_order_list">
                                                    <h4 class="multisteps-form__title">Data Barang</h4>
                                                    <div class="multisteps-form__content">
                                                        <div class="form-row mt-2">
                                                            <button class="btn btn-primary js-btn-plus" >Tambah</button>
                                                            <div class="table-responsive ">
                                                                <table id="detailTable" class="table table-bordered table-striped align-items-center mb-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No. </th>
                                                                            <th>Nama Barang</th>
                                                                            <th>Ruangan</th>
                                                                            <th>Jumlah Barang</th>
                                                                            <th style="width:189px;">Opsi</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mt-3" style="text-align: right;">
                                                            <button class="btn btn-secondary js-btn-update" type="click" title="Prev">Kembali</button>
                                                            <a href="{{route('pemakaian.index')}}" class="btn btn-primary ">
                                                                Simpan
                                                            </a>                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- form detail pemakaian --}}
                                                <form id="addFormDetail" action="{{route('pemakaian.storeDetail')}}" method="post">
                                                    @csrf
                                                    <div class="multisteps-form__panel" data-animation="scaleIn" id="panel_tambah">
                                                        <h4 class="multisteps-form__title">Pilih Barang</h4>
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
                                                                            <label for="jumah_barang">Stok Barang</label>
                                                                            <input type="number" class="form-control" name="jumlah_barang" id="jumlah_barang" min="0" disabled>
                                                                            <small id="stok_info" style="display: none;">Stok: <span id="stok_value"></span></small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group mt-2" style="text-align: right;">
                                                                {{-- <button class="btn btn-secondary js-btn-prev" type="button" title="Prev">Batal</button> --}}
                                                                <button class="btn btn-primary js-btn-choose" type="button" title="Next">Simpan</button>
                                                                <button class="btn btn-secondary js-btn-prev" type="button" title="Prev">Batal</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                
                                                <form id="updateFormPemkaian" action="{{route('pemakaian.update')}}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                <div class="multisteps-form__panel  js-active first" data-animation="scaleIn" id="panel_update">
                                                    <h4 class="multisteps-form__title">Data Diri</h4>
                                                    <div class="multisteps-form__content">
                                                        <div class="form-row mt-2">
                                                            <div class="form-group">
                                                                <label for="status">Status</label>
                                                                    <select class="form-select" name="status" id="status_upd">
                                                                            <option value="siswa">Siswa</option>
                                                                            <option value="guru">Guru</option>
                                                                            <option value="karyawan">Karyawan</option>
                                                                    </select>                                
                                                            </div>
                                                            <div class="form-group " style="display: none;" id="id_siswa_update">
                                                                <label for="id_siswa">Nama Lengkap</label>
                                                                    <select class="form-select" data-live-search="true" name="id_users" id="id_siswa_upd" >
                                                                        <option value="" selected disabled>Pilih Nama</option>
                                                                        @foreach($siswa as $key => $sw)
                                                                        <option value="{{$sw->id_users}}" @if( old('id_users')==$sw->id_users)selected @endif>
                                                                            {{$sw->name}}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>                                
                                                            </div>
                                                            <div class="form-group " style="display: none;" id="id_guru_update">
                                                                <label for="id_guru">Nama Lengkap</label>
                                                                    <select class="form-select" data-live-search="true" name="id_guru" id="id_guru_upd" >
                                                                        <option value="" selected disabled>Pilih Nama</option>
                                                                        @foreach($guru as $key => $gr)
                                                                        <option value="{{$gr->id_guru}}" @if( old('id_guru')==$gr->id_guru)selected @endif>
                                                                            {{$gr->nama_guru}}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>                                
                                                            </div>
                                                            <div class="form-group " style="display: none;" id="id_karyawan_update">
                                                                <label for="id_karyawan">Nama Lengkap</label>
                                                                    <select class="form-select" data-live-search="true" name="id_karyawan" id="id_karyawan_upd" >
                                                                        <option value="" selected disabled>Pilih Nama</option>
                                                                        @foreach($karyawan as $key => $krywn)
                                                                        <option value="{{$krywn->id_karyawan}}" @if( old('id_karyawan')==$krywn->id_karyawan)selected @endif>
                                                                            {{$krywn->nama_karyawan}}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>                                
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-6 col-md-6 kelas">
                                                                    <div class="form-group">
                                                                        <label for="kelas" class="mb-0">Kelas</label>
                                                                        <input class="multisteps-form__input form-control" type="text" name="kelas" id="kelas_update" required >
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 col-md-6 jurusan">
                                                                    <div class="form-group">
                                                                        <label for="jurusan" class="mb-0">Jurusan</label>
                                                                        <input class="multisteps-form__input form-control" type="text" name="jurusan" id="jurusan_update" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="keterangan_pemakaian">Keterangan Pemakaian</label>
                                                                <input type="text" name="keterangan_pemakaian" id="keterangan_pemakaian_update" class="multisteps-form__input form-control" ></input>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="tgl_pakai">Tanggal Pakai</label>
                                                                <input type="date" name="tgl_pakai" id="tgl_pakai_update" class="multisteps-form__input form-control"  ></input>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mt-2" style="text-align: right;">
                                                            <button class="btn btn-danger js-btn-cancel" type="button" title="Prev">Batal</button>
                                                            <button class="btn btn-primary js-btn-save-update" type="click" title="Next">Selnjutnya</button>
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
        </div>
    </div>
</div>

@stop
@push('js')
<script src="{{ asset('js/pemakaian.js ') }}"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
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


//untuk select ruangan 
document.addEventListener('DOMContentLoaded', function() {
    const stokInput = document.getElementById('jumlah_barang');
    const stokInfo = document.getElementById('stok_info');
    const stokValue = document.getElementById('stok_value');

    // Fetch ruangan options for the selected barang
    document.querySelectorAll('select[name=id_barang]').forEach(select => select.addEventListener('click', function() {
        const id_ruanganSelect = this.closest('.form-row').querySelector('select[name=id_ruangan]');
        const selectedIdBarang = this.value;

        // Fetch ruangan options for the selected barang
        fetch(`/get-ruangan-options/${selectedIdBarang}`)
            .then(response => response.json())
            .then(data => {
                // Clear existing options
                id_ruanganSelect.innerHTML = '';

                // Populate options based on the received data
                data.forEach(option => {
                    const newOption = document.createElement('option');
                    newOption.value = option.ruangan.id_ruangan;
                    newOption.text = option.ruangan.nama_ruangan;
                    id_ruanganSelect.add(newOption);
                });

                // Show or hide the ruangan select based on whether options are available
                id_ruanganSelect.style.display = data.length > 0 ? 'block' : 'none';
                id_ruanganSelect.setAttribute('required', data.length > 0 ? 'true' : 'false');

                // Set the stock information
                if (data.length > 0) {
                    stokInput.disabled = false;
                    stokInfo.style.display = 'block'; // Show the stock info
                    stokValue.textContent = data[0].jumlah_barang;
                } else {
                    stokInput.disabled = true;
                    stokInfo.style.display = 'none'; // Hide the stock info
                }
            })
            .catch(error => console.error('Error:', error));
    }));

    // Event listener untuk perubahan pilihan ruangan
    document.getElementById('id_ruangan').addEventListener('change', function() {
        const selectedIdRuangan = this.value;
        updateStok(selectedIdRuangan);
    });

    // Fungsi untuk memperbarui stok berdasarkan id ruangan yang dipilih
    function updateStok(idRuangan) {
        fetch(`/get-stok-options/${idRuangan}`)
            .then(response => response.json())
            .then(data => {
                // Menampilkan informasi stok
                stokInput.disabled = false;
                stokInfo.style.display = 'block';
                stokValue.textContent = data.stok;
            })
            .catch(error => console.error('Error:', error));
    }

    stokInput.addEventListener('input', function() {
        const selectedStok = parseInt(this.value);
        const availableStok = parseInt(stokValue.textContent);
        if (selectedStok > availableStok) {
            this.setCustomValidity('Stok yang dimasukkan melebihi stok yang tersedia');
        } else {
            this.setCustomValidity('');
        }
    });
});

// untuk select nama berdasarkan status
    document.querySelectorAll('select[id=status]').forEach(select => select.addEventListener('click', function() {
    const namaSiswaElement = document.querySelector('#id_siswa');
    const namaGuruElement = document.querySelector('#id_guru');
    const namaKaryawanElement = document.querySelector('#id_karyawan');
    const kelasElement = document.querySelector('#kelas');
    const jurusanElement = document.querySelector('#jurusan');

    // Inisialisasi readonlyValue sebagai false
    let readonlyValue = false;

    namaSiswaElement.style.display = 'none';
    namaGuruElement.style.display = 'none';
    namaKaryawanElement.style.display = 'none';

    if (this.value === 'siswa') {
        namaSiswaElement.style.display = 'block';
    } else if (this.value === 'guru') {
        namaGuruElement.style.display = 'block';
        kelasElement.value = null;
        // Set readonlyValue menjadi true jika guru dipilih
        readonlyValue = true;
    } else if (this.value === 'karyawan') {
        namaKaryawanElement.style.display = 'block';
        kelasElement.value = null; // Atur nilai input kelas menjadi null
        jurusanElement.value = null;
        // Set readonlyValue menjadi true jika karyawan dipilih
        readonlyValue = true;
    }

   


    // Atur atribut readonly untuk elemen kelas
    kelasElement.readOnly = readonlyValue;

    // Atur atribut readonly untuk elemen jurusan hanya jika karyawan dipilih
    jurusanElement.readOnly = (this.value === 'karyawan');
}));


// document.getElementById('status').addEventListener('click', function() {
//     const selectedStatus = this.value;
//     const siswaElement = this.parentNode.parentNode.parentNode.querySelector(
//         '#id_siswa');
//     const guruElement = this.parentNode.parentNode.parentNode.querySelector(
//         '#id_guru');
//     const karyawanElement = this.parentNode.parentNode.parentNode.querySelector(
//         '#id_karyawan');

//     const kelasElement = this.parentNode.parentNode.parentNode.querySelector(
//         '#kelas');
//     const jurusanElement = this.parentNode.parentNode.parentNode.querySelector(
//         '#jurusan');

//     // Hide all forms
//     siswaElement.style.display = 'block';
//     guruElement.style.display = 'none';
//     karyawanElement.style.display = 'none';
//     // NamaElement.style.display = 'block';
//     jurusanElement.removeAttribute('readonly');
//     kelasElement.removeAttribute('readonly');


//     // Show the selected form
//     if (selectedStatus === 'siswa') {
//         siswaElement.style.display = 'block';
//         // siswaElement.style.display = 'none';

//     } else if (selectedStatus === 'guru') {
//         guruElement.style.display = 'block';
//         siswaElement.style.display = 'none';
//         kelasElement.setAttribute('readonly', 'true');
//     } else if (selectedStatus === 'karyawan') {
//         karyawanElement.style.display = 'block';
//         siswaElement.style.display = 'none';
//         kelasElement.setAttribute('readonly', 'true');
//         jurusanElement.setAttribute('readonly', 'true');

//     }
// });

$(document).ready(function() {

    var idPemakaian;

    $("#addFormPemkaian").on('click', '.js-btn-save', function(e) {
    e.preventDefault();
            var form = $(this).closest('form');
            var url = form.attr('action');
            var method = form.attr('method');
            var data = form.serialize();
            $.ajax({
                type: method,
                url: url,
                data: data,
            })
            .done(function(response) {
                    // Dapatkan id_peminjaman dari respons JSON
                idPemakaian = response.id_pemakaian;
                console.log('Form submitted!', response);

                const panelOrderList = document.getElementById('panel_order_list');
                let panelOrderListIndex = Array.from(DOMstrings.stepFormPanels).indexOf(panelOrderList);
      
                setActiveStep(panelOrderListIndex);
                setActivePanel(panelOrderListIndex);
                return;

                    // form[0].reset();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Data diri belum lengkap.',
                });
            });
    });

    

    $("#addFormDetail").on('click', '.js-btn-choose', function(e) {
        e.preventDefault();
        var form = $(this).closest('form#addFormDetail');
         var url = form.attr('action');
        var method = form.attr('method');
        var data = form.serialize();
        if (idPemakaian){
            data += '&id_pemakaian=' + idPemakaian;

            $.ajax({
                type: method,
                url: url,
                data: data,
            })
            .done(function(response) {
                    var existingRowCount = $('#detailTable tbody tr').length;
                    var newRowNumber = existingRowCount + 1;

                    var newRow = '<tr>' +
                        '<td>' + newRowNumber + '</td>' +
                        '<td>' + response.nama_barang + '</td>' +
                        '<td>' + response.nama_ruangan + '</td>' +
                        '<td>' + response.jumlah_barang + '</td>' +
                        '<td><button class="btn btn-danger btn-sm removeBtn" data-id="' + response.id_detail_pemakaian + '">Hapus</button></td>' +
                        '</tr>';
                    $('#detailTable tbody').append(newRow);

                    form.get(0).reset();
                    formHeight(getActivePanel());
                    const panelOrderList = document.getElementById('panel_order_list');
                    let panelOrderListIndex = Array.from(DOMstrings.stepFormPanels).indexOf(panelOrderList);
                
                    setActiveStep(panelOrderListIndex);
                    setActivePanel(panelOrderListIndex);
                    return;
                    // console.log('Form submitted!', response.id_detail_pemakaian);
                    console.log('Form detail submitted!', idPemakaian);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText); // Menampilkan respons ke konsol browser

                var errorMessage;

                try {
                    const responseJson = JSON.parse(jqXHR.responseText);

                    // Cek apakah respons mengandung properti errors
                    if (responseJson && responseJson.errors) {
                        // Mengambil pesan kesalahan spesifik dari 'errors'
                        errorMessage =  'Input belum terisi.'
                    }
                } catch (error) {
                    errorMessage = 'Terjadi kesalahan yang tidak terduga. Silakan coba lagi nanti.';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage,
                });
            });
        }else{
            Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Data diri belum lengkap.',
                });
        }
    });

    function updateRowNumbers() {
    // Select all rows in the tbody
        let rows = $('#detailTable tbody tr');
        
        // Loop through each row and update the displayed number
        rows.each(function(index) {
            // Find the first column and update its text
            $(this).find('td:first-child').text(index + 1);
        });
    }

    $('#detailTable tbody').on('click', '.removeBtn', function(e) {
        e.preventDefault();

            
        let detailId = $(this).data('id');
        rowToRemove =  $(this).closest('tr');

        Swal.fire({
            title: 'Apa kamu yakin?',
            text: "Data akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/pemakaian/delete/${detailId}`,
                        type: "DELETE",
                        cache: false,
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    })
                    .done(function(response){
                        console.log('ok');
                        if(rowToRemove){
                            rowToRemove.remove();
                            updateRowNumbers();
                            formHeight(getActivePanel());
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'data tidak di temukan.',
                            });
                        }
                    })
                    .fail(function(response){
                        console.log('Data tidak terkirim!', response);
                    });
                }
            });
    });

    
    var data = ''; // Deklarasikan variabel data sebelum penggunaan

    $("#panel_order_list").on('click', '.js-btn-update', function(e) {
        e.preventDefault();

        if (idPemakaian !== undefined) {
            data += '&id_pemakaian=' + idPemakaian;

            $.ajax({
                type: 'GET',
                url: `/get-pemakaian-data/${idPemakaian}`, // Gunakan idPemakaian langsung di URL
                data: data,
            })
            .done(function(response) {
                const namaSiswaElement = document.querySelector('#id_siswa_update');
                const namaGuruElement = document.querySelector('#id_guru_update' );
                const namaKaryawanElement = document.querySelector('#id_karyawan_update');
                const kelasElement = document.querySelector('#kelas_update');
                const jurusanElement = document.querySelector('#jurusan_update');
                let readonlyValue = false;

                namaSiswaElement.style.display = 'none';
                namaGuruElement.style.display = 'none';
                namaKaryawanElement.style.display = 'none';

            document.querySelectorAll('select[id="status_upd"]').forEach(select => select.addEventListener('click', function() {

                if (this.value === 'siswa') {
                    namaSiswaElement.style.display = 'block';
                    namaGuruElement.style.display = 'none';
                    namaKaryawanElement.style.display = 'none';
                    readonlyValue = false;
                } else if (this.value === 'guru') {
                    namaSiswaElement.style.display = 'none';
                    namaGuruElement.style.display = 'block';
                    namaKaryawanElement.style.display = 'none';
                    kelasElement.value = null;
                    readonlyValue = true;
                } else if (this.value === 'karyawan') {
                    namaSiswaElement.style.display = 'none';
                    namaGuruElement.style.display = 'none';
                    namaKaryawanElement.style.display = 'block';
                    kelasElement.value = null; // Atur nilai input kelas menjadi null
                    jurusanElement.value = null;
                    readonlyValue = true;
                }

                // Atur atribut readonly untuk elemen kelas
                kelasElement.readOnly = readonlyValue;

                // Atur atribut readonly untuk elemen jurusan hanya jika karyawan dipilih
                jurusanElement.readOnly = (this.value === 'karyawan');
            
            }));

                if (response.id_users !== 1) {
                    namaSiswaElement.style.display = 'block';
                    var selectSiswa = document.getElementById('id_siswa_upd');
                    var statusSiswa = document.getElementById('status_upd');
                    $('#status_upd').val('siswa');

                    for (var i = 0; i < selectSiswa.options.length; i++) {
                        if (selectSiswa.options[i].value == response.id_users) {
                            selectSiswa.selectedIndex = i;
                            break;
                        }
                    }
                    // $('#id_siswa_update').prop('value', response.id_siswa);
                } else if(response.id_guru !== 1){
                    namaGuruElement.style.display = 'block';
                    readonlyValue = true;
                    var selectGuru = document.getElementById('id_guru_upd');
                    var statusGuru = document.getElementById('status_upd' );
                    $('#status_upd').val('guru');
    
                    for (var i = 0; i < selectGuru.options.length; i++) {
                        if (selectGuru.options[i].value == response.id_guru) {
                            selectGuru.selectedIndex = i;
                            break;
                        }
                    }                
                } else if (response.id_karyawan !== 1) {
                    namaKaryawanElement.style.display = 'block';
                    readonlyValue = true;
                    var statusKaryawan = document.getElementById('status_upd');
                    $('#status_upd').val('karyawan');

                    var selectKaryawan = document.getElementById('id_karyawan_upd');
    
                    for (var i = 0; i < selectKaryawan.options.length; i++) {
                        if (selectKaryawan.options[i].value == response.id_karyawan) {
                            selectKaryawan.selectedIndex = i;
                            break;
                        }
                    }         
                }
                kelasElement.readOnly = readonlyValue;
                jurusanElement.readOnly = (document.getElementById('status_upd').value === 'karyawan');
                $('#kelas_update').prop('value', response.kelas);
                $('#jurusan_update').prop('value',response.jurusan);
                $('#keterangan_pemakaian_update').prop('value',response.keterangan_pemakaian);
                $('#tgl_pakai_update').prop('value',response.tgl_pakai);

                const panelUpdate = document.getElementById('panel_update');
                let panelUpdateIndex = Array.from(DOMstrings.stepFormPanels).indexOf(panelUpdate);
                setActivePanel(panelUpdateIndex);
                let activeStepUpdate = panelUpdateIndex--;
                activeStepUpdate = --panelUpdateIndex;
                activeStepUpdate = --panelUpdateIndex;
                setActiveStep(activeStepUpdate);
                return;

            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log('Data gagal terkirim!!', errorThrown);
            });
        } else {
            console.log('idPemakaian tidak terdefinisi');
        }
    });

    $("#updateFormPemkaian").on('click', '.js-btn-save-update', function(e) {
    e.preventDefault();
            var form = $(this).closest('form');
            var url = form.attr('action');
            var method = form.attr('method');
            var data = form.serialize();
            if (idPemakaian){
            data += '&id_pemakaian=' + idPemakaian;


                $.ajax({
                    type: method,
                    url: url,
                    data: data,
                })
                .done(function(response) {
                        // Dapatkan id_peminjaman dari respons JSON
                    idPemakaian = response.id_pemakaian;
                    console.log('Form submitted!', response);

                    const panelOrderList = document.getElementById('panel_order_list');
                    let panelOrderListIndex = Array.from(DOMstrings.stepFormPanels).indexOf(panelOrderList);
        
                    setActiveStep(panelOrderListIndex);
                    setActivePanel(panelOrderListIndex);
                    return;

                        // form[0].reset();
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Data diri belum lengkap.',
                    });
                });
            }
    });

    $('.js-btn-cancel').click(function(e) {
        e.preventDefault();

        if (idPemakaian){
            Swal.fire({
            title: 'Apa kamu yakin ingin berhenti?',
            text: "Data tidak akan tersimpan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Keluar'
            }).then((result) => {
                if (result.isConfirmed) {
                $.ajax({
                        url: `/pemakaian/${idPemakaian}`,
                        type: "DELETE",
                        cache: false,
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    })
                    .done(function(response){
                        console.log('Data terhapus!', idPemakaian);
                        if(response.id_detail_pemakaian){
                            idDetailPemakaian = response.id_detail_pemakaian

                            $.ajax({
                                url: `/pemakaian/delete/${idDetailPemakaian}`,
                                type: "DELETE",
                                cache: false,
                                headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                            })
                            .done(function(response){
                                console.log('Data detail terhapus!', idDetailPemakaian);
                                
                            })
                            .fail(function(response){
                                console.log('Data detail tidak terkirim!');
                            });
                        }
                        window.location.href = '/pemakaian'; 
                    })
                    .fail(function(response){
                        console.log('Data tidak terkirim!', idPemakaian);
                    });
                }
            });
        }else{
            Swal.fire({
                title: 'Apa kamu yakin ingin berhenti?',
                text: "Data tidak akan tersimpan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Keluar'
            }).then((result) => {
                window.location.href = '/pemakaian'; 
            });
        }
    });
});

</script>

@endpush