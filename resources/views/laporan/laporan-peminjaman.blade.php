@extends('layouts.demo')
@section('title', 'List Laporan')
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
Laporan Peminjaman
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h4 class="m-0 text-dark">Data Laporan Peminjaman</h4>
                </div>
                <div class="card-body m-0">
                    <div class="row align-items-end">
                      
                            <form action="{{ route('laporan-peminjaman') }}" method="GET" class="row align-items-end">
                               <div class="form-group">
                                    <label for="id_barang" class="form-label">Barang:</label>
                                    <select id="id_barang" name="id_barang"
                                        class="form-select @error('id_barang') is-invalid @enderror">
                                        <option value="0" @if(session('selected_id_barang', 0)==0) selected @endif>All
                                        </option>
                                        @foreach ($barang as $barang)
                                        <option value="{{ $barang->id_barang }}" @if($barang->id_barang ==
                                            session('selected_id_barang')) selected @endif>{{ $barang->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama_peminjam" class="form-label">Nama Peminjam:</label>
                                    <select id="nama_peminjam" name="nama_peminjam" class="form-select @error('nama_peminjam') is-invalid @enderror">
                                        <option value="" @if(session('selected_nama_peminjam', '') == '') selected @endif>All</option>
                                        @foreach (session('all_peminjam_names', []) as $peminjam_name)
                                            @if ($peminjam_name != '-')
                                                <option value="{{ $peminjam_name }}" @if(session('selected_nama_peminjam') == $peminjam_name) selected @endif>{{ $peminjam_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 col-lg-3">
                                    <div class="form-group">
                                    <label for="tglawal" class="form-label">Tanggal Awal:</label>
                                    <input type="date" id="tglawal" name="tglawal" class="form-control"
                                        value="{{ request()->input('tglawal') }}">
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-3">
                                    <div class="form-group">
                                    <label for="tglakhir" class="form-label">Tanggal Akhir:</label>
                                    <input type="date" id="tglakhir" name="tglakhir" class="form-control"
                                        value="{{ request()->input('tglakhir') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mt-md-0 mt-3">
                                <div class="d-flex align-items-center">
                                    <button type="submit" class="btn btn-primary mt-md-4" style="width: 120px;">Tampilkan</button>&nbsp;
                                    <a href="{{ route('laporan-peminjaman') }}" class="btn btn-info mt-md-4 ml-md-auto" style="width: 120px;">Refresh</a>&nbsp;
                                    <a href="{{ route('downloadpdf', [
                                        'tglawal' => request()->input('tglawal'),
                                        'tglakhir' => request()->input('tglakhir'),
                                        'id_barang' => request()->input('id_barang'),
                                        'nama_peminjam' => session('selected_nama_peminjam') ]) }}" class="btn btn-danger mt-md-4 ml-2 ml-md-auto" style="min-width: 150px;">Unduh PDF
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>            
                </div>
            </div>
            @if(request()->filled('id_barang') || request()->filled('tglawal') || request()->filled('tglakhir') || request()->filled('nama_peminjam'))
            @if(isset($peminjaman) && count($peminjaman) > 0)
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive ">
                        <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Nama</th>
                                    <th>Jurusan</th>
                                    <th>List Barang</th>
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
                                    <td>{{ $peminjaman->karyawan ? $peminjaman->karyawan->nama_karyawan : 'N/A' }}</td>
                                    @else
                                    <td>{{ $peminjaman->users ? $peminjaman->users->name : 'N/A' }}</td>
                                    @endif
                                    @if($peminjaman->kelas == null && $peminjaman->jurusan == null)
                                    <td>
                                        <div style='display: flex; justify-content: center;'>-
                                        </div>
                                    </td>
                                    @else
                                    <td>{{ $peminjaman->kelas }} {{ $peminjaman->jurusan }}</td>
                                    @endif
                                    <td>
                                        <a href="{{ route('peminjaman.showDetail', $peminjaman->id_peminjaman) }}"
                                            class="btn btn-info btn-xs mx-1">
                                            <i class="fa fa-rectangle-list"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-info" role="alert">
                Tidak ada data yang cocok dengan filter yang diberikan.
            </div>
            @endif
            @else
            <div class="alert alert-info" role="alert">
                Silakan pilih filter untuk menampilkan data.
            </div>
            @endif
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
</script>
@endpush