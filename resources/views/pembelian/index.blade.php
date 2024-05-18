@extends('layouts.demo')
@section('title', 'List Pembelian')
@section('css')
@endsection
@section('breadcrumb-name')
Pembelian
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h4 class="m-0 text-dark">List Pembelian</h4>
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
                                    <th>Tanggal Pembelian</th>
                                    <th>Nama Toko</th>
                                    <th>Total Pembelian</th>
                                    <th>Stok Barang</th>
                                    <th style="width:0px">Keterangan Anggaran</th>
                                    <th>Nota Pembelian</th>
                                    <th style="width:189px;">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $showDetail = true; @endphp
                                @foreach($pembelian as $key => $pb)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{\Carbon\Carbon::parse($pb->tgl_pembelian)->format('d F Y')}}</td>
                                    <td>{{$pb->nama_toko}}</td>
                                    <td>Rp. {{ isset($subtotalPembelian[$pb->id]) ? number_format($subtotalPembelian[$pb->id], 0, ',', '.') : 0 }}</td>
                                    <td>{{ isset($stoklPembelian[$pb->id]) ? $stoklPembelian[$pb->id] : 0 }}</td>
                                    <td>{{$pb->keterangan_anggaran}}</td>
                                    <td style="text-align: center; ">
                                        @if($pb->nota_pembelian)
                                        <a href="{{ asset('storage/nota_pembelian/' . $pb->nota_pembelian) }}"
                                            download class="btn btn-info btn-xs mx-1">
                                            <i class="ni ni-folder-17"
                                                style="display: inline-block; line-height: normal; vertical-align: middle;"></i>
                                        </a>
                                        @else
                                        <p>-</p>
                                        @endif
                                    </td>
                                    <td>
                                        @include('components.action-buttons', ['id' => $pb->id_pembelian, 'key' => $key,
                                        'route' => 'pembelian'])
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
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Tambah Pembelian</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm" action="{{route('pembelian.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="tgl_pembelian">Tanggal Pembelian</label>
                        <input type="date" name="tgl_pembelian" id="tgl_pembelian" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_toko">Nama Toko</label>
                        <input type="text" name="nama_toko" id="nama_toko" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan_anggaran">Keterangan Anggaran</label>
                        <input type="text" name="keterangan_anggaran" id="keterangan_anggaran"
                            class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="nota_pembelian" class="form-label">Nota Pembelian</label>
                        <img class="img-preview img-fluid mb-1 col-sm-3 d-block">
                        <input class="form-control @error('nota_pembelian') is-invalid @enderror" type="file"
                            id="nota_pembelian" name="nota_pembelian" onchange="previewImageTambah()"
                            accept="image/jpeg, image/jpg, image/png">
                        <small class="form-text text-muted">Allow file extensions : .jpeg .jpg .png</small>
                        @error('nota_pembelian') <span class="textdanger">{{$message}}</span> @enderror
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

@foreach($pembelian as $key => $pb)

<div class="modal fade" id="editModal{{$pb->id_pembelian}}" tabindex="-1" role="dialog"
    aria-labelledby="editModalLabel{{$pb->id_pembelian}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Pembelian</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm" action="{{route('pembelian.update', $pb->id_pembelian)}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="tgl_pembelian">Tanggal Pembelian</label>
                        <input type="date" name="tgl_pembelian" id="tgl_pembelian" class="form-control"
                            value="{{old('tgl_pembelian', $pb->tgl_pembelian)}}" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_toko">Nama Toko</label>
                        <input type="text" name="nama_toko" id="nama_toko" class="form-control"
                            value="{{old('nama_toko', $pb->nama_toko)}}" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan_anggaran">Keterangan Anggaran</label>
                        <textarea rows="3" type="date" name="keterangan_anggaran" id="keterangan_anggaran"
                            class="form-control"
                            required>{{old('keterangan_anggaran', $pb->keterangan_anggaran)}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="nota_pembelian">Nota Pembelian</label>
                        @if($pb->nota_pembelian)
                        <a href="{{asset('storage/nota_pembelian/' . $pb->nota_pembelian)}}" target="_blank">
                            <img class="img-preview-edit img-fluid mb-2 col-sm-2 d-block"
                                src="{{asset('storage/nota_pembelian/' . $pb->nota_pembelian)}}">
                        </a>
                        @endif
                        <input class="form-control @error('nota_pembelian') is-invalid @enderror" type="file"
                            id="nota_pembelian_edit" name="nota_pembelian" onchange="previewImageEdit()"
                            accept="image/jpeg, image/jpg, image/png">
                        <small class="form-text text-muted">Allow file extensions : .jpeg .jpg .png</small>
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


function previewImageTambah() {
    const foto = document.querySelector('#nota_pembelian');
    const imgPreview = document.querySelector('.img-preview');

    imgPreview.style.display = 'block';

    const ofReader = new FileReader();
    ofReader.readAsDataURL(foto.files[0]);

    ofReader.onload = function(oFREvent) {
        imgPreview.src = oFREvent.target.result;
    }
}

function previewImageEdit() {
    const foto2 = document.querySelector('#nota_pembelian_edit');
    const imgPreview2 = document.querySelector('.img-preview-edit');

    imgPreview2.style.display = 'block';

    const ofReader2 = new FileReader();
    ofReader2.readAsDataURL(foto2.files[0]);

    ofReader2.onload = function(oFREvent) {
        imgPreview2.src = oFREvent.target.result;
    }
}

var rupiah = document.getElementById('total_pembelian');
rupiah.addEventListener('keyup', function(e) {
    rupiah.value = formatRupiah(this.value, 'Rp. ');
});

function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

var subtotal_pembelian_inputs = document.querySelectorAll('[data-format="rupiah"]');

subtotal_pembelian_inputs.forEach(function(total_pembelian_edit) {
    // Inisialisasi nilai pada saat halaman dimuat
    total_pembelian_edit.value = formatRupiah(total_pembelian_edit.value, 'Rp. ');

    // Tambahkan event listener untuk mengubah format saat ada perubahan nilai
    total_pembelian_edit.addEventListener('keyup', function(e) {
        this.value = formatRupiah(this.value, 'Rp. ');
    });

    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
});

// $("#addForm").on('click', '.js-btn-save', function(e) {
//     e.preventDefault();
//     var form = $(this).closest('form#addForm');
//     var url = form.attr('action');
//     var method = form.attr('method');
//     var data = form.serialize();
//     $.ajax({
//         type: method,
//         url: url,
//         data: data,
//     })
//     .done(function(response) {
//         Swal.fire({
//                     icon: 'success',
//                     title: 'Sukses!',
//                     text: response.message,
//                     showCancelButton: true,
//                     confirmButtonColor: '#3085d6',
//                     cancelButtonColor: '#d33',
//                     confir mButtonText: 'Ya',
//                     cancelButtonText: 'Tidak',
//                 }).then((result) => {
//                     if (result.isConfirmed) {
//                         window.location.href = '/detailpembelian/' + response.id_pembelian;
//                     } else {
//                         window.location.href = '/pembelian';
//                     }
//                 });
//     });
// });



</script>


@endpush