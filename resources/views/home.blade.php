@extends('layouts.demo')
@section('title', 'Home')
@section('css')
 <link rel="stylesheet" href="{{asset('css\home.css')}}"> 
 {{-- <link rel="stylesheet" href="{{asset('css\card.css')}}">  --}}
<style>
    .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px; /* Sesuaikan dengan lebar maksimum yang diinginkan */
    }
    .border-divider {
    border-top: 0.3vh solid #e5e5e5;
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-body p {
        overflow-wrap: break-word;
    }
    
    @media (min-width: 768px) {
    .welcome-card {
        padding-left: 40px;
        padding-right: 20px;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    .container-fluid {
        padding-top: 20px;
        padding-bottom: 20px;
        padding-left: 20px;
        padding-right: 20px;
    }
    .welcome-card p{
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .welcome-card {
        padding-left: 30px;
        padding-right: 30px;
        padding-top: 30px;
        padding-bottom: 10px;
    }
    .container-fluid {
        padding-bottom: 10px;
        padding-left: 10px;
        padding-right: 10px;
    }
    .welcome-img{
        display: none;
    }
    .welcome-card p{
        font-size: 0.8rem;
    }
    /* .welcome-card btn-sm{
        padding-x:
    } */
}

</style>
@endsection
@section('breadcrumb-name')
Home
@endsection
@section('content')
@can('isTeknisi', 'isKaprog', 'isKabeng')
<div class="container-fluid pt-4 pb-2">
    <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 mb-4 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8" style="padding-right: 0px">
                                <div class="numbers">
                                    <h5 class="font-weight-bolder text-primary mt-2" style="font-size: 26px">
                                    {{$inventaris}}
                                    </h5>
                                    <p class="text-xs mb-0 text-uppercase font-weight-bold">Terinventarisasi</p>

                                </div>
                            </div>
                            <div class="col-4 d-flex d-flex align-items-center justify-content-end" style="padding-left: 0; padding-right; 15px;">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="ni ni-box-2 text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <h5 class="font-weight-bolder text-danger mt-2" style="font-size: 26px">
                                        {{$peminjaman}}
                                    </h5>
                                    <p class="text-xs mb-0 text-uppercase font-weight-bold">Dipinjam</p>

                                </div>
                            </div>
                            <div class="col-4 d-flex d-flex align-items-center justify-content-end" style="padding-left: 0; padding-right; 15px;">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="fa-solid fa-people-carry-box text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <h5 class="font-weight-bolder text-success mt-2" style="font-size: 26px">
                                        {{$barangRusak}}
                                    </h5>
                                    <p class="text-xs mb-0 text-uppercase font-weight-bold">Kondisi Rusak</p>

                                </div>
                            </div>
                            <div class="col-4 d-flex d-flex align-items-center justify-content-end" style="padding-left: 0; padding-right; 15px;">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="fa-solid fa-screwdriver-wrench text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <h5 class="font-weight-bolder text-warning mt-2" style="font-size: 26px">
                                        {{$users}}
                                    </h5>
                                    <p class="text-xs mb-0 text-uppercase font-weight-bold">Pengguna</p>

                                </div>
                            </div>
                            <div class="col-4 d-flex d-flex align-items-center justify-content-end" style="padding-left: 0; padding-right; 15px;">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i class="fa-solid fa-users text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card" >
                <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-3"></i> &nbsp;Jadwal Pengembalian</h6>
                        </div>
                </div>
                @if ($jadwals->isEmpty())    
                <div class="card-body pt-4 p-3">
                    <div class="container" style="height:100px;" >
                    <ul class="list-group">
                      <li class="list-group-item border-0 p-3 bg-gray-100 border-radius-lg">
                        <div class="d-flex flex-column">
                          <h6 class="mb-3 text-sm"></h6>
                          <p class="text-center">No data available</p>                   
                        </div>
                      </li>
                    </ul>
                  </div>
                @else
                <div class="table-responsive">
                    <table class="table align-items-center ">
                        <tbody>
                            @foreach($jadwals as $peminjaman => $detailPeminjamans)
                                @php
                                    $tglKembali = \Carbon\Carbon::parse($detailPeminjamans->first()->peminjaman->tgl_kembali);
                                    $tglSekarang = \Carbon\Carbon::now();
                                    $melebihiBatas = $tglKembali->isPast();
                                @endphp
                                <tr>
                                    <td class="w-25">
                                        <div class="d-flex align-items-center">
                                            {{-- <div>
                                                <img src="../assets/img/icons/flags/US.png" alt="Country flag">
                                            </div> --}}
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">Nama:</p>
                                                <h6 class="text-sm mb-0 truncate" style="max-width: 120px;"> <!-- Menerapkan gaya langsung -->
                                                    @if ($detailPeminjamans->first()->peminjaman->status === 'guru')
                                                        {{ $detailPeminjamans->first()->peminjaman->guru->nama_guru }}
                                                    @elseif ($detailPeminjamans->first()->peminjaman->status === 'karyawan')
                                                        {{ $detailPeminjamans->first()->peminjaman->karyawan->nama_karyawan }}
                                                    @else
                                                        {{ $detailPeminjamans->first()->peminjaman->users->name }}
                                                    @endif
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Jumlah:</p>
                                            <h6 class="text-sm mb-0">{{ $detailPeminjamans->count() }}</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Dipinjam:</p>
                                            <h6 class="text-sm mb-0">{{\Carbon\Carbon::parse($detailPeminjamans->first()->peminjaman->tgl_pinjam)->format('d M Y')}}</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <div class="col text-center">
                                            <p class="text-xs font-weight-bold mb-0">Dikembalikan:</p>
                                            <h6 class="text-sm mb-0">{{\Carbon\Carbon::parse($detailPeminjamans->first()->peminjaman->tgl_kembali)->format('d M Y')}}</h6>
                                        </div>
                        
                                    </td>
                                    <td>  
                                        <a href="{{ route('peminjaman.showDetail', $detailPeminjamans->first()->peminjaman->id_peminjaman) }}" class="btn btn-link btn-icon-only btn-rounded btn-md text-dark icon-move-right my-auto">
                                            
                                            <i class="ni ni-bold-right" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Kategori </h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                    <i class="fa-solid fa-desktop text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">Alat Praktik</h6>
                                    <span class="text-xs">{{$alatPraktik}} Barang</span>
                                </div>
                            </div>
                            <div class="d-flex">
                                <a href="{{ route('barang.index') }}" class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto">
                                    <i class="ni ni-bold-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                    <i class="fa-solid fa-box-open text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">Bahan Praktik</h6>
                                    <span class="text-xs">{{$bahanPraktik}} stok</span>
                                </div>
                            </div>
                            <div class="d-flex">
                                <button
                                    class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></button>
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                    <i class="fa-solid fa-book text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">Perlengkapan</h6>
                                    <span class="text-xs">{{$perlengkapan}} Barang</span>
                                </div>
                            </div>
                            <div class="d-flex">
                                <button
                                    class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer pt-3">
        <div class="container-fluid pb-0">
            <div class=" align-items-start">
                    <div class="copyright text-start text-sm text-muted ">
                        ©2024 SIJA STUDENT
                    </div>
            </div>
        </div>
    </footer>

</div>
@endcan


@can ('isSiswa')
<div class="container-fluid ">
    <div class="card">
        <div class="row align-items-center">
                <div class="col-12 col-md-8 welcome-card">
                    <h3>Welcome {{ implode(' ', array_slice(explode(' ', $user->name), 0, 2)) }}!</h3>

                    <p style="text-sm">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Hic dolorem autem ab at rerum eumsit? Delectus enim animi id aliquam porro!</p>
                    <a href="{{ route('barang.index') }}" class="btn bg-gradient-primary btn-sm text-sm">Lihat Barang</a>
                </div>
                <div class="col-md-4 mt-1 p-1 welcome-img">
                    <img src="{{ asset('/img/humans.png') }}" alt="animasi" style="width: 270px;">
                </div>
        </div>
    </div>
</div>



<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">


<div class="container-fluid py-1">
    <div class="row">
        <div class="col-6 col-md-6">
            <div class="card wallet">
                <div class="overlay"></div>
                <div class="circle">
                    {{-- <img src="img/icons8-hand-box-96.png"> --}}
                    <svg width="78px" height="78px" viewBox="0 0 78 78" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <!-- Isi SVG Lingkaran di sini -->
                        <rect fill="none" x="0" y="0" width="78" height="78" rx="50%"></rect>
                        <image xlink:href="{{asset("img/icons8-hand-box-96.png")}}" x="13" y="13" width="52" height="52" />
                    </svg>
                </div>
                <h5 class="mt-3 mb-0">Peminjaman</h5>
                <a href="{{route('peminjaman.barcode')}}" class=" btn btn-link btn-sm mb-0">Klik Disini</a>
            </div>
        </div>
        
        <div class="col-6 col-md-6">
            <div class="card human-resources">
            <div class="overlay"></div>
            <div class="circle">
                <svg width="78px" height="78px" viewBox="0 0 78 78" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <!-- Isi SVG Lingkaran di sini -->
                    <rect fill="none" x="0" y="0" width="78" height="78" rx="50%"></rect>
                    <image xlink:href="{{asset("img/icons8-hand-truck-96 (1).png")}}" x="13" y="13" width="52" height="52" />
                </svg>

            </div>
            <h5 class="mt-3 mb-0">Pemakaian</h5>
            <a href="{{route('pemakaian.create')}}" class=" btn btn-link btn-sm mb-0">Klik Disini</a>
            </div>
        </div>
    </div>

</div>
<div class="container-fluid py-4">
    <div class="riwayat-pinjam">
        <div class="header mb-2"> 
            <h6>Riwayat Peminjaman</h6>
        </div>
        
        <div class="body mt-2">
            @if($jadwals->isNotEmpty())
            @foreach ($jadwals as $jadwal)
                @foreach ($jadwal->detailPeminjaman as $detailPeminjaman)
                    @foreach ($detailPeminjaman->inventarisis as $inventaris)
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0 px-2">
                                <tbody>
                                    <tr>
                                        <td class="w-25">
                                            <div class="d-flex align-items-center" style="margin-left: 20px;">
                                                @if($detailPeminjaman->status == 'dipinjam')
                                                <div class="icon-history text-lg justify-content-center" style="background-color: #2dce89">
                                                    <i class="fa-regular fa-clock" style="color: #ffffff"></i>
                                                </div>      
                                                @elseif($detailPeminjaman->status == 'sudah_dikembalikan')
                                                <div class="icon-history text-lg justify-content-center" style="background-color: #5e72e4;">
                                                    <i class="fa-solid fa-circle-check" style="color: #ffffff"></i>
                                                </div>    
                                                @elseif($detailPeminjaman->status == 'dipinjam' && strtotime($jadwal->tgl_pinjam) > strtotime($jadwal->tgl_kembali))
                                                <div class="icon-history text-lg justify-content-center" style="background-color: #f5365c">
                                                    <i class="fa-solid fa-triangle-exclamation" style="color: #ffffff"></i>
                                                </div>    
                                                @endif                                      
                                                <div class="ms-4">
                                                    <p class="text-sm mb-0"> <!-- Menerapkan gaya langsung -->
                                                        {{$inventaris->barang->nama_barang}}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <p class="text-sm mb-0">{{$inventaris->barang->kode_barang}}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <p class="text-sm mb-0">{{\Carbon\Carbon::parse($jadwal->tgl_pinjam)->format('d M Y')}}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <p class="text-sm mb-0">{{\Carbon\Carbon::parse($jadwal->tgl_kembali)->format('d M Y')}}</p>
                                            </div>
                                        </td>
                                        <td>  
                                            <a href="{{ route('peminjaman.showDetail', $detailPeminjaman->id_detail_peminjaman)}}" class="btn btn-link btn-icon-only btn-rounded btn-md text-dark icon-move-right my-auto">
                                                
                                                <i class="ni ni-bold-right" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                @endforeach
            @endforeach
            @else
            <div class="card">
                {{-- <div class="container" style="height:100px;" > --}}
                {{-- <ul class="list-group"> --}}
                  <li class="list-group-item border-0 p-3 bg-gray-200 border-radius-lg">
                      <p class="text-center text-sm mb-0">Tidak ada barang yang dipinjam.</p>                   
                  </li>
                {{-- </ul> --}}
              </div>
            @endif
        </div>
    </div>
</div>
@endcan
{{-- 
<div class="container">
    <div class="row">
        <div class="col-6 col-md-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="imgBx">
                                <img src="img/icons8-hand-box-96.png">
                            </div>
                            <div class="contentBx">
                                <h2>Peminjaman</h2>

                                <a href="{{route('peminjaman.create')}}">Klik Disini</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
                <div class="card" style=" margin-left: 16px">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="imgBx">
                                    <img src="img/icons8-hand-truck-96 (1).png">
                                </div>
                                <div class="contentBx">
                                    <h2>Pemakaian</h2>

                                    <a href="{{route('pemakaian.index')}}">Klik Disini</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div> --}}


<!-- <div class="container-fluid py-4"> -->


@endsection