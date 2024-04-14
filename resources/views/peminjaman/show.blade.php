@extends('layouts.demo')
@section('title', 'List Barang')
@section('css')
<link rel="stylesheet" href="{{asset('fontawesome-free-6.4.2-web\css\all.min.css')}}">
<link rel="stylesheet" href="{{asset('css\show.css')}}">
<style>

</style>
@endsection
@section('breadcrumb-name')
Peminjaman / List Barang
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row ">
        <div class="col-12 col-sm-4 mb-4">
            <!-- <div class="col-4"> -->
            <div class="card mb-4">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3 text-dark">
                                <i class="fa-solid fa-boxes-stacked"></i> &nbsp;Peminjaman
                            </h5>
                            <div class="show-group">
                                <label for="nama_lengkap" class="show-label ">Nama</label>
                            </div>
                            <div class="show-input">
                                @if ($peminjaman->status === 'guru')
                                {{ $peminjaman->guru ? $peminjaman->guru->nama_guru : 'N/A' }} :
                                @elseif ($peminjaman->status === 'karyawan')
                                {{ $peminjaman->karyawan ? $peminjaman->karyawan->nama_karyawan : 'N/A' }} :
                                @else
                                {{ $peminjaman->users ? $peminjaman->users->name : 'N/A' }}
                                @endif
                            </div>
                            <div class="show-group">
                                <label for="nama_lengkap" class="show-label ">Kelas</label>
                            </div>
                            <div class="show-input">
                                {{$peminjaman->kelas}} {{$peminjaman->jurusan}} :
                            </div>
                            <div class="show-group">
                                <label for="nama_lengkap" class="show-label ">Tanggal
                                    Peminjaman</label>
                            </div>
                            <div class="show-input">
                                {{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d M Y') ?? old('tgl_pinjam')}}
                                :
                            </div>
                            <div class="show-group">
                                <label for="nama_lengkap" class="show-label ">Tanggal
                                    Pengembalian</label>
                            </div>
                            <div class="show-input">
                                {{ \Carbon\Carbon::parse($peminjaman->tgl_selesai)->format('d M Y') ?? old('tgl_selesai')}}
                                :
                            </div>
                            <div class="show-group">
                                <label for="nama_lengkap" class="show-label ">Keterangan
                                    Pemakaian</label>
                            </div>
                            <div class="show-input">
                                {{$peminjaman->keterangan_pemakaian}} :
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-8">
            <div class="card mb-4">

                <div class="card-body m-0">
                    <div class="table-container">
                        <div class="table-responsive">
                            <div class="mb-2">
                                <!-- <button class="btn btn-primary mb-2" data-toggle="modal"
                                    data-target="#addModal">Tambah</button> -->
                            </div>
                            <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="center-th">No.</th>
                                        <th class="center-th">Nama<br>Barang</th>
                                        <th class="center-th">Status</th>
                                        <th class="center-th">Kondisi<br>Awal</th>
                                        <th class="center-th">Kondisi<br>Akhir</th>
                                        <th class="center-th">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detailPeminjamans as $key => $barang)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$barang->inventaris->barang->nama_barang}}</td>
                                        <td>
                                            @if($barang->status == 'dipinjam')
                                            <span class="badge bg-gradient-danger">Dipinjam</span>
                                            @elseif($barang->status == 'sudah_dikembalikan')
                                            <span class="badge bg-gradient-success">Sudah Dikembalikan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($barang->inventaris->kondisi_barang == 'rusak')
                                            <span class="badge bg-gradient-danger">Rusak</span>
                                            @elseif($barang->inventaris->kondisi_barang == 'tidak_lengkap')
                                            <span class="badge bg-gradient-secondary">Tidak Lengkap</span>
                                            @else
                                            <span class="badge bg-gradient-success">Lengkap</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($barang->kondisi_barang_akhir == 'rusak')
                                            <span class="badge bg-gradient-danger">Rusak</span>
                                            @elseif($barang->kondisi_barang_akhir == 'tidak_lengkap')
                                            <span class="badge bg-gradient-secondary">Tidak Lengkap</span>
                                            @elseif($barang->kondisi_barang_akhir == 'lengkap')
                                            <span class="badge bg-gradient-success">Lengkap</span>
                                            @else
                                            <div style='display: flex; justify-content: center;'>
                                                <span>-</span>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($barang->status == "dipinjam")
                                            <a href="#" class="btn btn-primary btn-xs edit-button" data-toggle="modal"
                                                data-target="#editModal{{$barang->id_detail_peminjaman}}"
                                                data-id="{{$barang->id_detail_peminjaman}}">
                                                <i class="fa fa-undo"></i>
                                            </a>
                                            <a href="{{ route('detailPeminjaman.destroy', $barang->id_detail_peminjaman) }}"
                                                onclick="notificationBeforeDelete(event, this, {{$key+1}})"
                                                class="btn btn-danger btn-xs mx-1">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            @else
                                            <div style='display: flex; justify-content: center;'>
                                                <span> <i class="fas fa-check-circle  fa-2x"
                                                        style="color: #42e619; align-items: center;"></i></span>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    <!-- Modal Edit Pegawai -->
                                    <div class="modal fade" id="editModal{{$barang->id_detail_peminjaman}}"
                                        tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Pengembalian Barang</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <i class="fa fa-close" style="color: black;"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        action="{{ route('detailPeminjaman.update', $barang->id_detail_peminjaman)}}"
                                                        method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group">
                                                            <div class="form-input-group">
                                                                <div class="form-input-text1">
                                                                    <label for="id_barang">Barang</label>
                                                                    <input type="text" name="id_barang" id="id_barang"
                                                                        class="form-control"
                                                                        value="{{$barang->inventaris->barang->nama_barang ?? old('id_barang')}}"
                                                                        readonly>
                                                                    @error('id_barang')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-input-text">
                                                                    <label for="id_ruangan">Ruangan</label>
                                                                    <select class="form-select" name="id_ruangan"
                                                                        id="id_ruangan" required>
                                                                        @foreach($ruangans as $key => $r)
                                                                        <option value="{{$r->id_ruangan}}" @if(
                                                                            old('id_ruangan')==$r->
                                                                            id_ruangan)selected @endif>
                                                                            {{$r->nama_ruangan}}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('id_ruangan')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="status">Status</label>
                                                            <select
                                                                class="form-select @error('status') is-invalid @enderror"
                                                                id="status" name="status">
                                                                <option value="dipinjam" @if($barang->status ==
                                                                    'dipinjam'
                                                                    ||
                                                                    old('status')=='dipinjam' )selected
                                                                    @endif>Dipinjam
                                                                </option>
                                                                <option value="sudah_dikembalikan" @if($barang->
                                                                    status
                                                                    ==
                                                                    'sudah_dikembalikan' ||
                                                                    old('status')=='sudah_dikembalikan'
                                                                    )selected
                                                                    @endif>Dikembalikan
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputkondisi_barang_akhir">Kondisi
                                                                Barang Akhir</label>
                                                            <select
                                                                class="form-select @error('kondisi_barang_akhir') is-invalid @enderror"
                                                                id="exampleInputkondisi_barang_akhir"
                                                                name="kondisi_barang_akhir">
                                                                <option value="lengkap" @if($barang->
                                                                    kondisi_barang_akhir
                                                                    ==
                                                                    'lengkap' ||
                                                                    old('kondisi_barang_akhir')=='lengkap'
                                                                    )selected @endif>
                                                                    Lengkap
                                                                </option>
                                                                <option value="tidak_lengkap" @if($barang->
                                                                    kondisi_barang_akhir
                                                                    ==
                                                                    'tidak_lengkap' ||
                                                                    old('kondisi_barang_akhir')=='tidak_lengkap'
                                                                    )selected @endif>
                                                                    Tidak Lengkap
                                                                </option>
                                                                <option value="rusak" @if($barang->
                                                                    kondisi_barang_akhir
                                                                    ==
                                                                    'rusak' ||
                                                                    old('kondisi_barang_akhir')=='rusak'
                                                                    )selected @endif>
                                                                    Rusak
                                                                </option>
                                                            </select>
                                                            @error('kondisi_barang_akhir')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <label for="ket_tidak_lengkap_akhir">Keterangan
                                                                Barang</label>
                                                            <input type="text" name="ket_tidak_lengkap_akhir"
                                                                id="ket_tidak_lengkap_akhir" class="form-control"
                                                                value="{{old('ket_tidak_lengkap_akhir')}}">
                                                            <small class="form-text text-muted">*wajib diisi
                                                                ketika
                                                                barang tidak lengkap/rusak. </small>
                                                            @error('ket_tidak_lengkap_akhir')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit"
                                                                class="btn btn-primary">Simpan</button>
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
                        <div class="modal-footer mt-3">
                            <a href="{{ route('peminjaman.index') }}" class="btn btn-primary ml-3">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Barang Peminjaman</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class=" modal-body">
                <form class="addForm" action="{{ route('detailPeminjaman.store') }}" method="post">
                    @csrf
                    <div class="form-row mt-3">
                        <input type="hidden" name="id_peminjaman" value="{{ $peminjaman->id_peminjaman }}">
                        <div class="form-group">
                            <label for="id_barang">Barang</label>
                            <select class="form-select" name="id_barang" id="id_barang" required>
                                @foreach($id_barang_options as $key => $b)
                                <option value="{{ $b->barang->id_barang }}">
                                    {{ $b->barang->nama_barang }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_barang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="form-input-group">
                                <div class="form-input-text1">
                                    <label for="id_ruangan">Ruangan</label>
                                    <select class="form-select" name="id_ruangan" id="id_ruangan" required>

                                    </select>
                                </div>
                                @error('id_ruangan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <div class="form-input-text">
                                    <label for="jumlah_barang">Jumlah Barang</label>
                                    <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="kondisi_barang">Kondisi Barang</label>
                            <select class="form-select" name="kondisi_barang" id="kondisi_barang" required>

                            </select>
                            @error('kondisi_barang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group mt-2">
                            <label for="ket_tidak_lengkap_awal">Keterangan Barang</label>
                            <input type="text" name="ket_tidak_lengkap_awal" id="ket_tidak_lengkap_awal"
                                class="form-control">
                            <small class="form-text text-muted">*wajib diisi ketika
                                barang tidak lengkap/rusak. </small>
                            @error('ket_tidak_lengkap_awal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>
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



document.querySelectorAll('select[name=id_barang]').forEach(select => select.addEventListener('click',
    function() {
        if (this.closest('#addModal')) {
            const id_barangSelect = this.closest('.form-group').nextElementSibling.querySelector(
                'select[name=id_ruangan]');
            const selectedIdRuangan = this.value;

            // Fetch id_barang options for the selected id_ruangan
            fetch(`/fetch-id-barang/${selectedIdRuangan}`)
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

                    const event = new Event('change');
                    id_barangSelect.dispatchEvent(event);
                })
                .catch(error => console.error('Error:', error));
        }
    }));
document.querySelectorAll('select[name=id_ruangan], select[name=id_barang]').forEach(select => select
    .addEventListener(
        'change',
        function() {
            const id_ruanganSelect = document.querySelector('select[name=id_ruangan]');
            const id_barangSelect = document.querySelector('select[name=id_barang]');

            const selectedIdRuangan = id_ruanganSelect.value;
            const selectedIdBarang = id_barangSelect.value;
            const kondisiSelect = this.closest('.form-group').nextElementSibling.querySelector(
                'select[name=kondisi_barang]');

            // Fetch kondisi barang for the selected id_ruangan and id_barang
            fetch(`/fetch-kondisi-barang/${selectedIdRuangan}/${selectedIdBarang}`)
                .then(response => response.json())
                .then(data => {
                    // Clear existing options
                    kondisiSelect.innerHTML = '';

                    // Populate options based on the received data
                    data.forEach(option => {
                        const newOption = document.createElement('option');
                        newOption.value = option.kondisi_barang;
                        newOption.text = option.kondisi_barang + (option.ket_barang ? ' - ' + option
                            .ket_barang : '');
                        kondisiSelect.add(newOption);
                    });

                    // Show or hide the kondisi_barang select based on whether options are available
                    kondisiSelect.style.display = data.length > 0 ? 'block' : 'none';
                    kondisiSelect.setAttribute('required', data.length > 0 ? 'true' : 'false');


                })
                .catch(error => console.error('Error:', error));
        }));
</script>

@endpush