@extends('layouts.demo')
@section('title', 'List Pemakaian')
@section('css')
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
                    <div class="row mb-4">
                        <form method="get" action="{{ route('pemakaian.export' , ['start_date' => request()->input('start_date'), 'end_date' => request()->input('end_date')]) }}" class="d-flex ">
                            <div class="col-5">
                                <label for="start_date">Tanggal Awal:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"  value="{{ request()->input('start_date') }}">
                            </div>
                    
                            <div class="col-5 mx-3">
                                <label for="end_date">Tanggal Akhir:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request()->input('end_date') }}">
                            </div>
                            <div class="col-2  mt-4">
                                <button type="submit" class="btn btn-danger">Unduh Excel</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="mb-2">
                        <a class="btn btn-primary mb-2" href="{{route('pemakaian.create')}}">Tambah</a>
                    </div>
                    <div class="table-responsive ">
                        <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal Pakai</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>List Barang</th>
                                    <th style="width:189px;">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupedPemakaians as $key => $pakai)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{\Carbon\Carbon::parse($pakai->tgl_pakai)->format('d F Y')}}</td>
                                    <td>
                                        @if($pakai->id_guru == '1' && $pakai->id_karyawan == '1')
                                            {{$pakai->siswa->nama_siswa}}
                                        @elseif($pakai->id_siswa == '1' && $pakai->id_karyawan == '1')
                                            {{$pakai->guru->nama_guru}}
                                        @elseif($pakai->id_siswa == '1' && $pakai->id_guru == '1')
                                            {{$pakai->karyawan->nama_karyawan}}
                                        @endif
                                    </td>
                                    <td>
                                        {{$pakai->kelas}} {{$pakai->jurusan}}
                                    </td>
                                    <td>
                                        <a href="{{ route('pemakaian.showDetail', $pakai->id_pemakaian) }}"
                                            class="btn btn-info btn-xs mx-1">
                                            <i class="fa fa-rectangle-list"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-xs edit-button" data-toggle="modal" data-target="#editModal{{$pakai->id_pemakaian}}" data-id="{{$pakai->id_pemakaian}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="{{ route('pemakaian.destroy', $pakai->id_pemakaian) }}" onclick="notificationBeforeDelete(event, this, {{$key+1}})" class="btn btn-danger btn-xs mx-1">
                                            <i class="fa fa-trash"></i>
                                        </a>
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




@foreach($groupedPemakaians as $key => $pakai)
<div class="modal fade" id="editModal{{$pakai->id_pemakaian}}" tabindex="-1" role="dialog"
    aria-labelledby="editModalLabel{{$pakai->id_pemakaian}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Pemakaian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm" action="{{route('pemakaian.update')}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_pemakaian" value="{{$pakai->id_pemakaian}}">
                    <div class="form-group">
                        <label for="status">Status</label>
                            <select class="form-select" id="status_upd{{$pakai->id_pemakaian}}">
                                    <option value="siswa">Siswa</option>
                                    <option value="guru">Guru</option>
                                    <option value="karyawan">Karyawan</option>
                            </select>                                
                    </div>
                    <div class="form-group" style="display: none;" id="id_siswa_update{{$pakai->id_pemakaian}}">
                        <label for="id_siswa">Nama Lengkap</label>
                            <select class="form-select" data-live-search="true" name="id_siswa" id="id_siswa_upd{{$pakai->id_pemakaian}}" >
                                <option value="" selected hidden>-- Pilih Nama --</option>
                                @foreach($siswa as $key => $sw)
                                <option value="{{$sw->id_siswa}}" @if( old('id_siswa')==$sw->id_siswa)selected @endif>
                                    {{$sw->nama_siswa}}
                                </option>
                                @endforeach
                            </select>                                
                    </div>
                    <div class="form-group" style="display: none;" id="id_guru_update{{$pakai->id_pemakaian}}">
                        <label for="id_guru">Nama Lengkap</label>
                            <select class="form-select" data-live-search="true" name="id_guru" id="id_guru_upd{{$pakai->id_pemakaian}}" >
                                <option value="" selected hidden>-- Pilih Nama --</option>
                                @foreach($guru as $key => $gr)
                                <option value="{{$gr->id_guru}}" @if( old('id_guru')==$gr->id_guru)selected @endif>
                                    {{$gr->nama_guru}}
                                </option>
                                @endforeach
                            </select>                                
                    </div>
                    <div class="form-group" style="display: none;" id="id_karyawan_update{{$pakai->id_pemakaian}}">
                        <label for="id_karyawan">Nama Lengkap</label>
                            <select class="form-select" data-live-search="true" name="id_karyawan" id="id_karyawan_upd{{$pakai->id_pemakaian}}" >
                                <option value="" selected hidden>-- Pilih Nama --</option>
                                @foreach($karyawan as $key => $krywn)
                                <option value="{{$krywn->id_karyawan}}" @if( old('id_karyawan')==$krywn->id_karyawan)selected @endif>
                                    {{$krywn->nama_karyawan}}
                                </option>
                                @endforeach
                            </select>                                
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="kelas" class="mb-0">Kelas</label>
                                <input class=" form-control" type="text" name="kelas" id="kelas_update{{$pakai->id_pemakaian}}" value="{{old('kelas', $pakai->kelas)}}" >
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="jurusan" class="mb-0">Jurusan</label>
                                <input class="form-control" type="text" name="jurusan" id="jurusan_update{{$pakai->id_pemakaian}}" value="{{old('jurusan', $pakai->jurusan)}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="keterangan_pemakaian">Keterangan Pemakaian</label>
                        <textarea rows="3" name="keterangan_pemakaian" id="keterangan_pemakaian" class="form-control" >{{old('keterangan_pemakaian', $pakai->keterangan_pemakaian)}}
                        </textarea>
                    </div>
                    <div class="form-group">
                        <label for="tgl_pakai">Tanggal Pakai</label>
                        <input type="date" name="tgl_pakai" id="tgl_pakai" class="form-control" value="{{old('tgl_pakai', $pakai->tgl_pakai)}}">
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

@stop
@push('js')
{{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> --}}
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
<script>
$(document).ready(function() {
    $('.edit-button').click(function(e) {
        e.preventDefault();

        let IdPemakaian = $(this).data('id');

        $.ajax({
            type: 'GET',
            url: `/get-pemakaian-data/${IdPemakaian}`,
        })
        .done(function(response) {
            console.log('Data terkirim!!', response);
                const namaSiswaElement = document.querySelector('#id_siswa_update' + IdPemakaian);
                const namaGuruElement = document.querySelector('#id_guru_update' + IdPemakaian);
                const namaKaryawanElement = document.querySelector('#id_karyawan_update' + IdPemakaian );
                const kelasElement = document.querySelector('#kelas_update' + IdPemakaian);
                const jurusanElement = document.querySelector('#jurusan_update' + IdPemakaian);
                let readonlyValue = false;

                namaSiswaElement.style.display = 'none';
                namaGuruElement.style.display = 'none';
                namaKaryawanElement.style.display = 'none';

            document.querySelectorAll('select[id="status_upd' + IdPemakaian + '"]').forEach(select => select.addEventListener('click', function() {

                if (this.value === 'siswa') {
                    namaSiswaElement.style.display = 'block';
                    namaGuruElement.style.display = 'none';
                    namaKaryawanElement.style.display = 'none';
                    readonlyValue = false;
                } else if (this.value === 'guru') {
                    namaSiswaElement.style.display = 'none';
                    namaGuruElement.style.display = 'block';
                    namaKaryawanElement.style.display = 'none';
                    readonlyValue = true;
                } else if (this.value === 'karyawan') {
                    namaSiswaElement.style.display = 'none';
                    namaGuruElement.style.display = 'none';
                    namaKaryawanElement.style.display = 'block';
                    readonlyValue = true;
                }

                // Atur atribut readonly untuk elemen kelas
                kelasElement.readOnly = readonlyValue;

                // Atur atribut readonly untuk elemen jurusan hanya jika karyawan dipilih
                jurusanElement.readOnly = (this.value === 'karyawan');
            
            }));

                if (response.id_siswa !== 1) {
                    namaSiswaElement.style.display = 'block';
                    var selectSiswa = document.getElementById('id_siswa_upd' + IdPemakaian);
                    var statusSiswa = document.getElementById('status_upd' + IdPemakaian);
                    $('#status_upd' + IdPemakaian).val('siswa');

                    for (var i = 0; i < selectSiswa.options.length; i++) {
                        if (selectSiswa.options[i].value == response.id_siswa) {
                            selectSiswa.selectedIndex = i;
                            break;
                        }
                    }
                    // $('#id_siswa_update').prop('value', response.id_siswa);
                } else if(response.id_guru !== 1){
                    namaGuruElement.style.display = 'block';
                    readonlyValue = true;
                    var selectGuru = document.getElementById('id_guru_upd' + IdPemakaian);
                    var statusGuru = document.getElementById('status_upd' + IdPemakaian);
                    $('#status_upd' + IdPemakaian).val('guru');
    
                    for (var i = 0; i < selectGuru.options.length; i++) {
                        if (selectGuru.options[i].value == response.id_guru) {
                            selectGuru.selectedIndex = i;
                            break;
                        }
                    }                
                } else if (response.id_karyawan !== 1) {
                    namaKaryawanElement.style.display = 'block';
                    readonlyValue = true;
                    var statusKaryawan = document.getElementById('status_upd' + IdPemakaian);
                    $('#status_upd' + IdPemakaian).val('karyawan');

                    var selectKaryawan = document.getElementById('id_karyawan_upd' + IdPemakaian);
    
                    for (var i = 0; i < selectKaryawan.options.length; i++) {
                        if (selectKaryawan.options[i].value == response.id_karyawan) {
                            selectKaryawan.selectedIndex = i;
                            break;
                        }
                    }         
                }
                kelasElement.readOnly = readonlyValue;
                jurusanElement.readOnly = (document.getElementById('status_upd'+ IdPemakaian).value === 'karyawan');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('Data gagal terkirim!!', errorThrown);
        });
    });
});


</script>

@endpush