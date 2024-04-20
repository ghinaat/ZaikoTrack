@extends('layouts.demo')
@section('title', 'Notifikasi')
@section('css')
   <style>

        .main-profile .col-md-3 img{
            width: 100%;
        }

    </style>
@endsection
@section('breadcrumb-name')
Notifikasi / Detail Notifikasi
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <h1 class="mt-5">{{ $notifikasi->judul }}</h1>
                    {!! $notifikasi->pesan !!}
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <!-- Tombol Lihat Link -->
                            <a href="{{ $notifikasi->link }}" class="btn btn-primary w-50">Lihat Link</a>&nbsp;
                            <!-- Tombol Back -->
                            <a href="{{ route('notifikasi.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
