@extends('layouts.demo')
@section('title', 'Tambah Pemakaian')
@section('css')
<link rel="stylesheet" href="{{ asset('css/pemakaian.css') }}">

@endsection
@section('breadcrumb-name')
Tambah Pemakaian
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-body m-0">
                    <div class="content">
                        <div class="content__inner">
                            <h6 class="content__title mt-3">Form Pemakaian</h6>
                            <div class="container overflow-hidden">
                                <div class="multisteps-form">
                                    <div class="row">
                                        <div class="col-12 col-lg-8 mx-auto mb-4 mt-1">                            
                                            <div class="multisteps-form__progress">
                                                <button class="multisteps-form__progress-btn first" type="button" title="Address">Alat & Bahan</button>
                                                <button class="multisteps-form__progress-btn" type="button" title="Order Info">Detail Pemakai</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 m-auto">
                                        <div class="multisteps-form__form">
                                            <!--single form panel-->

                                                {{-- </form> --}}
                                                <div class="multisteps-form__panel" data-animation="scaleIn" id="panel_order_list">
                                                    <h1 class="multisteps-form__subtitle">Alat dan Bahan</h1>
                                                    <div class="multisteps-form__content">
                                                        <div class="form-row mt-2">
                                                            <button class="btn btn-primary js-btn-plus" >Tambah</button>
                                                            <div class="table-responsive mt-2">
                                                                <table id="cartTable" class="table table-bordered table-striped align-items-center mb-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>
                                                                            <th>Nama Barang</th>
                                                                            {{-- <th>Ruangan</th> --}}
                                                                            <th>Jumlah Barang</th>
                                                                            <th style="width:189px;">Opsi</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($cart as $key => $cr)
                                                                        <tr>
                                                                            <td>{{$key+1}}</td>
                                                                            <td>{{$cr->inventaris->barang->nama_barang}}</td>
                                                                            {{-- <td>{{$cr->inventaris->ruangan->nama_ruangan}}</td> --}}
                                                                            <td>{{$cr->jumlah_barang}}</td>
                                                                            <td>
                                                                                <a href="{{ route('cart.destroy', $cr->id_cart) }}" onclick="notificationBeforeDelete(event, this, {{$key+1}})"
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
                                                        <div class="form-group mt-4" style="text-align: right;">
                                                            <a href="{{ route('cart.batal') }}" onclick="deleteCart(event, this)" class="btn btn-danger" type="button" title="cancel">Batal</a>
                                                            <button class="btn btn-primary js-btn-next " type="button" title="Next">Selanjutnya</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <form id="addForm" action="{{route('pemakaian.store')}}" method="post">
                                                        @csrf
                                                    <div class="multisteps-form__panel  js-active first" data-animation="scaleIn">
                                                        <h4 class="multisteps-form__title">Data Siswa</h4>
                                                        <div class="multisteps-form__content">
                                                            <div class="form-row mt-2">
                                                                <div class="form-group">
                                                                    <label for="nama_lengkap" class="mb-0">Nama Pemakai</label>
                                                                    <input name="nama_lengkap" id="nama_lengkap" class="multisteps-form__input form-control" type="text" required>            
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="kelas" class="mb-0">Kelas</label>
                                                                            <input class="multisteps-form__input form-control" type="text" name="kelas" id="kelas" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="jurusan" class="mb-0">Jurusan</label>
                                                                            <input class="multisteps-form__input form-control" type="text" name="jurusan" id="jurusan" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="keterangan_pemakaian">Keterangan Pemakaian</label>
                                                                    <textarea rows="3" name="keterangan_pemakaian" id="keterangan_pemakaian" class="form-control"  ></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group mt-2" style="text-align: right;">
                                                            <button class="btn btn-secondary js-btn-prev" type="button" title="Prev">Kembali</button>
                                                            <button class="btn btn-primary" type="submit" title="Next">Simpan</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                    <form id="addFormCart" action="{{route('cart.store')}}" method="post">
                                                        @csrf
                                                        <div class="multisteps-form__panel" data-animation="scaleIn" id="panel_tambah">
                                                            <div class="multisteps-form__content">
                                                                <div class="form-row mt-2"> 
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="id_barang">Nama Barang</label>
                                                                            <select class="form-select" name="id_barang" id="id_barang">
                                                                                @foreach($barang as $key => $br)
                                                                                <option value="{{$br->id_barang}}" @if( old('id_barang')==$br->id_barang)selected @endif>
                                                                                    {{$br->barang->nama_barang}}
                                                                                </option>
                                                                                @endforeach
                                                                            </select>                                
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="form-group">
                                                                                <label for="id_ruangan">Ruangan</label>
                                                                                <select class="form-select" name="id_ruangan" id="id_ruangan" required>
                                                                                    
                                                                                </select>                                
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="form-group">
                                                                                <label for="jumlah_barang">Jumlah Barang</label>
                                                                                <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                </div>
                                                                <div class="form-group mt-2" style="text-align: right;">
                                                                    <button class="btn btn-secondary js-btn-cancel" type="button" title="Prev">Batal</button>
                                                                    <button class="btn btn-primary " type="submit" title="Next">Pilih</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                
                                            {{-- </div> --}}
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
</div>
@stop
@push('js')
<script src="{{ asset('js/pemakaian.js ') }}"></script>

<form action="" id="delete-form" method="post">
    @method('delete')
    @csrf
</form>
<script> 
    $('#example2').DataTable({ 
        "responsive": true, 
    }); 
    function deleteCart(event, el, dt) {
        event.preventDefault();
        $("#delete-form").attr('action', $(el).attr('href'));
        $("#delete-form").submit();
    }
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
document.querySelectorAll('select[name=id_barang]').forEach(select => select.addEventListener('click',
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