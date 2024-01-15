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
                    <div class="mb-2">
                        <a class="btn btn-primary mb-2" href="{{route('pemakaian.create')}}">Tambah</a>
                    </div>
                    <div class="table-responsive ">
                        <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal Pakai</th>
                                    <th>Nama Pemakai</th>
                                    <th>Kelas</th>
                                    <th>Barang</th>
                                    <th style="width:189px;">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupedPemakaians as $key => $pakai)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{\Carbon\Carbon::parse($pakai->tgl_pakai)->format('d F Y')}}</td>
                                    <td>{{$pakai->nama_lengkap}}</td>
                                    <td>{{$pakai->kelas}} {{$pakai->jurusan}}</td>
                                    <td>
                                        <a href="{{ route('pemakaian.showDetail', $pakai->id_pemakaian) }}"
                                            class="btn btn-info btn-xs mx-1">
                                            <i class="fa fa-rectangle-list"></i>
                                        </a>
                                    </td>
                                    <td>
                                        @include('components.action-buttons', ['id' => $pakai->id_pemakaian, 'key' => $key,
                                        'route' => 'pemakaian'])
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
                <form id="addForm" action="{{route('pemakaian.update', $pakai->id_pemakaian)}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="tgl_pakai">Tanggal Pakai</label>
                        <input type="text" name="tgl_pakai" id="tgl_pakai" class="form-control"
                            value="{{\Carbon\Carbon::parse($pakai->tgl_pakai)->format('d F Y') ?? old('tgl_pakai')}}                            " readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama_pemakai">Nama Pemakai</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                            value="{{old('nama_lengkap', $pakai->nama_lengkap)}}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="kelas" class="mb-0">Kelas</label>
                                <input class=" form-control" type="text" name="kelas" id="kelas" value="{{old('kelas', $pakai->kelas)}}" >
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="jurusan" class="mb-0">Jurusan</label>
                                <input class="form-control" type="text" name="jurusan" id="jurusan" value="{{old('jurusan', $pakai->jurusan)}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="keterangan_pemakaian">Keterangan Pemakaian</label>
                        <textarea rows="3" name="keterangan_pemakaian" id="keterangan_pemakaian" class="form-control" >{{old('keterangan_pemakaian', $pakai->keterangan_pemakaian)}}
                        </textarea>
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
<script src="{{ asset('js/pemakaian.js ') }}"></script>
{{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> --}}

<form action="" id="delete-form" method="post">
    @method('delete')
    @csrf
</form>
<script>
$(document).ready(function() {
    // Handle click untuk form "Pilih Barang"
    // Gunakan event delegation dengan .on()
    $("#addFormCart").on('click', '.js-btn-choose', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');  // Gunakan closest('form') untuk mencari formulir terdekat
        var url = form.attr('action');
        var method = form.attr('method');
        var data = form.serialize();
        $.ajax({
            type: method,
            url: url,
            data: data,
        })
        .done(function(response) {
            // Penanganan jika sukses
            var newRow = '<tr>' +
                '<td>' + (response.key + 1) + '</td>' +
                '<td>' + response.nama_barang + '</td>' +
                '<td>' + response.jumlah_barang + '</td>' +
                '<td>' + (response.keterangan_pemakaian ? response.keterangan_pemakaian : '-') + '</td>' +
                '<td><button class="btn btn-danger btn-sm removeBtn" data-cart-id="' + response.id_cart + '">Remove</button></td>' +
                '</tr>';
            $('#cartTable tbody').append(newRow);

            console.log('Form submitted!');
            form[0].reset(); // Gunakan form[0] untuk mereset formulir
        })
        .fail(function(xhr) {
            // Penanganan jika gagal
            console.error('Error sending data:', xhr.responseText);
        });
    });
});

$(document).ready(function() {
    $('#cartTable tbody').on('click', '.removeBtn', function(e) {
        e.preventDefault();

            // $.ajaxSetup({
            // headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });
        let cartId = $(this).data('cart-id');
        // Ambil token CSRF dari tag meta
        let token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Apa kamu yakin?',
            text: "Data akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $(this).closest('tr').remove();
                $.ajax({
                    url: `/cart/${cartId}`,
                    type: "DELETE",
                    cache: false,
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // success: function(response) {
                    //     console.log('ok');
                    //     // Hapus baris dari tabel setelah penghapusan berhasil
                    //     $(this).closest('tr').remove();
                    // },
                });
            }
        });
    });
});
</script>
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
document.querySelectorAll('select[name=id_barang]').forEach(select => select.addEventListener('change',
    function() {
        const id_barangSelect = this.closest('.form-group').nextElementSibling.querySelector(
            'select[name=id_ruangan]');
        const selectedIdRuangan = this.value;

        // Fetch id_barang options for the selected id_ruangan
        fetch(`/get-ruangan-options/${selectedIdRuangan}`)
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
</script>

@endpush