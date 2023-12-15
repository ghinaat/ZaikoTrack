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
                        <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#addModal">Tambah</button>
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
                                </tr>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach
    </tbody>
    </table>
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

            </div>


            <!--multisteps-form-->
            <div class="multisteps-form">
                <!--progress bar-->
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-8 ml-auto mr-auto mb-2">
                        <div class="multisteps-form__progress">
                            <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">Data
                                Peminjam</button>
                            <button class="multisteps-form__progress-btn" type="button" title="Order Info">Detail
                                Peminjaman</button>
                            <button class="multisteps-form__progress-btn" type="button" title="Comments">Barang
                                Dipinjam
                            </button>
                        </div>
                    </div>

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
                                                    <input type="text" name="jurusan" id="jurusan" class="form-control"
                                                        required>
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
                            <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
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
                                                <label for="keterangan_pemakaian">Keterangan Pemakaian</label>
                                                <input type="text" name="keterangan_pemakaian" id="keterangan_pemakaian"
                                                    class="form-control" required>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="button-row d-flex justify-content-end mt-4">
                                        <button class="btn btn-secondary js-btn-prev" type="button"
                                            title="Prev">Prev</button>
                                        <button class="btn btn-primary ml-auto js-btn-next" type="button"
                                            title="Next">Next </button>

                                    </div>
                                </div>
                            </div>
                            <!--single form panel-->
                            <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                                <h4 class="multisteps-form__title">Detail
                                    Peminjaman</h4>
                                <div class="multisteps-form__content">
                                    <div class="form-row mt-3">
                                        <div class="table-container">
                                            <div class="table-responsive">
                                                <div class="mb-2">
                                                    <button class="btn btn-primary ml-auto js-btn-next" type="button"
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
                                                        @foreach($inventaris as $key => $inventaris)
                                                        <tr>
                                                            <td>{{$key+1}}</td>
                                                            <td>{{$inventaris->ruangan->nama_ruangan}}</td>
                                                            <td>{{$inventaris->ruangan->nama_ruangan}}</td>
                                                            <td>{{$inventaris->ruangan->nama_ruangan}}</td>
                                                        </tr>
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
                        </form>
                    </div>
                </div>
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                    <h4 class="multisteps-form__title">Barang Dipinjam</h4>
                    <div class="multisteps-form__content">
                        <form class="multisteps-form__form" action="{{ route('peminjaman.store') }}" method="post">
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
                                            <input type="text" name="jumlah_barang" id="jumlah_barang"
                                                class="form-control" required>
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
                            <div class="button-row d-flex justify-content-end mt-4">
                                <div class="button-row d-flex justify-content-end mt-4">
                                    <button id="simpanBarangButton" class="btn btn-secondary js-btn-prev" type="submit"
                                        title="Simpan">Simpan</button>
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





document.querySelectorAll('select[name=id_barang]').forEach(select => select.addEventListener('change',
    function() {
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
            })
            .catch(error => console.error('Error:', error));
    }));
document.querySelectorAll('select[name=id_ruangan], select[name=id_barang]').forEach(select => select.addEventListener(
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
                    newOption.text = option.kondisi_barang + ' - ' + option.ket_barang;
                    kondisiSelect.add(newOption);
                });

                // Show or hide the kondisi_barang select based on whether options are available
                kondisiSelect.style.display = data.length > 0 ? 'block' : 'none';
                kondisiSelect.setAttribute('required', data.length > 0 ? 'true' : 'false');
            })
            .catch(error => console.error('Error:', error));
    }));
</script>
<script>
let items = [];

// Define an array to store selected barang
let selectedBarang = [];

// Event listener for the "Simpan" button
$('#simpanBarangButton').on('click', function() {
    // Get values from the form
    const ruangan = $('#id_ruangan').val();
    const barang = $('#id_barang').val();
    const jumlah = $('#jumlah_barang').val();
    const keterangan = $('#ket_tidak_lengkap_awal').val();

    // Add selected barang to the array
    selectedBarang.push({
        ruangan: ruangan,
        barang: barang,
        jumlah: jumlah,
        keterangan: keterangan
    });

    // Clear form inputs
    $('#id_barang').val('');
    $('#jumlah_barang').val('');
    $('#ket_tidak_lengkap_awal').val('');

    // Update the table with selected barang
    updateSelectedBarangTable();
});

// Function to update the selected barang table
function updateSelectedBarangTable() {
    // Clear existing rows
    $('#selectedBarangTable tbody').empty();

    // Add rows based on selected barang array
    selectedBarang.forEach((item, index) => {
        $('#selectedBarangTable tbody').append(`
            <tr>
                <td>${index + 1}</td>
                <td>${item.barang}</td>
                <td>${item.jumlah}</td>
                <td>${item.keterangan || '-'}</td>
                <td><button class="btn btn-danger btn-sm" onclick="removeSelectedBarang(${index})">Remove</button></td>
            </tr>
        `);
    });
}

// Function to remove selected barang
function removeSelectedBarang(index) {
    selectedBarang.splice(index, 1);
    updateSelectedBarangTable();
}
</script>

@endpush