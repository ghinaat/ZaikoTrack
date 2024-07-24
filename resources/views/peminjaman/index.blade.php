@extends('layouts.demo')
@section('title', 'List Peminjaamn')
@section('css')
<link rel="stylesheet" href="{{asset('dist\css\selectize.bootstrap5.css')}}">
<style>
    .form-group {
  display: flex;
  flex-direction: column;
}

.form-label {
  margin-bottom: 8px;
}

.form-input-group {
  display: flex;
}

.form-input-text1{
  width: 380px; /* Sesuaikan lebar input field sesuai kebutuhan */
  margin-right: 16px;
}
.form-input-text{
  width: 380px; /* Sesuaikan lebar input field sesuai kebutuhan */
}

</style>
@endsection
@section('breadcrumb-name')
Peminjaman
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h4 class="m-0 text-dark">List Peminjaman</h4>
                </div>
                <div class="card-body m-0">
                    <div class="row align-items-end">
                      
                            
                        
                    </div>
                
                    <div class="row">
                        <div class="d-flex">
                            <div class="col-4 col-md-6 mb-2">
                                <button class="btn btn-primary mb-2"
                                    onclick="notificationBeforeAdds(event, this)">Tambah</button>
                            </div>
                         
                        </div>

                    </div>

                    <div class="table-responsive ">
                        <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal Pinjam</th>
                                    @cannot('isSiswa')
                                    <th>Nama</th>
                                    <th>Jurusan</th>
                                    @endcan
                                    <th>List Barang</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                              
                                @foreach($peminjaman as $key => $peminjaman)
                                <tr>
                                    <td></td>
                                    <td>{{\Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d F Y')}}</td>
                                    @cannot('isSiswa')
                                    @if ($peminjaman->status == 'guru')
                                    <td>{{ $peminjaman->guru ? $peminjaman->guru->nama_guru : 'N/A' }}</td>
                                    @elseif ($peminjaman->status == 'karyawan')
                                    <td>{{ $peminjaman->karyawan ? $peminjaman->karyawan->nama_karyawan : 'N/A' }}
                                    </td>
                                    @else
                                    <td>{{ $peminjaman->users ? $peminjaman->users->name : 'N/A' }}</td>
                                    @endif
                                    @if($peminjaman->users->profile->kelas == null && $peminjaman->users->profile->jurusan == null)
                                    <td>
                                        <div style='display: flex; justify-content: center;'>-
                                        </div>
                                    </td>
                                    @else
                                    <td>{{$peminjaman->users->profile->kelas}} {{$peminjaman->users->profile->jurusan}}</td>
                                    @endif
                                    @endcan
                                    <td>

                                        <a href="{{ route('peminjaman.showDetail', $peminjaman->id_peminjaman) }}"
                                            class="btn btn-info btn-xs mx-1">
                                            <i class="fa fa-rectangle-list"></i>
                                        </a>
                                    </td>
                                    <td>
                                        @if ($detailPeminjaman)
                                        {{-- @include('components.action-buttons', ['id' =>  $peminjaman->id_peminjaman, 'key'
                                        => $key, 'route' => 'peminjaman']) --}}
                                        <a href="#" class="btn btn-primary btn-xs edit-button" data-toggle="modal" data-target="#editModal{{$peminjaman->id_peminjaman}}"
                                            data-id="{{$peminjaman->id_peminjaman}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="{{ route('peminjaman.destroy', $peminjaman->id_peminjaman) }}" onclick="notificationBeforeDelete(event, this, {{$key+1}})"
                                            class="btn btn-danger btn-xs mx-1">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        @else
                                        <div style='display: flex; justify-content: center;'>
                                            <span><i class="fas fa-check-circle fa-2x"
                                                    style="color: #42e619; align-items: center;"></i></span>
                                        </div>
                                        @endif
                                    </td>
                                    <!-- Modal Edit Pegawai -->
                                    <div class="modal fade" id="editModal{{$peminjaman->id_peminjaman}}" tabindex="-1"
                                        role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit
                                                        Peminjaman</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <i class="fa fa-close" style="color: black;"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="updateForm"
                                                        action="{{ route('peminjaman.update', $peminjaman->id_peminjaman)}}"
                                                        method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        @if( auth()->user()->level == "siswa")
                                                            <input type="hidden" name="id_users" value="{{auth()->user()->id_users}}">
                                                            <input type="hidden" name="status" value="siswa">
                                                        @else
                                                        <div class="form-group">
                                                            <label for="exampleInputstatus">Status</label>
                                                            <select
                                                                class="form-select @error('status') is-invalid @enderror selectpicker"
                                                                data-live-search="true" id="status{{$peminjaman->id_peminjaman}}"
                                                                name="status">
                                                                <option value="siswa" @if($peminjaman->status == 'siswa'
                                                                    ||
                                                                    old('status')=='siswa'
                                                                    )selected @endif>Siswa
                                                                </option>
                                                                <option value="guru" @if($peminjaman->status == 'guru'
                                                                    ||
                                                                    old('status')=='guru'
                                                                    )selected @endif>Guru
                                                                </option>
                                                                <option value="karyawan" @if($peminjaman->status ==
                                                                    'karyawan'
                                                                    ||
                                                                    old('status')=='karyawan'
                                                                    )selected @endif>Karyawan</option>
                                                            </select>
                                                            @error('status')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="id_guru">Nama Guru 2</label>
                                                            <select name="id_guru2" class="form-select" id="normalize3{{$peminjaman->id_peminjaman}}">
                                                                <option value="" selected disabled>Pilih Nama</option>
                                                                @foreach($guru as $key => $g)
                                                                <option value="{{ $g->id_guru }}">
                                                                    {{ $g->nama_guru }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                            @error('id_guru')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>
                                                        <div id="siswaForm{{$peminjaman->id_peminjaman}}" style="display: none;">
                                                            <div class="form-group" >
                                                                <label for="id_users">Nama Siswa</label>
                                                                <select name="id_users" class="form-select" id="normalize{{$peminjaman->id_peminjaman}}">                                                            <option selected>Select an option</option>
                                                                    <option value="" selected disabled>Pilih Nama</option>
                                                                    @foreach($users as $user)
                                                                    @if($user->level == 'siswa')
                                                                    <option value="{{ $user->id_users }}">{{ $user->name }}
                                                                    </option>
                                                                    @endif
                                                                    @endforeach
                                                                  </select>
                                                                @error('id_users')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                                @enderror
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="kelas" class="form-label">Kelas</label>
                                                                             <input type="text" name="kelas" id="kelas{{$peminjaman->id_peminjaman}}" class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="nis" class="form-label">NIS</label>
                                                                            <input type="text" name="nis" id="nis{{$peminjaman->id_peminjaman}}" class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                
                                                            <div  id="guruForm{{$peminjaman->id_peminjaman}}" style="display: none;">
                                                            
                                                                <div class="row">
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="form-group">
                                                                                <label for="id_guru">Nama Guru</label>
                                                                                <select class="form-select" name="id_guru" id="normalize1{{$peminjaman->id_peminjaman}}">
                                                                                    <option value="" selected disabled>Pilih Nama</option>
                                                                                    @foreach($guru as $key => $g)
                                                                                    <option value="{{ $g->id_guru }}">
                                                                                        {{ $g->nama_guru }}
                                                                                    </option>
                                                                                    @endforeach
                                                                                </select>
                                                                                @error('id_guru')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="form-group">
                                                                                <label for="nip" class="form-label">NIP</label>
                                                                                <input type="text" name="nip" id="nip{{$peminjaman->id_peminjaman}}" class="form-control" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                              
        
                                                            <div class="form-group" id="karyawanForm{{$peminjaman->id_peminjaman}}" style="display: none;">
                                                                <label for="id_karyawan">Nama Karyawan</label>
                                                                <select class="form-select" name="id_karyawan" id="normalize2{{$peminjaman->id_peminjaman}}">
                                                                    <option value="" selected disabled>Pilih Nama</option>
                                                                    @foreach($karyawan as $key => $k)
                                                                    <option value="{{ $k->id_karyawan }}">
                                                                        {{ $k->nama_karyawan }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('id_karyawan')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                                @enderror
                                                            </div>
                                                            @endif
                                                           
        
                                                            <div class="form-group mt-2">
                                                                <label for="keterangan_peminjaman">Keterangan
                                                                    Peminjaman</label>
                                                                <input type="text" name="keterangan_peminjaman"
                                                                    id="keterangan_peminjaman{{$peminjaman->id_peminjaman}}" class="form-control" 
                                                                    value="{{$peminjaman->keterangan_peminjaman}}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="tgl_kembali" class="form-label">Tanggal
                                                                    Kembali</label>
                                                                <input type="date" name="tgl_kembali" id="tgl_kembali{{$peminjaman->id_peminjaman}}"
                                                                    class="form-control" value="{{$peminjaman->tgl_kembali}}" required>
                                                                
                                                                </div>
                                                        <div class="modal-footer">
                                                            <button type="click" class="btn btn-primary">Simpan</button>
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Batal</button>
                                                        </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@push('js')
<script src="../dist/js/selectize.js"></script>

<form action="" id="delete-form" method="post">
    @method('delete')
    @csrf
</form>
{{-- <script src="../js/script.js"></script> --}}


<script>
$(document).ready(function() {
    var table = $('#myTable').DataTable({
        "responsive": true,
        "order": [
            [0, 'desc']
        ],
        "language": {
            "paginate": {
                "previous": "<",
                "next": ">"
            }
        }
    });

    table.on('order.dt search.dt', function() {
        table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
});


function notificationBeforeAdds(event, el, dt) {
    event.preventDefault();

    Swal.fire({
        title: 'Pilihan Tambah Data',
        text: 'Pilih cara untuk menambahkan data:',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Dengan Barcode',
        cancelButtonText: 'Tanpa Barcode'

    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna memilih "Dengan Barcode"
            window.location.href = '/peminjaman/barcode'; // Ganti dengan URL halaman yang sesuai
        } else {
            // Jika pengguna memilih "Tanpa Barcode", tampilkan add modal
            window.location.href = '/peminjaman/create'; // Ganti dengan URL halaman yang sesuai
        }

    });
}

$(document).ready(function() {
    $('.edit-button').click(function(e) {
        e.preventDefault();

        let IdPeminjaman = $(this).data('id');

        $('#normalize' + IdPeminjaman).selectize({

        }); 
        $('#normalize1' + IdPeminjaman).selectize({

        }); 
        $('#normalize2' + IdPeminjaman).selectize({

        }); 
        $.ajax({
            type: 'GET',
            url: `/fetch-peminjaman-data/${IdPeminjaman}`,
        })
        .done(function(response) {
            console.log('Data terkirim!!', response);
                const namaSiswaElement = document.getElementById('siswaForm' + IdPeminjaman);
                const namaGuruElement = document.getElementById('guruForm' + IdPeminjaman);
                const namaKaryawanElement = document.getElementById('karyawanForm' + IdPeminjaman);
                const kelasElement = document.querySelector('#kelas' + IdPeminjaman);
                const nisElement = document.querySelector('#nis' + IdPeminjaman);
                const nipElement = document.querySelector('#nip' + IdPeminjaman);

                namaSiswaElement.style.display = 'none';
                namaGuruElement.style.display = 'none';
                namaKaryawanElement.style.display = 'none';

            document.querySelectorAll('select[id="status' + IdPeminjaman + '"]').forEach(select => select.addEventListener('click', function() {

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

            $('#normalize' + IdPeminjaman).on('change', function() {
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

        $('#normalize1' + IdPeminjaman).on('change', function() {
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
                    $('#status_upd' + IdPeminjaman).val('siswa');

                    var selectSiswa = document.getElementById('normalize' + IdPeminjaman);
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
                    $('#status_upd' + IdPeminjaman).val('guru');
                    var selectGuru = document.getElementById('normalize1' + IdPeminjaman);
                    var selectizeInstance = $(selectGuru).selectize()[0].selectize;
                    var existingOption = selectizeInstance.options[response.id_guru];
                    if (!existingOption) {
                        selectizeInstance.addOption({ value: response.id_guru, text: 'User Name' }); // Replace 'User Name' with actual name
                    }
                    selectizeInstance.setValue(response.id_guru);      
     
                } else if (response.status == 'karyawan') {
                    namaKaryawanElement.style.display = 'block';
                    $('#status_upd' + IdPeminjaman).val('karyawan');
                    var selectKaryawan = document.getElementById('normalize2' + IdPeminjaman);
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