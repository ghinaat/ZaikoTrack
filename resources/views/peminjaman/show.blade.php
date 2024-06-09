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
                      {{ $peminjaman->kelas ?? '' }} {{ $peminjaman->jurusan ?? '' }}
                    </div>
                    @elseif($peminjaman->status == 'guru' )
                    <h6 class="mt-2 text-secondary text-xs">Jurusan</h6>
                    <div class="col-12 text-dark text-sm font-weight-bold mb-3">
                      {{ $peminjaman->jurusan ?? '' }}
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
    
        <div class="col-12 col-sm-8">
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
                                            <div class="btn-group">
                                            
                                                <button class="btn btn-info btn-xs mx-1"
                                                data-id-detail-peminjaman="{{ $barang->id_detail_peminjaman }}"
                                                onclick="notificationBeforeReturn(event, this)">
                                                 <i class="fa fa-undo"></i>
                                                 </button>                                           
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
                                            @else
                                            <div style='display: flex; justify-content: center;'>
                                                <span> <i class="fas fa-check-circle  fa-2x"
                                                        style="color: #42e619; align-items: center;"></i></span>
                                            </div>
                                            @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Modal Edit Pegawai -->
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
                                                        <div class="form-group">
                                                            <label for="id_barang">Kode Barang</label>
                                                            <select class="form-select" name="id_barang" id="id_barang" required>
                                                                @foreach($id_barang_edit as $key => $br)
                                                                <option value="{{$br->id_barang }}" @if($br->id_barang ==
                                                                    old('id_barang', $br->id_barang) )selected @endif>
                                                                    {{$br->kode_barang}}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                            @error('id_barang')
                                                             <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <label for="kondisi_barang">Nama Barang</label>
                                                            <select class="form-select" name="kondisi_barang" id="kondisi_barang" readonly>
                    
                                                            </select>
                                                            @error('kondisi_barang')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                    
                                                            <label for="id_ruangan">Ruangan</label>
                                                            <select class="form-select" name="id_ruangan" id="id_ruangan" readonly>
                    
                                                            </select>
                                                        </div>
                                                        @error('id_ruangan')
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
                            <label for="kondisi_barang">Nama Barang</label>
                            <select class="form-select" name="kondisi_barang" id="kondisi_barang" readonly>

                            </select>
                            @error('kondisi_barang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">

                            <label for="id_ruangan">Ruangan</label>
                            <select class="form-select" name="id_ruangan" id="id_ruangan" readonly>

                            </select>
                        </div>
                        @error('id_ruangan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                        
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
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button class="btn btn-danger" type="button" onclick="history.go();" title="Prev">Batal</button>
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


document.querySelectorAll('select[name=id_barang]').forEach(select => select.addEventListener('click', function() {
    const selectedIdBarang = this.value;
    const kondisiSelect = this.closest('.form-group').nextElementSibling.querySelector('select[name=kondisi_barang]');
    const idRuanganSelect = this.closest('.form-group').nextElementSibling.nextElementSibling.querySelector(
        'select[name=id_ruangan]');
    let selectedIdRuangan; // Variabel untuk menyimpan nilai id_ruangan

    // Fetch id_ruangan options for the selected id_barang
    fetch(`/fetch-id-barang/${selectedIdBarang}`)
        .then(response => response.json())
        .then(data => {
            // Clear existing options
            idRuanganSelect.innerHTML = '';

            // Populate options based on the received data
            data.forEach(option => {
                const newOption = document.createElement('option');
                newOption.value = option.ruangan.id_ruangan;
                newOption.text = option.ruangan.nama_ruangan;
                idRuanganSelect.add(newOption);
            });

            // Show or hide the id_ruangan select based on whether options are available
            idRuanganSelect.style.display = data.length > 0 ? 'block' : 'none';
            idRuanganSelect.setAttribute('required', data.length > 0 ? 'true' : 'false');

          
        })

        .then(() => {
            // Fetch kondisi barang for the selected id_ruangan and id_barang
            fetch(`/fetch-nama-barang/${selectedIdBarang}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    // Clear existing options
                    kondisiSelect.innerHTML = '';

                    // Populate options based on the received data
                    data.forEach(option => {
                        const newOption = document.createElement('option');
                        newOption.value = option.barang.id_barang;
                        newOption.text = option.barang.nama_barang;
                        kondisiSelect.add(newOption);
                    });

                    // Show or hide the kondisi_barang select based on whether options are available
                    kondisiSelect.style.display = data.length > 0 ? 'block' : 'none';
                    kondisiSelect.setAttribute('required', data.length > 0 ? 'true' : 'false');
                })
                .catch(error => console.error('Error fetching kondisi_barang options:', error));
        })
        .catch(error => console.error('Error fetching id_ruangan options:', error));
}));

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
            window.location.href = `/peminjaman/qrcode/${idPeminjaman}`; // Navigate to the URL with the id_ruangan
        } else {
            // If the user chooses "Tanpa Barcode", display the add modal
            showAddModal();
            $('#addModal').modal('hide')
        }
        // Hide the addModal (if applicable)
        $('#addModal').modal('hide');
    });
}

function showAddModal() {
    Swal.close();

    $('#addModal').modal('show');
    
}

function addData() {

    $('#addModal').modal('hide');
}
</script>

@endpush