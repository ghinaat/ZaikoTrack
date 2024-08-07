@extends('layouts.demo')
@section('title', 'Tambah Pemakaian')
@section('css')
<link rel="stylesheet" href="{{ asset('css\pemakaian.css')}}">
<link rel="stylesheet" href="{{asset('dist\css\selectize.bootstrap5.css')}}">
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
                                                                @if( auth()->user()->level == "siswa")
                                                                <input type="hidden" name="id_users" value="{{auth()->user()->id_users}}">
                                                                <input type="hidden" name="status" value="siswa">
                                                                @else
                                                                <div class="form-group">
                                                                    <label for="status">Status</label>
                                                                        <select class="form-select"  data-live-search="true" name="status" id="status">
                                                                                <option value="siswa">Siswa</option>
                                                                                <option value="guru">Guru</option>
                                                                                <option value="karyawan">Karyawan</option>
                                                                        </select>                                
                                                                </div> 
                                                                <div id="siswaForm" style="display: block;">
                                                                    <div class="form-group">
                                                                        <label for="id_siswa">Nama Siswa</label>
                                                                        <select class="form-select"  name="id_users" id="normalize" >
                                                                            <option value="" selected disabled>Pilih Nama</option>
                                                                            @foreach($siswa as $key => $sw)
                                                                            <option value="{{$sw->id_users}}" @if( old('id_users')==$sw->id_users)selected @endif>
                                                                                {{$sw->name}}
                                                                            </option>
                                                                            @endforeach
                                                                        </select>                                
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="form-group">
                                                                                <label for="kelas" class="form-label">Kelas</label>
                                                                                <input type="text" name="kelas" id="kelas" class="form-control" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="form-group">
                                                                                <label for="nis" class="form-label">NIS</label>
                                                                                <input type="text" name="nis" id="nis" class="form-control" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div  id="guruForm" style="display: none;">
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="form-group" id="id_guru">
                                                                                <label for="id_guru">Nama Guru</label>
                                                                                    <select class="form-select"  name="id_guru" id="normalize1" >
                                                                                        <option value="" selected disabled>Pilih Nama</option>
                                                                                        @foreach($guru as $key => $gr)
                                                                                        <option value="{{$gr->id_guru}}" @if( old('id_guru')==$gr->id_guru)selected @endif>
                                                                                            {{$gr->nama_guru}}
                                                                                        </option>
                                                                                        @endforeach
                                                                                    </select>                                
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="form-group">
                                                                                <label for="nip" class="form-label">NIP</label>
                                                                                <input type="text" name="nip" id="nip" class="form-control" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group " style="display: none;" id="karyawanForm">
                                                                    <label for="id_karyawan">Nama Karyawan</label>
                                                                        <select class="form-select" name="id_karyawan" id="normalize2" >
                                                                            <option value="" selected disabled>Pilih Nama</option>
                                                                            @foreach($karyawan as $key => $krywn)
                                                                            <option value="{{$krywn->id_karyawan}}" @if( old('id_karyawan')==$krywn->id_karyawan)selected @endif>
                                                                                {{$krywn->nama_karyawan}}
                                                                            </option>
                                                                            @endforeach
                                                                        </select>                                
                                                                </div>
                                                                @endif
                                                                <div class="form-group">
                                                                    <label for="keterangan_pemakaian">Keterangan Pemakaian</label>
                                                                    <input type="text"  name="keterangan_pemakaian" id="keterangan_pemakaian" class="multisteps-form__input form-control"  ></input>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="tgl_pakai" class="form-label">Tanggal Pemakaian</label>
                                                                    <input type="date" name="tgl_pakai" id="tgl_pakai" class="form-control" required>
                                                                </div>   
                                                            </div>
                                                            <div class="form-group text-end justify-content-end mb-0 mt-4" >
                                                                <button class="btn btn-danger js-btn-cancel mx-2" type="click" title="Prev">Batal</button>
                                                                <button class="btn btn-primary js-btn-save" type="click" title="Next">Selanjutnya</button>
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
                                                            <a href="#" class="btn btn-primary" id="simpanButton" href="{{ route('pemakaian.index') }}">
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
                                                                            <select id="id_barang" name="id_barang" class="form-select">
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
                                                                            <select class="form-select" name="id_ruangan" id="id_ruangan" style="display: block;">
                                                                                <!-- Tambahkan opsi ruangan di sini -->
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
                                                            @if( auth()->user()->level == "siswa")
                                                                <input type="hidden" name="id_users" value="{{auth()->user()->id_users}}">
                                                                <input type="hidden" name="status" value="siswa">
                                                            @else
                                                            <div class="form-group">
                                                                <label for="status">Status</label>
                                                                    <select class="form-select" name="status" id="status_upd">
                                                                            <option value="siswa">Siswa</option>
                                                                            <option value="guru">Guru</option>
                                                                            <option value="karyawan">Karyawan</option>
                                                                    </select>                                
                                                            </div>
                                                            <div id="siswaFormUpdate" style="display: block;">
                                                                <div class="form-group">
                                                                    <label for="id_siswa">Nama Siswa</label>
                                                                        <select class="form-select" data-live-search="true" name="id_users" id="normalize3" >
                                                                            <option value="" selected disabled>Pilih Nama</option>
                                                                            @foreach($siswa as $key => $sw)
                                                                            <option value="{{$sw->id_users}}" @if( old('id_users')==$sw->id_users)selected @endif>
                                                                                {{$sw->name}}
                                                                            </option>
                                                                            @endforeach
                                                                        </select>                                
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="kelas" class="form-label">Kelas</label>
                                                                            <input type="text" name="kelas" id="kelas_upd" class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="nis" class="form-label">NIS</label>
                                                                            <input type="text" name="nis" id="nis_upd" class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div  id="guruFormUpdate" style="display: none;">
                                                                <div class="row">
                                                                    <div class="col-md-6 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="id_guru">Nama Guru</label>
                                                                                <select class="form-select" data-live-search="true" name="id_guru" id="normalize4" >
                                                                                    <option value="" selected disabled>Pilih Nama</option>
                                                                                    @foreach($guru as $key => $gr)
                                                                                    <option value="{{$gr->id_guru}}" @if( old('id_guru')==$gr->id_guru)selected @endif>
                                                                                        {{$gr->nama_guru}}
                                                                                    </option>
                                                                                    @endforeach
                                                                                </select>                                
                                                                        </div>        
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="nip" class="form-label">NIP</label>
                                                                            <input type="text" name="nip" id="nip_upd" class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group " style="display: none;" id="karyawanFormUpdate">
                                                                <label for="id_karyawan">Nama Karyawan</label>
                                                                    <select class="form-select" data-live-search="true" name="id_karyawan" id="normalize5" >
                                                                        <option value="" selected disabled>Pilih Nama</option>
                                                                        @foreach($karyawan as $key => $krywn)
                                                                        <option value="{{$krywn->id_karyawan}}" @if( old('id_karyawan')==$krywn->id_karyawan)selected @endif>
                                                                            {{$krywn->nama_karyawan}}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>                                
                                                            </div>
                                                            @endif
                                                            <div class="form-group">
                                                                <label for="keterangan_pemakaian">Keterangan Pemakaian</label>
                                                                <input type="text" name="keterangan_pemakaian" id="keterangan_pemakaian_update" class="multisteps-form__input form-control" ></input>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="tgl_pakai" class="form-label">Tanggal Pemakaian</label>
                                                                <input type="date" name="tgl_pakai" id="tgl_pakai_update" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mt-2" style="text-align: right;">
                                                            <button class="btn btn-danger js-btn-cancel" type="button" title="Prev">Batal</button>
                                                            <button class="btn btn-primary js-btn-save-update" type="click" title="Next">Selanjutnya</button>
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
<script src="../dist/js/selectize.js"></script>
<form action="" id="delete-form" method="post">
    @method('delete')
    @csrf
</form>

<script>

$('#normalize').selectize({

});
$('#normalize1').selectize({

});
$('#normalize2').selectize({

}); 
$('#normalize3').selectize({

}); 
$('#normalize4').selectize({

}); 
$('#normalize5').selectize({

}); 

document.getElementById('simpanButton').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior
        
        // Perform your AJAX request or other logic here

        // Redirect to the index page
        window.location.href = "{{ route('pemakaian.index') }}";

        // Show success message using SweetAlert2
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: 'Data Berhasil Disimpan.',
            timer: 40000, // Set the timer to automatically close the message after 2 seconds
            showConfirmButton: false // Hide the "OK" button
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
        console.log("Selected Barang ID:", selectedIdBarang);

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

});
    const status = document.getElementById('status');
    if(status){
        document.getElementById('status').addEventListener('click', function() {
            const selectedStatus = this.value;
            const siswaElement = document.getElementById('siswaForm');
            const guruElement = document.getElementById('guruForm');
            const karyawanElement = document.getElementById('karyawanForm');
            const kelasInput = siswaElement.querySelector('#kelas');
            const nisInput = siswaElement.querySelector('#nis');
            const nipInput = guruElement.querySelector('#nip');
            
            kelasInput.value = '';
            nisInput.value = '';
            nipInput.value = '';

        
            // Hide all forms
            siswaElement.style.display = 'block';
            guruElement.style.display = 'none';
            karyawanElement.style.display = 'none';
            // NamaElement.style.display = 'block';
        

            // Show the selected form
            if (selectedStatus === 'siswa') {
                siswaElement.style.display = 'block';

            } else if (selectedStatus === 'guru') {
                guruElement.style.display = 'block';
                siswaElement.style.display = 'none';
            
            } else if (selectedStatus === 'karyawan') {
                karyawanElement.style.display = 'block';
                siswaElement.style.display = 'none';
            
            }
        });
    }
$('#normalize').on('change', function() {
    // Dapatkan nilai yang dipilih menggunakan Selectize.js
    var selectedIdUsers = $(this).selectize()[0].selectize.getValue();
    
    // Debug: Log ID user yang dipilih
    console.log('Selected user ID:', selectedIdUsers);
    
    // Temukan elemen input untuk nis dan kelas
    const nisInput = document.querySelector('input[name=nis]');
    const kelasInput = document.querySelector('input[name=kelas]');

    // Lakukan permintaan AJAX untuk mengambil data berdasarkan selectedIdUsers
    fetch(`/fetch-id-siswa/${selectedIdUsers}`)
        .then(response => {
            if (!response.ok) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Data profile belum disi.',
                });            }
            return response.json();
        })
        .then(data => {
            // Debug: Periksa data yang diterima
            console.log('Data received:', data);

            if (data.error) {
                throw new Error(data.error);
            }

            // Tampilkan data yang sesuai di elemen input
            nisInput.value = data.nis || '';
            kelasInput.value = (data.kelas || '') + ' ' + (data.jurusan || '');
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            // Kosongkan input dan mungkin tampilkan pesan error kepada user
            nisInput.value = '';
            kelasInput.value = '';
        });
});

$('#normalize1').on('change', function() {
    // Dapatkan nilai yang dipilih menggunakan Selectize.js
    var selectedIGuru = $(this).selectize()[0].selectize.getValue();
    
    // Debug: Log ID user yang dipilih
    console.log('Selected user ID:', selectedIGuru);
    
    // Temukan elemen input untuk nis dan kelas
    const nipInput = document.querySelector('input[name=nip]');

    // Lakukan permintaan AJAX untuk mengambil data berdasarkan selectedIGuru
    fetch(`/fetch-id-guru/${selectedIGuru}`)
        .then(response => {
            if (!response.ok) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Data profile belum disi.',
                });            }
            return response.json();
        })
        .then(data => {
            // Debug: Periksa data yang diterima
            console.log('Data received:', data);

            if (data.error) {
                throw new Error(data.error);
            }

            // Tampilkan data yang sesuai di elemen input
            nipInput.value = data.nip || '';
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            // Kosongkan input dan mungkin tampilkan pesan error kepada user
            nipInput.value = '';
        });
});

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
                idPemakaian = response.id_pemakaian;

                const panelOrderList = document.getElementById('panel_order_list');
                let panelOrderListIndex = Array.from(DOMstrings.stepFormPanels).indexOf(panelOrderList);
        
                setActiveStep(panelOrderListIndex);
                setActivePanel(panelOrderListIndex);
                return;
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Data Diri Belum Lengkap.',
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
                if (response.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.error,
                    });
                }else{ 
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
                    }
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
                const namaSiswaElement = document.querySelector('#siswaFormUpdate');
                if(namaSiswaElement){
                    const namaSiswaElement = document.querySelector('#siswaFormUpdate');
                    const namaGuruElement = document.querySelector('#guruFormUpdate' );
                    const namaKaryawanElement = document.querySelector('#karyawanFormUpdate');
                    const kelasElement = document.querySelector('#kelas_upd');
                    const nisElement = document.querySelector('#nis_upd');
                    const nipElement = document.querySelector('#nip_upd');

                    namaSiswaElement.style.display = 'none';
                    namaGuruElement.style.display = 'none';
                    namaKaryawanElement.style.display = 'none';

                    document.querySelectorAll('select[id="status_upd"]').forEach(select => select.addEventListener('click', function() {

                        if (this.value === 'siswa') {
                            namaSiswaElement.style.display = 'block';
                            namaGuruElement.style.display = 'none';
                            namaKaryawanElement.style.display = 'none';
                        } else if (this.value === 'guru') {
                            namaGuruElement.style.display = 'block';
                            namaSiswaElement.style.display = 'none';
                            namaKaryawanElement.style.display = 'none';
                        } else if (this.value === 'karyawan') {
                            namaKaryawanElement.style.display = 'block';
                            namaSiswaElement.style.display = 'none';
                            namaGuruElement.style.display = 'none';
                        }
                        
                    }));

                    $('#normalize3').on('change', function() {
                    var selectedIdUsers = $(this).selectize()[0].selectize.getValue();
                    console.log('Selected user ID:', selectedIdUsers);
                        fetch(`/fetch-id-siswa/${selectedIdUsers}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Failed to fetch profile data');
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('Data received:', data);
                                if (data.error) {
                                    throw new Error(data.error);
                                }
                                nisElement.value = data.nis || '';
                                kelasElement.value = (data.kelas || '') + ' ' + (data.jurusan || '');
                                })
                            .catch(error => {
                                console.error('Error fetching data:', error);
                                nisInput.value = '';
                                kelasInput.value = '';
                            alert('Error: ' + error.message);
                        });
                    });

                    $('#normalize4').on('change', function() {
                    var selectedGuru = $(this).selectize()[0].selectize.getValue();
                    console.log('Selected user ID:', selectedGuru);
                    fetch(`/fetch-id-guru/${selectedGuru}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Failed to fetch profile data');
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('Data received:', data);
                                if (data.error) {
                                    throw new Error(data.error);
                                }
                                nipElement.value = data.nip || '';
                            })
                            .catch(error => {
                                console.error('Error fetching data:', error);
                                nipElement.value = '';
                                alert('Error: ' + error.message);
                            });
                    });

                        
                        if (response.status === 'siswa') {
                            console.log(response.id_users)
                            const selectedUser = response.id_users;
                            fetch(`/fetch-id-siswa/${selectedUser}`)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Failed to fetch profile data');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.error) {
                                        throw new Error(data.error);
                                    }
                                    nisElement.value = data.nis || '';
                                    kelasElement.value = (data.kelas || '') + ' ' + (data.jurusan || '');
                                })
                                .catch(error => {
                                    console.error('Error fetching data:', error);
                                    nisInput.value = '';
                                    kelasInput.value = '';
                                    alert('Error: ' + error.message);
                                });

                            namaSiswaElement.style.display = 'block';
                            kelasElement.style.display = 'block';
                            nisElement.style.display = 'block';
                            $('#status_upd').val('siswa');

                            var selectSiswa = document.getElementById('normalize3');
                            var selectizeInstance = $(selectSiswa).selectize()[0].selectize;
                            var existingOption = selectizeInstance.options[response.id_users];
                            if (!existingOption) {
                                selectizeInstance.addOption({ value: response.id_users, text: 'User Name' }); // Replace 'User Name' with actual name
                            }
                            selectizeInstance.setValue(response.id_users);

                        } else if(response.status === 'guru'){
                            const selectedGuru = response.id_guru;
                            fetch(`/fetch-id-guru/${selectedGuru}`)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Failed to fetch profile data');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    console.log('Data received:', data);
                                    if (data.error) {
                                        throw new Error(data.error);
                                    }
                                    nipElement.value = data.nip || '';
                                })
                                .catch(error => {
                                    console.error('Error fetching data:', error);
                                    nipElement.value = '';
                                    alert('Error: ' + error.message);
                                });
                            namaGuruElement.style.display = 'block';
                            nipElement.style.display = 'block';
                            $('#status_upd').val('guru');

                            var selectGuru = document.getElementById('normalize4');
                            var selectizeInstance = $(selectGuru).selectize()[0].selectize;
                            var existingOption = selectizeInstance.options[response.id_guru];
                            if (!existingOption) {
                                selectizeInstance.addOption({ value: response.id_guru, text: 'User Name' }); // Replace 'User Name' with actual name
                            }
                            selectizeInstance.setValue(response.id_guru);      

                        } else if (response.status === 'karyawan') {
                            namaKaryawanElement.style.display = 'block';
                            $('#status_upd').val('karyawan');
                            var selectKaryawan = document.getElementById('normalize5');
                            var selectizeInstance = $(selectKaryawan).selectize()[0].selectize;
                            var existingOption = selectizeInstance.options[response.id_karyawan];
                            if (!existingOption) {
                                selectizeInstance.addOption({ value: response.id_karyawan, text: 'User Name' }); // Replace 'User Name' with actual name
                            }
                            selectizeInstance.setValue(response.id_karyawan); 
                        }
                }
                
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