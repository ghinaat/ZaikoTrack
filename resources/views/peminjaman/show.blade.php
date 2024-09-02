@extends('layouts.demo')
@section('title', 'List Barang')
@section('css')
<link rel="stylesheet" href="{{asset('fontawesome-free-6.4.2-web\css\all.min.css')}}">
<link rel="stylesheet" href="{{asset('css\show.css')}}">
<style>
.border-divider {
    border-bottom: 0.3vh solid #e5e5e5;
}
</style>
@endsection
@section('breadcrumb-name')
Peminjaman / List Barang
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
      <div class="col-sm-12 col-md-4">
        <div class="card h-30 mb-4">
          <div class="card-header pb-0 px-3">
            <div class="row">
              <div class="d-flex align-items-center">
                <div class="">
                  <i class="fa-solid fa-boxes-stacked fa-lg"></i>
                </div>
                <div class="d-flex flex-column  ms-3">
                  <h5 class="mb-0">Peminjaman</h5>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body pt-4 p-3">
            <ul class="list-group">
              <li class=" d-flex mb-2 border-divider">
                <div class="col-12">
                  <div class="d-flex flex-column">
                    <h6 class="mt-2 text-secondary text-xs">Nama Lengkap</h6>
                    <div class="col-12 text-dark text-sm font-weight-bold mb-3">
                        @if ($peminjaman->status === 'guru')
                        {{ $peminjaman->guru ? $peminjaman->guru->nama_guru : 'N/A' }} 
                        @elseif ($peminjaman->status === 'karyawan')
                        {{ $peminjaman->karyawan ? $peminjaman->karyawan->nama_karyawan : 'N/A' }} 
                        @else
                        {{ $peminjaman->users ? $peminjaman->users->name : 'N/A' }}
                        @endif
                  </div>
                  
                  </div>
                </div>
              </li>
              <li class=" d-flex justify-content-betweenborder-radius-lg mb-2 border-divider">
                <div class="col-12">
                  <div class="d-flex flex-column">
                    @if($peminjaman->status == 'siswa' )
                    <h6 class="mt-2 text-secondary text-xs">Kelas</h6>
                    <div class="col-12 text-dark text-sm font-weight-bold mb-3">
                      {{ $peminjaman->users->profile->kelas ?? '' }} {{ $peminjaman->users->profile->jurusan ?? '' }}
                    </div>

                    

                    @else
                    <h6 class="mt-2 text-secondary text-xs">Status</h6>
                    <div class="col-12 text-dark text-sm font-weight-bold mb-3">
                      {{ $peminjaman->status ?? '' }}
                    @endif              
                  </div>
                </div>
              </li>
              <li class=" d-flex justify-content-betweenborder-radius-lg mb-2 border-divider">
                <div class="col-12">
                  <div class="d-flex flex-column">
                    <h6 class="mt-2 text-secondary text-xs">Tanggal peminjaman</h6>
                    <div class="col-12 text-dark text-sm font-weight-bold mb-3">
                      {{\Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d F Y') ?? '' }}
                    </div>
                  </div>
                </div>
              </li>
              <li class="d-flex justify-content-between border-radius-lg mb-2 border-divider">
                <div class="col-12">
                    <div class="d-flex flex-column">
                        <h6 class="mt-2 text-secondary text-xs">Tanggal Pengembalian</h6>
                        <div class="col-12 text-dark font-weight-bold mb-3">
                            @php
                            $tglKembali = \Carbon\Carbon::parse($peminjaman->tgl_kembali);
                            $currentDate = \Carbon\Carbon::now();
                        
                            // Periksa apakah tanggal kembali sudah terlewati
                            if ($currentDate->greaterThan($tglKembali)) {
                                $badgeClass = 'badge bg-gradient-danger'; // Tanggal kembali sudah terlewati
                            } else {
                                // Hitung sisa hari dari tanggal kembali
                                $daysRemaining = $currentDate->diffInDays($tglKembali);
                                
                                if ($daysRemaining <= 3) {
                                    $badgeClass = 'badge bg-gradient-warning'; // Kurang dari atau sama dengan 3 hari
                                } else {
                                    // Default badge class
                                    $badgeClass = '';
                                }
                            }
                        @endphp
                            <span class="{{ $badgeClass }}">
                                {{ \Carbon\Carbon::parse($peminjaman->tgl_kembali)->format('d F Y') ?? '' }}
                            </span>
                        </div>
                    </div>
                </div>
            </li>
            
              <li class="border-0 d-flex justify-content-between ps-0  border-radius-lg mb-2">
                <div class="col-6">
                  <div class="d-flex flex-column">
                    <h6 class="mt-2 text-secondary text-xs">Keterangan</h6>
                    <div class="col-12 text-dark text-sm font-weight-bold mb-2">
                      @if($peminjaman->keterangan_peminjaman)
                        {{ $peminjaman->keterangan_peminjaman ?? '' }}
                      @else
                        -
                      @endif
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    
      <div class="col-12 col-sm-12 col-md-8">
            <div class="card mb-4">
                <div class="card-body m-0">
                    <div class="table-container">
                        <div class="table-responsive">
                            <div class="mb-2">
                                <button class="btn btn-primary mb-2" onclick="notificationBeforeAddPeminjaman(event, this, {{ $peminjaman->id_peminjaman }})" data-id-peminjaman="{{ $peminjaman->id_peminjaman }}">Tambah</button>
                            </div>
                            <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="center-th">No.</th>
                                        <th class="center-th">Nama<br>Barang</th>
                                        <th class="center-th">Kode<br>Barang</th>
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
                                        <td>{{$barang->inventaris->barang->kode_barang}}</td>
                                        <td>
                                            @if($barang->status == 'dipinjam')
                                            <span class="badge bg-gradient-danger">Dipinjam</span>
                                            @elseif($barang->status == 'sudah_dikembalikan')
                                            <span class="badge bg-gradient-success">Sudah Dikembalikan</span>
                                            @elseif($barang->status == 'proses_pengajuan')
                                            <span class="badge bg-gradient-secondary">Proses Pengajuan</span>
                                            @else
                                            <span class="badge bg-gradient-warning">Pengajuan Ditolak</span>
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
                                            <div class="btn-group">
                                            @if($barang->status == "dipinjam")
                                           
                                                <button class="btn btn-info btn-xs mx-1"
                                                data-id-detail-peminjaman="{{ $barang->id_detail_peminjaman }}"
                                                onclick="notificationBeforeReturn(event, this)">
                                                 <i class="fa fa-undo"></i>
                                                 </button>   
                                            </div>                                        
                                            @can("isTeknisi", 'isKabeng')
                                            <a href="#" class="btn btn-primary btn-xs edit-button" data-toggle="modal"
                                                data-target="#editModal{{$barang->id_detail_peminjaman}}"
                                                data-id="{{$barang->id_detail_peminjaman}}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('detailPeminjaman.destroy', $barang->id_detail_peminjaman) }}"
                                                onclick="notificationBeforeDelete(event, this, {{$key+1}})"
                                                class="btn btn-danger btn-xs mx-1">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            @endcan
                                            @elseif($barang->status == "sudah_dikembalikan")
                                            <div style='display: flex; justify-content: center;'>
                                                <span> <i class="fas fa-check-circle  fa-2x"
                                                        style="color: #42e619; align-items: center;"></i></span>
                                            </div>
                                            @elseif($barang->status == 'proses_pengajuan')
                                            @can('isSiswa')
                                            <div style='display: flex; justify-content: center;'>
                                                <span> <i class="fa fa-clock  fa-2x"
                                                        style="color: #383c37; align-items: center;"></i></span>
                                            </div>
                                            @endcan
                                            @can("isTeknisi", 'isKabeng')
                                            <a href="#" class="btn btn-primary btn-xs mx-1 " data-toggle="modal"
                                                data-target="#approvalModal{{$barang->id_detail_peminjaman}}"
                                                data-id="{{$barang->id_detail_peminjaman}}" >
                                                <i class="fa-solid fa-file-circle-check"></i>
                                            </a>
                                            @endcan
                                            @else
                                            @can('isSiswa')
                                            <!-- Ensure you are using data-bs-* attributes -->
                                            @if($barang->ket_ditolak_pengjuan)
                                            <button type="button" class="btn btn-warning btn-xs mx-1" 
                                                    data-bs-toggle="popover" 
                                                    data-bs-trigger="focus" 
                                                    title="Alasan Penolakan" 
                                                    data-bs-content="{{$barang->ket_ditolak_pengajuan}}">
                                                <i class="fa fa-info"></i>
                                            </button>
                                            @endif
                                            <button class="btn btn-info btn-xs mx-1"
                                                    data-id-detail-peminjaman="{{ $barang->id_detail_peminjaman }}"
                                                    onclick="notificationBeforeReturn(event, this)">
                                               <i class="fa fa-undo"></i>
                                            </button>   
                                            </div>           
                                            @endcan
                                            @can("isTeknisi", 'isKabeng')
                                                <a href="#" class="btn btn-primary btn-xs mx-1" data-bs-toggle="modal"
                                                data-bs-target="#approvalModal{{$barang->id_detail_peminjaman}}"
                                                data-id="{{$barang->id_detail_peminjaman}}">
                                                <i class="fa-solid fa-file-circle-check"></i>
                                                </a>
                                        @endcan
                                            @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="editModal{{$barang->id_detail_peminjaman}}"
                                        tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit Peminjaman</h5>
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
                                                        <div class="form-row mt-3">
                                                            <div class="form-group mt-2">
                                                                <label for="ket_barang">Keterangan Barang</label>
                                                                <input type="text" name="ket_tidak_lengkap_awal" id="ket_tidak_lengkap_awal" class="form-control  @error('ket_tidak_lengkap_awal') is-invalid @enderror" value="{{$barang->ket_tidak_lengkap_awal}}">
                                                                <small class="form-text text-muted">*wajib diisi ketika
                                                                    barang tidak lengkap/rusak. </small>
                                                                @error('ket_barang')
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
                                    {{-- Modal Inforamsi --}}
                                    
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
            <div class="modal-body">
                <form class="addForm" action="{{ route('detailPeminjaman.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_peminjaman" value="{{ $peminjaman->id_peminjaman }}">
                    <div class="form-row mt-3">
                        <div class="form-group">
                            <label for="id_barang">Kode Barang</label>
                            <select class="form-select" name="id_barang" id="id_barang" required>
                                @if($id_barang_options->isEmpty())
                                <option value="" disabled selected>No data available</option>
                                @else
                                @foreach($id_barang_options as $key => $b)
                                <option value="{{ $b->barang->id_barang }}">
                                    {{ $b->barang->kode_barang }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                            @error('id_barang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        
                        <div class="form-group mt-2">
                            <label for="nama_barang">Nama Barang</label>
                            <input type="text" class="form-control" name="nama_barang" id="nama_barang" readonly>
                            @error('nama_barang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        
                        <div class="form-group mt-2">
                            <label for="id_ruangan">Ruangan</label>
                            <input type="text" class="form-control" name="id_ruangan" id="id_ruangan" readonly>
                            @error('id_ruangan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        
                        <div class="form-group mt-2">
                            <label for="ket_barang">Keterangan Barang</label>
                            <input type="text" name="ket_barang" id="ket_barang" class="form-control @error('ket_barang') is-invalid @enderror">
                            <small class="form-text text-muted">*wajib diisi ketika barang tidak lengkap/rusak.</small>
                            @error('ket_barang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button class="btn btn-danger" type="button" onclick="history.go();" title="Prev">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@foreach($detailPeminjamans as $key => $barang)
<div class="modal fade" id="approvalModal{{$barang->id_detail_peminjaman}}"
    tabindex="-1" role="dialog" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalLabel">Pengajuan Pengembalian Pinjaman</h5>
                <button type="button" class="btn-close" data-dismiss="modal"
                    aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class="modal-body">
            <form action="{{ route('detailPeminjaman.approval', $barang->id_detail_peminjaman)}}"
                method="post">
                @csrf
                @method('PUT')
                <div class="form-row mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_barang" class="form-label">Kode Barang</label>
                                <input type="text" name="kode_barang" id="id_barang"
                                       class="form-control"
                                       value="{{ $barang->inventaris->barang->kode_barang ?? old('kode_barang') }}" readonly>
                                @error('id_barang')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_barang" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" name="nama_barang" id="nama_barang"
                                       value="{{ $barang->inventaris->barang->nama_barang ?? old('nama_barang') }}" readonly>
                                @error('nama_barang')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-group mt-2">
                                <label for="id_ruangan">Ruangan</label>
                                <select class="form-select" name="id_ruangan" id="id_ruangan" required>
                                    @foreach($ruangans as $key => $r)
                                        <option value="{{$r->id_ruangan}}" @if(old('id_ruangan') == $r->id_ruangan || $barang->inventaris->ruangan->id_ruangan == $r->id_ruangan) selected @endif>
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
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-group  mt-2">
                                <label for="kondisi_barang_akhir">Kondisi
                                    Barang</label>
                                <select class="form-select @error('kondisi_barang_akhir') is-invalid @enderror"
                                    id="kondisi_barang_akhir" name="kondisi_barang_akhir">
                                    <option value="lengkap" @if(
                                        old('kondisi_barang_akhir')=='lengkap'
                                        )selected @endif>
                                        Lengkap
                                    </option>
                                    <option value="tidak_lengkap" @if(
                                        old('kondisi_barang_akhir')=='tidak_lengkap'
                                        )selected @endif>
                                        Tidak Lengkap
                                    </option>
                                    <option value="rusak" @if(
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
                        </div>
                    </div>    
                    <div class="form-group" id="ket_tidak_lengkap_akhir_group" style="display: none;">
                        <label for="ket_tidak_lengkap_akhir">Keterangan Barang</label>
                        <input type="text" class="form-control  @error('ket_tidak_lengkap_akhir') is-invalid @enderror" id="ket_tidak_lengkap_akhir" name="ket_tidak_lengkap_akhir"
                               value="{{ old('ket_tidak_lengkap_akhir') ?? $barang->ket_tidak_lengkap_akhir }}">
                        @error('ket_tidak_lengkap_akhir')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>      
                    <div class="form-group">
                        <label for="status">Pengajuan Pengembalian</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="sudah_dikembalikan" @if(old('status') == 'sudah_dikembalikan' || $barang->status == 'sudah_dikembalikan') selected @endif>
                                Pengembalian Diterima
                            </option>
                            <option value="pengajuan_ditolak" @if(old('status') == 'pengajuan_ditolak' || $barang->status == 'pengajuan_ditolak') selected @endif>
                                Tidak Diterima
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-group" id="ket_pengajuan_ditolak_group" style="display: none;">
                        <label for="ket_ditolak_pengajuan">Keterangan Pengajuan Ditolak</label>
                        <input type="text" class="form-control  @error('ket_ditolak_pengajuan') is-invalid @enderror" id="ket_ditolak_pengajuan" name="ket_ditolak_pengajuan"
                               value="{{ old('ket_ditolak_pengajuan') ?? $barang->ket_ditolak_pengajuan }}">
                        @error('ket_ditolak_pengajuan')
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

document.addEventListener('DOMContentLoaded', function() {
    const kondisiSelect = document.getElementById('kondisi_barang_akhir');
    const ket = document.getElementById('ket_tidak_lengkap_akhir_group');
    
    function KeteranganKondisi() {
        if (kondisiSelect.value === 'lengkap') {
            ket.style.display = 'none';
        } else {
            ket.style.display = 'block';
        }
    }

    // Initialize visibility based on current selection
    KeteranganKondisi();

    // Add event listener for change events
    kondisiSelect.addEventListener('change', KeteranganKondisi);
});


document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const ketGroup = document.getElementById('ket_pengajuan_ditolak_group');
    
    function KetPengajuanDitolak() {
        if (statusSelect.value === 'pengajuan_ditolak') {
            ketGroup.style.display = 'block';
        } else {
            ketGroup.style.display = 'none';
        }
    }

    // Initialize visibility based on current selection
    KetPengajuanDitolak();

    // Add event listener for change events
    statusSelect.addEventListener('change', KetPengajuanDitolak);
});

document.querySelectorAll('select[name=id_barang]').forEach(select => select.addEventListener('click', function() {
    const selectedIdBarang = this.value;
    const namaBarangInput = this.closest('.form-group').nextElementSibling.querySelector('input[name=nama_barang]');
    const idRuanganInput = this.closest('.form-group').nextElementSibling.nextElementSibling.querySelector('input[name=id_ruangan]');
    // Fetch nama_barang for the selected id_barang
    fetch(`/fetch-nama-barang/${selectedIdBarang}`)
        .then(response => response.json())
        .then(data => {
            // Set the value of the nama_barang input field
            if (data.length > 0) {
                namaBarangInput.value = data[0].barang.nama_barang;
            } else {
                namaBarangInput.value = ''; // Clear if no data
            }
        })
        .catch(error => console.error('Error fetching nama_barang:', error));

    // Fetch id_ruangan options for the selected id_barang
    fetch(`/fetch-id-barang/${selectedIdBarang}`)
        .then(response => response.json())
        .then(data => {
            // Set the value of the id_ruangan input field
            if (data.length > 0) {
                idRuanganInput.value = data[0].ruangan.nama_ruangan;
            } else {
                idRuanganInput.value = ''; // Clear if no data
            }
        })
        .catch(error => console.error('Error fetching id_ruangan:', error));
}));

var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)
})

function notificationBeforeAddPeminjaman(event, el, dt) {
    event.preventDefault();

    const idPeminjaman = el.getAttribute('data-id-peminjaman');

    console.log('id_peminjaman:', idPeminjaman);

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
            // If the user chooses "Dengan Barcode"
            window.location.href = `/peminjaman/qrcode/${idPeminjaman}`;
        } else {
            // If the user chooses "Tanpa Barcode", display the add modal
            showAddModal();
        }
    });
}

function showAddModal() {
    Swal.close(); // Close the Swal dialog
    $('#addModal').modal('show'); // Show the add modal
}



</script>

@if(count($errors))
<script>
Swal.fire({
    title: 'Input tidak sesuai!',
    text: 'Pastikan inputan sudah sesuai',
    icon: 'error',
});
</script>
@endif
@endpush