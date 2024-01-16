@extends('layouts.demo')
@section('title', 'List Peminjaan')
@section('css')
<link rel="stylesheet" href="{{asset('css\style.css')}}">
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
                    <div class="mb-2">
                        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary mb-2">
                            Tambah
                        </a>
                    </div>
                    <div class="table-responsive ">
                        <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>List Barang</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjaman as $key => $peminjaman)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{\Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d F Y')}}</td>
                                    <td>{{$peminjaman->nama_lengkap}}</td>
                                    <td>{{$peminjaman->kelas}} {{$peminjaman->jurusan}}</td>
                                    <td>
                                        <a href="{{ route('peminjaman.showDetail', $peminjaman->id_peminjaman) }}"
                                            class="btn btn-info btn-xs mx-1">
                                            <i class="fa fa-rectangle-list"></i>
                                        </a>
                                    </td>
                                    <td>
                                        @include('components.action-buttons', ['id' => $peminjaman->id_peminjaman,
                                        'key' => $key,
                                        'route' => 'peminjaman'])
                                    </td>
                                    <!-- Modal Edit Pegawai -->
                                    <div class="modal fade" id="editModal{{$peminjaman->id_peminjaman}}" tabindex="-1"
                                        role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
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
                                                        action="{{ route('peminjaman.update', $peminjaman->id_peminjaman)}}"
                                                        method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group mt-2">
                                                            <label for="nama_lengkap">Nama</label>
                                                            <input type="text" name="nama_lengkap" id="nama_lengkap"
                                                                class="form-control"
                                                                value="{{$peminjaman->nama_lengkap ?? old('nama_lengkap')}}"
                                                                required>
                                                        </div>

                                                        <div class="form-group mt-2">
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
                                                                        value="{{$peminjaman->jurusan ?? old('jurusan')}}"
                                                                        class="form-control" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <label for="keterangan_pemakaian">Keterangan
                                                                Pemakaian</label>
                                                            <input type="text" name="keterangan_pemakaian"
                                                                id="keterangan_pemakaian" class="form-control"
                                                                value="{{$peminjaman->keterangan_pemakaian ?? old('keterangan_pemakaian')}}"
                                                                required>
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

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Peminjaman</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class=" modal-body">
                <!--content title-->
                <h2 class="content__title content__title--m-sm">Form Peminjaman</h2>
                <div class="multisteps-form">
                    <!--progress bar-->
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-8 ml-auto mr-auto mb-2">
                            <div class="multisteps-form__progress">
                                <button class="multisteps-form__progress-btn js-active" type="button"
                                    title="User Info">Data
                                    Peminjam</button>
                                <button class="multisteps-form__progress-btn" type="button" title="Order Info">Detail
                                    Peminjaman</button>
                                <button class="multisteps-form__progress-btn" type="button" title="Comments">Barang
                                    Dipinjam
                                </button>
                            </div>
                        </div>



                        <!--multisteps-form-->

                        <!--form panels-->

                        <div class="row">

                            <form class="multisteps-form__form" action="{{ route('peminjaman.store') }}" method="post">
                                @csrf
                                <!--single form panel-->
                                <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active"
                                    data-animation="scaleIn">

                                    <h4 class="multisteps-form__title">Data Diri</h4>
                                    <div class="multisteps-form__content">
                                        <div class="form-row mt-3">
                                            <div class="form-group">
                                                <label for="nama_lengkap">Nama</label>
                                                <input type="text" name="nama_lengkap" id="nama_lengkap"
                                                    class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-input-group">
                                                    <div class="form-input-text1">
                                                        <label for="kelas" class="form-label">Kelas</label>
                                                        <input type="text" name="kelas" id="kelas" class="form-control"
                                                            required>
                                                    </div>
                                                    <div class="form-input-text">
                                                        <label for="jurusan" class="form-label">Jurusan</label>
                                                        <input type="text" name="jurusan" id="jurusan"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="button-row d-flex  justify-content-end mt-4">
                                            <button class="btn btn-primary ml-auto js-btn-next" type="button"
                                                title="Next">Next</button>
                                        </div>
                                    </div>
                                </div>
                                <!--single form panel-->
                                <div class="multisteps-form__panel shadow p-4 rounded bg-white"
                                    data-animation="scaleIn">
                                    <h4 class="multisteps-form__title">Detail
                                        Peminjaman</h4>
                                    <div class="multisteps-form__content">
                                        <div class="form-row mt-3">
                                            <div class="form-group">
                                                <div class="form-input-group">
                                                    <div class="form-input-text1">
                                                        <label for="tgl_pinjam" class="form-label">Tanggal
                                                            Pinjam</label>
                                                        <input type="date" name="tgl_pinjam" id="tgl_pinjam"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="form-input-text">
                                                        <label for="tgl_kembali" class="form-label">Tanggal
                                                            Kembali</label>
                                                        <input type="date" name="tgl_kembali" id="tgl_kembali"
                                                            class="form-control" required>
                                                    </div>

                                                </div>
                                                <div class="form-group mt-2">
                                                    <label for="keterangan_pemakaian">Keterangan
                                                        Pemakaian</label>
                                                    <input type="text" name="keterangan_pemakaian"
                                                        id="keterangan_pemakaian" class="form-control" required>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="button-row d-flex justify-content-end mt-4">
                                            <button class="btn btn-secondary js-btn-prev" type="button"
                                                title="Prev">Prev</button>
                                            <button class="btn btn-primary ml-auto js-btn-next" type="button"
                                                title="Next">Next
                                            </button>

                                        </div>
                                    </div>
                                </div>
                                <!--single form panel-->
                                <div class="multisteps-form__panel shadow p-4 rounded bg-white"
                                    data-animation="scaleIn">
                                    <h4 class="multisteps-form__title">Detail
                                        Peminjaman</h4>
                                    <div class="multisteps-form__content">
                                        <div class="form-row mt-3">
                                            <div class="table-container">
                                                <div class="table-responsive">
                                                    <div class="mb-2">
                                                        <button class="btn btn-primary ml-auto js-btn-add" type="button"
                                                            title="Next" id="tambahButton">Tambah</button>
                                                    </div>
                                                    <table id="myTable2"
                                                        class="table table-bordered table-striped align-items-center mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Nama Barang</th>
                                                                <th>Jumlah</th>
                                                                <th>Keterangan</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($cart as $key => $cart)
                                                            <tr>
                                                                <td>{{$key+1}}</td>
                                                                <td>{{$cart->inventaris->barang->nama_barang}}
                                                                </td>
                                                                <td>{{$cart->jumlah_barang}}</td>
                                                                <td>{{$cart->ket_barang}}</td>
                                                                <td>

                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="button-row d-flex justify-content-end mt-4">
                                            <button class="btn btn-secondary js-btn-prev" type="button"
                                                title="Prev">Prev</button>
                                            <button class="btn btn-primary ml-auto" type="submit" title="Next">Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- tambah barang -->
                            <div id="additionalFormContainer" style="display: none;">
                                <form class="addForm" action="{{ route('peminjaman.cart') }}" method="post">
                                    @csrf

                                    <div class="form-row mt-3">
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
                                                    <select class="form-select" name="id_ruangan" id="id_ruangan"
                                                        required>

                                                    </select>
                                                </div>
                                                @error('id_ruangan')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                <div class="form-input-text">
                                                    <label for="jumlah_barang">Jumlah Barang</label>
                                                    <input type="text" name="jumlah_barang" id="jumlah_barang"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="kondisi_barang">Kondisi Barang</label>
                                            <select class="form-select" name="kondisi_barang" id="kondisi_barang"
                                                required>

                                            </select>
                                            @error('kondisi_barang')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="ket_barang">Keterangan Barang</label>
                                            <input type="text" name="ket_barang" id="ket_barang" class="form-control">
                                            <small class="form-text text-muted">*wajib diisi ketika
                                                barang tidak lengkap/rusak. </small>
                                            @error('ket_barang')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="button-row d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <button class="btn btn-secondary js-btn-back" type="button"
                                            title="Prev">Prev</button>
                                        <!-- <button class="btn btn-primary ml-auto js-btn-next" type="button"
                                                title="Next">Next
                                            </button> -->

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







@stop
@push('js')
<form action="" id="delete-form" method="post">
    @method('delete')
    @csrf
</form>
<script src="../js/script.js"></script>


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
    $('#myTable1').DataTable({
        "responsive": true,
        "language": {
            "paginate": {
                "previous": "<",
                "next": ">"
            }
        }
    });
});





document.querySelectorAll('select[name=id_barang]').forEach(select => select.addEventListener(
    'click',
    function() {
        const id_barangSelect = this.closest('.form-group').nextElementSibling
            .querySelector(
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
                id_barangSelect.setAttribute('required', data.length > 0 ? 'true' :
                    'false');

                const event = new Event('change');
                id_barangSelect.dispatchEvent(event);
            })
            .catch(error => console.error('Error:', error));

    }));
document.querySelectorAll('select[name=id_ruangan], select[name=id_barang]').forEach(select =>
    select
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
                        newOption.text = option.kondisi_barang + ' - ' + option
                            .ket_barang;
                        kondisiSelect.add(newOption);
                    });

                    // Show or hide the kondisi_barang select based on whether options are available
                    kondisiSelect.style.display = data.length > 0 ? 'block' : 'none';
                    kondisiSelect.setAttribute('required', data.length > 0 ? 'true' :
                        'false');
                })
                .catch(error => console.error('Error:', error));
        }));
</script>




@endpush