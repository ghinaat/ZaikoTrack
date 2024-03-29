@extends('layouts.demo')
@section('title', 'List Barang')
@section('css')
<link rel="stylesheet" href="{{asset('fontawesome-free-6.4.2-web\css\all.min.css')}}">
<style>

</style>
@endsection
@section('breadcrumb-name')
List Barang
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-md-7">
                            <h4 class="m-0 text-dark">
                                <i class="fa-solid fa-door-open"></i> &nbsp;{{$ruangan->nama_ruangan}}
                            </h4>
                        </div>
                        <div class="col-lg-5 col-md-8 col-sm-12 text-end">
                            <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                <li class="nav-item" id="option1" class="active">
                                    <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center active"
                                        data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                        <i class="fa-solid fa-screwdriver-wrench"></i>
                                        <span class="ms-2">Alat & Perlengkapan</span>
                                    </a>
                                </li>
                                &nbsp;
                                <li class="nav-item" id="option2">
                                    <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center "
                                        data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                        <i class="fa-solid fa-suitcase"></i>
                                        <span class="ms-2">Bahan Praktik</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="tableAlatPerlengkapan" class="card-body m-0">
                    <div class="table-container">
                        <div class="table-responsive">
                            <div class="mb-2">
                                <button class="btn btn-primary mb-2"
                                    onclick="notificationBeforeAdd(event, this)">Tambah</button>
                            </div>
                            <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Barang</th>
                                        <th>Kode Barang</th>
                                        <th>Kondisi</th>
                                        <th style="width:130px;">Keterangan</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($inventaris)
                                    @foreach($inventaris as $key => $barang)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$barang->barang->nama_barang}}</td>
                                        <td>{{$barang->barang->kode_barang}}</td>
                                        <td>
                                            @if($barang->kondisi_barang == 'rusak')
                                            <span class="badge bg-gradient-danger">Rusak</span>
                                            @elseif($barang->kondisi_barang == 'tidak_lengkap')
                                            <span class="badge bg-gradient-secondary">Tidak Lengkap</span>
                                            @else
                                            <span class="badge bg-gradient-success">Lengkap</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($barang->ket_barang)
                                            {{$barang->ket_barang}}
                                            @else
                                            <div style='display: flex; justify-content: center;'>
                                                <span>-</span>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            @include('components.action-buttons', ['id' =>
                                            $barang->id_inventaris,
                                            'key'
                                            =>
                                            $key, 'route' => 'inventaris'])
                                        </td>
                                    </tr>

                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <a href="{{route('inventaris.index')}}" class="btn btn-primary ">
                            Kembali
                        </a>
                    </div>
                </div>
                <div id="tableBahanPraktik" class="card-body m-0">
                    <div class="table-container">
                        <div class="table-responsive">
                            <div class="mb-2">
                                <button class="btn btn-primary mb-2" data-toggle="modal"
                                    data-target="#addModal">Tambah</button>
                            </div>
                            <table id="myTable1" class="table table-bordered table-striped align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Barang</th>
                                        <th>Stok</th>
                                        <th>Kondisi</th>
                                        <th style="width:130px;">Keterangan</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($inventaris)
                                    @foreach($inventaris as $key => $barang)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$barang->barang->nama_barang}}</td>
                                        <td>{{$barang->kode_barang}}</td>
                                        <td>
                                            @if($barang->kondisi_barang == 'rusak')
                                            <span class="badge bg-gradient-danger">Rusak</span>
                                            @elseif($barang->kondisi_barang == 'tidak_lengkap')
                                            <span class="badge bg-gradient-secondary">Tidak Lengkap</span>
                                            @else
                                            <span class="badge bg-gradient-success">Lengkap</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($barang->ket_barang)
                                            {{$barang->ket_barang}}
                                            @else
                                            <div style='display: flex; justify-content: center;'>
                                                <span>-</span>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            @include('components.action-buttons', ['id' =>
                                            $barang->id_inventaris,
                                            'key'
                                            =>
                                            $key, 'route' => 'inventaris'])
                                        </td>
                                    </tr>

                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <a href="{{route('inventaris.index')}}" class="btn btn-primary ">
                            Kembali
                        </a>
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
</div>
</div>

<!-- Modal Edit Pegawai -->
@foreach($inventaris as $key => $barang)
<div class="modal fade" id="editModal{{$barang->id_inventaris}}" tabindex="-1" role="dialog"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Pegawai
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('inventaris.update', $barang->id_inventaris)}}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_ruangan" id="id_ruangan" class="form-control"
                        value="{{ $ruangan->id_ruangan }}">
                    <div class="form-group">
                        <label for="id_barang">Nama Barang</label>
                        <select class="form-select" name="id_barang" id="id_barang" required>
                            @foreach($barangEdit->all() as $id_barang =>
                            $nama_barang)
                            <option value="{{ $id_barang }}" @if($barang->
                                id_barang
                                == $id_barang) selected @endif>
                                {{ $nama_barang }}</option>
                            @endforeach
                        </select>
                        @error('id_barang')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group" id="stokBarangField">
                        <!-- Add an ID to the form group for easy reference -->
                        <label for="jumlah_barang">Stok Barang</label>
                        <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control"
                            value="{{old('jumlah_barang', $barang->jumlah_barang)}}" required>
                        @error('jumlah_barang')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputkondisi_barang">Kondisi
                            Barang</label>
                        <select class="form-select @error('kondisi_barang') is-invalid @enderror"
                            id="exampleInputkondisi_barang" name="kondisi_barang">
                            <option value="lengkap" @if($barang->
                                kondisi_barang
                                ==
                                'lengkap' ||
                                old('kondisi_barang')=='lengkap'
                                )selected @endif>
                                Lengkap
                            </option>
                            <option value="tidak_lengkap" @if($barang->
                                kondisi_barang
                                ==
                                'tidak_lengkap' ||
                                old('kondisi_barang')=='tidak_lengkap'
                                )selected @endif>
                                Tidak Lengkap
                            </option>
                            <option value="rusak" @if($barang->
                                kondisi_barang
                                ==
                                'rusak' ||
                                old('kondisi_barang')=='rusak'
                                )selected @endif>
                                Rusak
                            </option>
                        </select>
                        @error('kondisi_barang')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="ket_barang">Ketarangan Barang</label>
                        <input type="text" name="ket_barang" id="ket_barang" class="form-control"
                            value="{{old('ket_barang', $barang->ket_barang)}}">
                        <small class="form-text text-muted">*wajib diisi
                            ketika
                            barang tidak lengkap/rusak. </small>
                        @error('ket_barang')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
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
<!-- end -->

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Inventaris</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class=" modal-body">
                <form id="addForm" action="{{ route('inventaris.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_ruangan" id="id_ruangan" class="form-control"
                        value="{{ $ruangan->id_ruangan }}">
                    <div class="form-group">
                        <label for="id_barang">Nama Barang</label>
                        <select class="form-select" name="id_barang" id="id_barang" required>
                            @foreach($barangs as $key => $br)
                            <option value="{{$br->id_barang }}" @if( old('id_barang')==$br->id_barang)selected
                                @endif>
                                {{$br->nama_barang}}
                            </option>
                            @endforeach
                        </select>
                        @error('id_barang')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group" id="stokBarangField">
                        <div class="form-group">
                            <label for="jumlah_barang">Stok Barang</label>
                            <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" required>
                            @error('jumlah_barang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputkondisi_barang">Kondisi Barang</label>
                        <select class="form-select @error('kondisi_barang') is-invalid @enderror"
                            id="exampleInputkondisi_barang" name="kondisi_barang">
                            <option value="lengkap" @if( old('kondisi_barang')=='lengkap' )selected @endif>Lengkap
                            </option>
                            <option value="tidak_lengkap" @if( old('kondisi_barang')=='tidak_lengkap' )selected @endif>
                                Tidak Lengkap
                            </option>
                            <option value="rusak" @if( old('kondisi_barang')=='rusak' )selected @endif>Rusak
                            </option>
                        </select>
                        @error('kondisi_barang')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
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



function showAddModal() {

    $("#add-modal").modal('show');
    console.log("Menampilkan modal tambah");
}

document.addEventListener('DOMContentLoaded', function() {
    // Simpan referensi ke elemen-elemen yang diperlukan
    const option1 = document.getElementById('option1');
    const option2 = document.getElementById('option2');
    const tableAlatPerlengkapan = document.getElementById('tableAlatPerlengkapan');
    const tableBahanPraktik = document.getElementById('tableBahanPraktik');

    // Tentukan fungsi untuk menampilkan atau menyembunyikan tabel berdasarkan radio button yang dipilih
    function handleRadioChange() {
        if (option1.classList.contains('active')) {
            tableAlatPerlengkapan.style.display = 'block';
            tableBahanPraktik.style.display = 'none';
        } else if (option2.classList.contains('active')) {
            tableAlatPerlengkapan.style.display = 'none';
            tableBahanPraktik.style.display = 'block';
        }
    }

    function hideStokBarangField() {
        const modalElements = document.querySelectorAll('[id^="addModal"]');
        modalElements.forEach((modal) => {
            const modalId = modal.getAttribute('id');
            const stokBarangField = modal.querySelector(
                '#jumlah_barang'); // Update this selector if needed

            // Check if the modal ID contains the substring "option1" or "option2"
            if (modalId.includes('tableAlatPerlengkapan')) {
                // Hide the "Stok Barang" field for option1
                stokBarangField.style.display = 'none';
            } else if (modalId.includes('tableBahanPraktik')) {
                // Show the "Stok Barang" field for option2
                stokBarangField.style.display = 'block';
            }
        });
    }

    // Tambahkan kelas active secara langsung dan panggil handleRadioChange
    option1.classList.add('active');
    handleRadioChange();

    // Tambahkan event listener untuk perubahan pada radio button
    option1.addEventListener('click', function() {
        option1.classList.add('active');
        option2.classList.remove('active');
        handleRadioChange();
    });

    option2.addEventListener('click', function() {
        option2.classList.add('active');
        option1.classList.remove('active');
        handleRadioChange();
    });



});
</script>

@endpush