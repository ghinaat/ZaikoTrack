@extends('layouts.demo')
@section('title', 'List Pemakaian')
@section('css')
<link rel="stylesheet" href="{{asset('dist\css\selectize.bootstrap5.css')}}">
<style>
   @media (min-width: 768px) {
    .input-date{
        width: 105%
    }
   }
    @media (max-width: 576px) {
    .no-padding-co{
       padding-left: 5px;
       padding-right: 5px;
       padding-bottom: 0;
    }
}
</style>
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
                
                    
                    
                    <div class="d-flex justify-content-between mt-1" style="margin-right: 7px;">
                        <a class="btn btn-primary" href="{{ route('pemakaian.create') }}">Tambah</a>
                       
                    </div>

                    <div class="table-responsive ">
                        <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal Pakai</th>
                                    @can('isTeknisi', 'isKaprog', 'isKabeng')
                                    @cannot('isSiswa')
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    @endcan
                                    @endcan
                                    <th>List Barang</th>
                                    <th style="width:189px;">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupedPemakaians as $key => $pakai)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{\Carbon\Carbon::parse($pakai->tgl_pakai)->format('d F Y')}}</td>
                                    @can('isTeknisi', 'isKaprog', 'isKabeng')
                                    @cannot('isSiswa')
                                    <td>
                                        @if($pakai->status == 'siswa')
                                            {{$pakai->users->name}}
                                        @elseif($pakai->status == 'guru')
                                            {{$pakai->guru->nama_guru}}
                                        @elseif($pakai->status == 'karyawan')
                                            {{$pakai->karyawan->nama_karyawan}}
                                        @endif
                                    </td>
                                   
                                    <td>
                                        @if ($pakai->kelas == null && $pakai->jurusan == null)
                                            -
                                        @else
                                            {{$pakai->kelas}} {{$pakai->jurusan}}
                                        @endif
                                    </td>
                                    @endcan
                                    @endcan
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
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm" action="{{route('pemakaian.update')}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @if( auth()->user()->level == "siswa")
                    <input type="hidden" name="id_users" value="{{auth()->user()->id_users}}">
                    <input type="hidden" name="status" value="siswa">
                    @else
                    <input type="hidden" name="id_pemakaian" value="{{$pakai->id_pemakaian}}">
                    <div class="form-group">
                        <label for="status">Status</label>
                            <select class="form-select" name="status" id="status_upd{{$pakai->id_pemakaian}}">
                                    <option value="siswa">Siswa</option>
                                    <option value="guru">Guru</option>
                                    <option value="karyawan">Karyawan</option>
                            </select>                                
                    </div>
                    <div id="siswaFormUpdate{{$pakai->id_pemakaian}}" style="display: none;">
                        <div class="form-group">
                            <label for="id_siswa">Nama Siswa</label>
                                <select class="form-select" data-live-search="true" name="id_users" id="normalize{{$pakai->id_pemakaian}}" >
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
                                    <input type="text" name="kelas" id="kelas{{$pakai->id_pemakaian}}" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="nis" class="form-label">NIS</label>
                                    <input type="text" name="nis" id="nis{{$pakai->id_pemakaian}}" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div  id="guruFormUpdate{{$pakai->id_pemakaian}}" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group" id="id_guru">
                                <label for="id_guru">Nama Guru</label>
                                    <select class="form-select"  name="id_guru" id="normalize1{{$pakai->id_pemakaian}}" >
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
                                    <input type="text" name="nip" id="nip{{$pakai->id_pemakaian}}" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group " style="display: none;" id="karyawanFormUpdate{{$pakai->id_pemakaian}}">
                        <label for="id_karyawan">Nama Karyawan</label>
                            <select class="form-select" name="id_karyawan" id="normalize2{{$pakai->id_pemakaian}}" >
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
                        <input type="text" name="keterangan_pemakaian" id="keterangan_pemakaian{{$pakai->id_pemakaian}}" 
                            class="form-control" value="{{old('keterangan_pemakaian', $pakai->keterangan_pemakaian)}}">
                        </input>
                    </div>
                    <div class="form-group">
                        <label for="tgl_pakai">Tanggal Pakai</label>
                        <input type="date" name="tgl_pakai" id="tgl_pakai" class="form-control" value="{{old('tgl_pakai', $pakai->tgl_pakai)}}" readonly>
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
<script src="../dist/js/selectize.js"></script>
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

$(document).ready(function() {
    $('.edit-button').click(function(e) {
        e.preventDefault();

        let IdPemakaian = $(this).data('id');

        $('#normalize' + IdPemakaian).selectize({

        }); 
        $('#normalize1' + IdPemakaian).selectize({

        }); 
        $('#normalize2' + IdPemakaian).selectize({

        }); 
        $.ajax({
            type: 'GET',
            url: `/get-pemakaian-data/${IdPemakaian}`,
        })
        .done(function(response) {
            console.log('Data terkirim!!', response);
                const namaSiswaElement = document.getElementById('siswaFormUpdate' + IdPemakaian);
                const namaGuruElement = document.getElementById('guruFormUpdate' + IdPemakaian);
                const namaKaryawanElement = document.getElementById('karyawanFormUpdate' + IdPemakaian);
                const kelasElement = document.querySelector('#kelas' + IdPemakaian);
                const nisElement = document.querySelector('#nis' + IdPemakaian);
                const nipElement = document.querySelector('#nip' + IdPemakaian);

                namaSiswaElement.style.display = 'none';
                namaGuruElement.style.display = 'none';
                namaKaryawanElement.style.display = 'none';

            document.querySelectorAll('select[id="status_upd' + IdPemakaian + '"]').forEach(select => select.addEventListener('click', function() {

                if (this.value === 'siswa') {
                    namaSiswaElement.style.display = 'block';
                    namaGuruElement.style.display = 'none';
                    namaKaryawanElement.style.display = 'none';
                } else if (this.value === 'guru') {
                    namaSiswaElement.style.display = 'none';
                    namaGuruElement.style.display = 'block';
                    namaKaryawanElement.style.display = 'none';
                } else if (this.value === 'karyawan') {
                    namaSiswaElement.style.display = 'none';
                    namaGuruElement.style.display = 'none';
                    namaKaryawanElement.style.display = 'block';
                }
            }));

            $('#normalize' + IdPemakaian).on('change', function() {
            var selectedIdUsers = $(this).selectize()[0].selectize.getValue();
            console.log('Selected user ID:', selectedIdUsers);
            const nisElement = document.querySelector('input[name=nis]');
            const kelasElement = document.querySelector('input[name=kelas]');

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
                    nisElement.value = data.nis || '';
                    kelasElement.value = (data.kelas || '') + ' ' + (data.jurusan || '');
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    // Kosongkan Element dan mungkin tampilkan pesan error kepada user
                    nisElement.value = '';
                    kelasElement.value = '';
                });
        });

        $('#normalize1' + IdPemakaian).on('change', function() {
            var selectedIGuru = $(this).selectize()[0].selectize.getValue();
            console.log('Selected user ID:', selectedIGuru);
            const nipElement = document.querySelector('input[name=nip]');

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
                    console.log('Data received:', data);

                    if (data.error) {
                        throw new Error(data.error);
                    }
                    nipElement.value = data.nip || '';
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    nipElement.value = '';
                });
        });


                if (response.status == 'siswa') {
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
                                nisElement.value = '';                                    
                                kelasElement.value = '';
                                alert('Error: ' + error.message);
                            });
                    namaSiswaElement.style.display = 'block';
                    $('#status_upd' + IdPemakaian).val('siswa');

                    var selectSiswa = document.getElementById('normalize' + IdPemakaian);
                    var selectizeInstance = $(selectSiswa).selectize()[0].selectize;
                    var existingOption = selectizeInstance.options[response.id_users];
                    if (!existingOption) {
                        selectizeInstance.addOption({ value: response.id_users, text: 'User Name' }); // Replace 'User Name' with actual name
                    }
                    selectizeInstance.setValue(response.id_users);

                } else if(response.status == 'guru'){
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
                    $('#status_upd' + IdPemakaian).val('guru');
                    var selectGuru = document.getElementById('normalize1' + IdPemakaian);
                    var selectizeInstance = $(selectGuru).selectize()[0].selectize;
                    var existingOption = selectizeInstance.options[response.id_guru];
                    if (!existingOption) {
                        selectizeInstance.addOption({ value: response.id_guru, text: 'User Name' }); // Replace 'User Name' with actual name
                    }
                    selectizeInstance.setValue(response.id_guru);      
     
                } else if (response.status == 'karyawan') {
                    namaKaryawanElement.style.display = 'block';
                    $('#status_upd' + IdPemakaian).val('karyawan');
                    var selectKaryawan = document.getElementById('normalize2' + IdPemakaian);
                    var selectizeInstance = $(selectKaryawan).selectize()[0].selectize;
                    var existingOption = selectizeInstance.options[response.id_karyawan];
                    if (!existingOption) {
                        selectizeInstance.addOption({ value: response.id_karyawan, text: 'User Name' }); // Replace 'User Name' with actual name
                    }
                    selectizeInstance.setValue(response.id_karyawan); 
                }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('Data gagal terkirim!!', errorThrown);
        });
    });
});


</script>

@endpush