@extends('layouts.demo')
@section('title', 'List Peminjaan')
@section('css')
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
                                    <th>Nama</th>
                                    <th>Jurusan</th>
                                    <th>List Barang</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                              
                                @foreach($peminjaman as $key => $peminjaman)
                                <tr>
                                    <td></td>
                                    <td>{{\Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d F Y')}}</td>
                                    @if ($peminjaman->status == 'guru')
                                    <td>{{ $peminjaman->guru ? $peminjaman->guru->nama_guru : 'N/A' }}</td>
                                    @elseif ($peminjaman->status == 'karyawan')
                                    <td>{{ $peminjaman->karyawan ? $peminjaman->karyawan->nama_karyawan : 'N/A' }}
                                    </td>
                                    @else
                                    <td>{{ $peminjaman->users ? $peminjaman->users->name : 'N/A' }}</td>
                                    @endif
                                    @if($peminjaman->kelas == null && $peminjaman->jurusan == null)
                                    <td>
                                        <div style='display: flex; justify-content: center;'>-
                                        </div>
                                    </td>
                                    @else
                                    <td>{{$peminjaman->kelas}} {{$peminjaman->jurusan}}</td>
                                    @endif
                                    <td>
                                        <a href="{{ route('peminjaman.showDetail', $peminjaman->id_peminjaman) }}"
                                            class="btn btn-info btn-xs mx-1">
                                            <i class="fa fa-rectangle-list"></i>
                                        </a>
                                    </td>
                                    <td>
                                        @if ($detailPeminjaman)
                                        @include('components.action-buttons', ['id' =>  $peminjaman->id_peminjaman, 'key'
                                        => $key, 'route' => 'peminjaman'])
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
                                                    <form
                                                        action="{{ route('peminjaman.update', $peminjaman->id_peminjaman)}}"
                                                        method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group">
                                                            <label for="exampleInputstatus">Status</label>
                                                            <select
                                                                class="form-select @error('status') is-invalid @enderror selectpicker"
                                                                data-live-search="true" id="exampleInputstatus"
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
                                                        <div class="form-group" id="siswaForm" style="display: block;">
                                                            <label for="id_users">Nama Siswa</label>
                                                            <select class="form-select" name="id_users" id="id_users">
                                                                <option value="" selected disabled>Pilih Nama</option>
                                                                @foreach($users as $user)
                                                                @if($user->level == 'siswa')
                                                                <option value="{{ $user->id_users }}"
                                                                    @if(old('id_users', $peminjaman->id_users ?? '') ==
                                                                    $user->id_users) selected @endif>{{ $user->name }}
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
                                                        <div class="form-group" id="guruForm" style="display: none;">
                                                            <label for="id_guru">Nama Guru</label>
                                                            <select class="form-select" name="id_guru" id="id_guru">
                                                                <option value="" selected disabled>Pilih Nama</option>
                                                                @foreach($guru as $g)
                                                                <option value="{{ $g->id_guru }}" @if(old('id_guru',
                                                                    $peminjaman->id_guru ?? '') == $g->id_guru) selected
                                                                    @endif>{{ $g->nama_guru }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('id_guru')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group" id="karyawanForm"
                                                            style="display: none;">
                                                            <label for="id_karyawan">Nama Karyawan</label>
                                                            <select class="form-select" name="id_karyawan"
                                                                id="id_karyawan">
                                                                <option value="" selected disabled>Pilih Nama</option>
                                                                @foreach($karyawan as $k)
                                                                <option value="{{ $k->id_karyawan }}"
                                                                    @if(old('id_karyawan', $peminjaman->id_karyawan ??
                                                                    '') == $k->id_karyawan) selected
                                                                    @endif>{{ $k->nama_karyawan }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('id_karyawan')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="form-input-group">
                                                                <div class="form-input-text1">
                                                                    <label for="kelas" class="form-label">Kelas</label>
                                                                    <input type="text" name="kelas" id="kelas"
                                                                        class="form-control"
                                                                        value="{{$peminjaman->kelas ?? old('kelas')}}"
                                                                        required>
                                                                </div>
                                                                <div class="form-input-text">
                                                                    <label for="jurusan"
                                                                        class="form-label">Jurusan</label>
                                                                    <input type="text" name="jurusan" id="jurusan"
                                                                        class="form-control"
                                                                        value="{{$peminjaman->jurusan ?? old('jurusan')}}"
                                                                        required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group mt-2">
                                                            <label for="keterangan_pemakaian">Keterangan
                                                                Pemakaian</label>
                                                            <input type="text" name="keterangan_pemakaian"
                                                                id="keterangan_pemakaian" class="form-control" value="{{$peminjaman->keterangan_pemakaian ?? old('keterangan_pemakaians')}}">
                                                        </div>

                                                        <div class="form-input-group">
                                                            <div class="form-input-text1">
                                                                <label for="tgl_pinjam" class="form-label">Tanggal
                                                                    Pinjam</label>
                                                                <input type="date" name="tgl_pinjam" id="tgl_pinjam"
                                                                    value="{{$peminjaman->tgl_pinjam ?? old('tgl_pinjam')}}"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="form-input-text">
                                                                <label for="tgl_kembali" class="form-label">Tanggal
                                                                    Kembali</label>
                                                                <input type="date" name="tgl_kembali" id="tgl_kembali"
                                                                    value="{{$peminjaman->tgl_kembali ?? old('tgl_kembali')}}"
                                                                    class="form-control" required>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
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
<form action="" id="delete-form" method="post">
    @method('delete')
    @csrf
</form>
<script src="../js/script.js"></script>


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

document.getElementById('exampleInputstatus').addEventListener('click', function() {
    const selectedStatus = this.value;
    const siswaElement = this.parentNode.parentNode.parentNode.querySelector(
        '#siswaForm');
    const guruElement = this.parentNode.parentNode.parentNode.querySelector(
        '#guruForm');
    const karyawanElement = this.parentNode.parentNode.parentNode.querySelector(
        '#karyawanForm');

    const kelasElement = this.parentNode.parentNode.querySelector(
        '#kelas');
    const jurusanElement = this.parentNode.parentNode.querySelector(
        '#jurusan');

    // Hide all forms
    siswaElement.style.display = 'block';
    guruElement.style.display = 'none';
    karyawanElement.style.display = 'none';
    // NamaElement.style.display = 'block';
    jurusanElement.removeAttribute('readonly');
    kelasElement.removeAttribute('readonly');


    // Show the selected form
    if (selectedStatus === 'siswa') {
        siswaElement.style.display = 'block';
        // siswaElement.style.display = 'none';

    } else if (selectedStatus === 'guru') {
        guruElement.style.display = 'block';
        siswaElement.style.display = 'none';
        kelasElement.setAttribute('readonly', 'true');
    } else if (selectedStatus === 'karyawan') {
        karyawanElement.style.display = 'block';
        siswaElement.style.display = 'none';
        kelasElement.setAttribute('readonly', 'true');
        jurusanElement.setAttribute('readonly', 'true');

    }
});
</script>




@endpush