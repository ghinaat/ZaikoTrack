@extends('layouts.demo')
@section('title', 'Tambah Peminjaman')
@section('css')
<link rel="stylesheet" href="{{asset('css\style.css')}}">
@endsection
@section('breadcrumb-name')
Tambah Peminjaman
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mb-2">
                <div class="card-header pb-0">
                    <h1 class="content__title content__title--m-sm">Form Peminjaman</h1>
                </div>
                <div class="card-body m-0">
                    <!--multisteps-form-->

                    <div class="container overflow-hidden">
                        <!--multisteps-form-->
                        <div class="multisteps-form">
                            <!--progress bar-->
                            <div class="row justify-content-center">
                                <div class="col-12 col-lg-8 ml-auto mr-auto mb-2">
                                    <div class="multisteps-form__progress">
                                        <button class="multisteps-form__progress-btn js-active" type="button"
                                            title="User Info">Alat & Bahan
                                        </button>
                                        <button class="multisteps-form__progress-btn" type="button"
                                            title="Address">Peminjam</button>
                                        <button class="multisteps-form__progress-btn" type="button" title="Order Info"
                                            id="order-info">Detail
                                            Peminjaman</button>
                                    </div>
                                </div>
                            </div>
                            <!--form panels-->
                            <div class="row">

                                <form class="multisteps-form__form" action="{{ route('peminjaman.store') }}"
                                    method="post">
                                    @csrf
                                    <div class="multisteps-form__panel  rounded bg-white js-active"
                                        data-animation="scaleIn">
                                        <h4 class="multisteps-form__title">Alat & Bahan</h4>
                                        <div class="multisteps-form__content">
                                            <div class="form-row mt-3">
                                                <div id="cart-container">
                                                    <div class="table-container">
                                                        <div class="table-responsive">
                                                            <div class="mb-2">
                                                                <button class="btn btn-primary ml-auto js-btn-add"
                                                                    type="button" title="Next"
                                                                    id="tambahButton">Tambah</button>
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
                                                                            <a href="{{ route('peminjaman.destroyCart', $cart->id_cart) }}"
                                                                                onclick="notificationBeforeDelete(event, this, {{$key+1}})"
                                                                                class="btn btn-danger btn-xs mx-1">
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

                                            <div class="button-row d-flex justify-content-end mt-4">

                                                <a href="{{ route('peminjaman.index') }}" id="cancelButton"
                                                    class="btn btn-danger">Batal</a>
                                                <button class=" btn btn-primary ml-auto js-btn-next" type="button"
                                                    title="Next">Next
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__panel  rounded bg-white " data-animation="scaleIn">

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
                                                            <input type="text" name="kelas" id="kelas"
                                                                class="form-control" required>
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
                                                <button class="btn btn-secondary js-btn-prev" type="button"
                                                    title="Prev">Kembali</button>
                                                <button class="btn btn-primary ml-auto js-btn-next" type="button"
                                                    title="Next">Next</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!--single form panel-->
                                    <div class="multisteps-form__panel  rounded bg-white" data-animation="scaleIn">
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
                                                    title="Prev">Kembali</button>
                                                <button type="submit"
                                                    class="btn btn-primary js-btn-save">Simpan</button>
                                                </button>
                                            </div>
                                </form>
                            </div>
                        </div>
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

                                <div class="button-row d-flex justify-content-end mt-4 ">
                                    <button class="btn btn-secondary js-btn-back" type="button"
                                        title="Prev">Kembali</button>
                                    <button type="submit" class="btn btn-primary js-btn-save">Simpan</button>
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
<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>
<form action="" id="delete-form" method="post">
    @method('delete')
    @csrf
</form>
<script src="../js/script.js"></script>
<script>
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

$(document).ready(function() {
    $('#cancelButton').on('click', function(e) {
        e.preventDefault(); // Prevent the default behavior of the anchor tag

        // Make an AJAX request to destroy the cart data
        $.ajax({
            url: "{{ route('peminjaman.clearCart') }}",
            type: "POST",
            dataType: "json",
            success: function(response) {
                // Handle the success response, e.g., redirect to another page
                window.location.href = "{{ route('peminjaman.index') }}";
            },
            error: function(error) {
                // Handle the error response
                console.error('Error:', error);
            }
        });
    });
});



document.querySelectorAll('select[name=id_barang]').forEach(select => select.addEventListener('click',
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

                const event = new Event('change');
                id_barangSelect.dispatchEvent(event);
            })
            .catch(error => console.error('Error:', error));

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