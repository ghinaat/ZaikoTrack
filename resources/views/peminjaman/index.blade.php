@extends('layouts.demo')
@section('title', 'List Peminjaan')
@section('css')

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
                        <!-- Adjusted to align items at the bottom -->
                        <div class="col-md-10">
                            <form action="{{ route('peminjaman.filter') }}" method="GET" class="form-inline mb-3">
                                <div class="form-group mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="col-md-6 mb-3">
                                            <label for="tglawal" class="my-label mr-2">Tanggal
                                                Awal:&nbsp;&nbsp;</label>&nbsp;&nbsp;
                                            <input type="date" id="tglawal" name="tglawal" required class="form-control"
                                                value="{{request()->input('tglawal')}}">&nbsp;&nbsp;
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tglakhir" class="form-label">Tanggal
                                                Akhir:</label>&nbsp;&nbsp;
                                            <input type="date" id="tglakhir" name="tglakhir" required
                                                class="form-control"
                                                value="{{request()->input('tglakhir')}}">&nbsp;&nbsp;
                                        </div>
                                        <div class="col-md-2 mb-3 ml-md-auto">
                                            <button type="submit"
                                                class="btn btn-primary align-bottom">Tampilkan</button>
                                            <!-- Added align-bottom -->
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-primary mb-2"
                                onclick="notificationBeforeAdds(event, this)">Tambah</button>
                        </div>
                        <div class="col-md-2 d-flex flex-column  justify-content-md-end">
                            <a href="{{ route('peminjaman.filter', ['tglawal' => request()->input('tglawal'), 'tglakhir' => request()->input('tglakhir')]) }}"
                                class="btn btn-danger">Export Data</a>
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
                                @php
                                $sortedPeminjaman = $peminjaman->sortByDesc('id_peminjaman');

                                @endphp
                                @foreach($sortedPeminjaman as $key => $peminjaman)
                                <tr>
                                    <td></td>
                                    <td>{{\Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d F Y')}}</td>
                                    @if ($peminjaman->status === 'guru')
                                    <td>{{ $peminjaman->guru ? $peminjaman->guru->nama_guru : 'N/A' }}</td>
                                    @elseif ($peminjaman->status === 'karyawan')
                                    <td>{{ $peminjaman->karyawan ? $peminjaman->karyawan->nama_karyawan : 'N/A' }}
                                    </td>
                                    @else
                                    <td>{{ $peminjaman->siswa ? $peminjaman->siswa->nama_siswa : 'N/A' }}</td>
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
                                        @include('components.action-buttons', ['id' =>
                                        $peminjaman->id_peminjaman, 'key'
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
                                                    <h5 class="modal-title" id="editModalLabel">Pengembalian
                                                        Barang</h5>
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
</script>




@endpush