@extends('layouts.demo')
@section('title', 'List Pemakaian')
@section('css')
<link rel="stylesheet" href="{{ asset('css/pemakaian.css') }}">

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
                        <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#addModal">Tambah</button>
                    </div>
                    <div class="table-responsive ">
                        <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah Barang</th>
                                    <th>Nama Pemakai</th>
                                    <th>Kelas</th>
                                    <th>Jurusan</th>
                                    <th>Tanggal Pemakaian</th>
                                    <th>Keterangan</th>
                                    <th style="width:189px;">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pemakaian as $key => $pakai)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$pakai->inventaris->barang->nama_barang}}</td>
                                    <td>{{$pakai->jumlah_barang}}</td>
                                    <td>{{$pakai->nama_lengkap}}</td>
                                    <td>{{$pakai->kelas}}</td>
                                    <td>{{$pakai->jurusan}}</td>
                                    <td>{{$pakai->tgl_pakai}}</td>
                                    <td>{{$pakai->keterangan_pemakaian}}</td>
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


<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Tambah Pemakaian</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class="content">
                <div class="content__inner">
                    <h1 class="content__title mt-4">Form Pemakaian</h1>
                    <div class="container overflow-hidden">
                        <div class="multisteps-form">
                            <div class="row">
                                <div class="col-12 col-lg-8 mx-auto mb-4">                            
                                    <div class="multisteps-form__progress">
                                        <button class="multisteps-form__progress-btn first" type="button" title="Address">Data Pribadi</button>
                                        <button class="multisteps-form__progress-btn" type="button" title="Order Info">Detail Pemakaian</button>
                                    </div>
                                </div>
                            </div>
                        <div class="row">
                            <div class="m-auto">
                            <div class="multisteps-form__form">
                                <!--single form panel-->
                                    {{-- <form id="addForm" action="{{route('pemakaian.store')}}" method="post"> --}}
                                        @csrf
                                    <div class="multisteps-form__panel first" data-animation="scaleIn">
                                        <h4 class="multisteps-form__title">Data Siswa</h4>
                                        <div class="multisteps-form__content">
                                            <div class="form-row mt-2">
                                                <div class="form-group">
                                                    <label for="nama_lengkap" class="mb-0">Nama Pemakai</label>
                                                    <input name="nama_lengkap" id="nama_lengkap" class="multisteps-form__input form-control" type="text" placeholder="Contoh : Bryan Domani" >            
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label for="kelas" class="mb-0">Kelas</label>
                                                            <input class="multisteps-form__input form-control" type="text" name="kelas" id="kelas" placeholder="Contoh : 12">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label for="jurusan" class="mb-0">Jurusan</label>
                                                            <input class="multisteps-form__input form-control" type="text" name="jurusan" id="jurusan" placeholder="Contoh : SIJA">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-2" style="text-align: right;">
                                                <button class="btn btn-primary js-btn-next " type="button" title="Next">Selanjutnya</button>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- </form> --}}
                                    <div class="multisteps-form__panel" data-animation="scaleIn" id="panel_order_list">
                                        <h4 class="multisteps-form__title">Info Barang</h4>
                                        <div class="multisteps-form__content">
                                            <div class="form-row mt-2">
                                                <button class="btn btn-primary js-btn-plus" >Tambah</button>
                                                <div class="table-responsive ">
                                                    <table id="cartTable" class="table table-bordered table-striped align-items-center mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Nama Barang</th>
                                                                {{-- <th>Ruangan</th> --}}
                                                                <th>Jumlah Barang</th>
                                                                <th>Keterangan</th>
                                                                <th style="width:189px;">Opsi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {{-- @foreach($cart as $key => $cr)
                                                            <tr>
                                                                <td>{{$key+1}}</td>
                                                                <td>{{$cr->inventaris->barang->nama_barang}}</td>
                                                                <td>{{$cr->inventaris->ruangan->nama_ruangan}}</td>
                                                                <td>{{$cr->jumlah_barang}}</td>
                                                                <td>{{$cr->keterangan_pemakaian}}</td>
                                                            </tr>
                                                            @endforeach --}}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="form-group mt-2" style="text-align: right;">
                                                <button class="btn btn-secondary js-btn-prev" type="button" title="Prev">Kembali</button>
                                                <button class="btn btn-success js-btn-next " type="button" title="Next">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!--single form panel-->
                                    <form id="addFormCart" action="{{route('cart.store')}}" method="post">
                                        @csrf
                                        <div class="multisteps-form__panel" data-animation="scaleIn" id="panel_tambah">
                                            <h4 class="multisteps-form__title">Pilih Barang</h4>
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
                                                                <select class="form-select" name="id_ruangan" id="id_ruangan">
                                                                    
                                                                </select>                                
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="jumlah_barang">Jumlah Barang</label>
                                                                <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="keterangan_pemakaian">Keterangan Pemakaian</label>
                                                        <textarea rows="3" name="keterangan_pemakaian" id="keterangan_pemakaian" class="form-control" ></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-2" style="text-align: right;">
                                                    <button class="btn btn-secondary js-btn-prev" type="button" title="Prev">Batal</button>
                                                    <button class="btn btn-primary js-btn-choose" type="click" title="Next">Pilih</button>
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

{{-- <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Tambah barang</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div> --}}



<!--PEN HEADER-->
<header class="header">
    <h1 class="header__title">Multi Step Form with animations</h1>
    <div class="header__btns btns"><a class="header__btn btn" href="https://github.com/nat-davydova/multisteps-form" title="Check on Github" target="_blank">Check on Github</a></div>
  </header>
  <!--PEN CONTENT     -->
  <div class="content">
    <!--content inner-->
    <div class="content__inner">
      <div class="container">
        <!--content title-->
        <h2 class="content__title content__title--m-sm">Pick animation type</h2>
        <!--animations form-->
        <form class="pick-animation my-4">
          <div class="form-row">
            <div class="col-5 m-auto">
              <select class="pick-animation__select form-control">
                <option value="scaleIn" selected="selected">ScaleIn</option>
                <option value="scaleOut">ScaleOut</option>
                <option value="slideHorz">SlideHorz</option>
                <option value="slideVert">SlideVert</option>
                <option value="fadeIn">FadeIn</option>
              </select>
            </div>
          </div>
        </form>
        <!--content title-->
        <h2 class="content__title">Click on steps or 'Prev' and 'Next' buttons</h2>
      </div>
      <div class="container overflow-hidden">
        <!--multisteps-form-->
        <div class="multisteps-form">
          <!--progress bar-->
          <div class="row">
            <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
              <div class="multisteps-form__progress">
                <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">User Info</button>
                <button class="multisteps-form__progress-btn" type="button" title="Address">Address</button>
                <button class="multisteps-form__progress-btn" type="button" title="Order Info">Order Info</button>
                <button class="multisteps-form__progress-btn" type="button" title="Comments">Comments        </button>
              </div>
            </div>
          </div>
          <!--form panels-->
          <div class="row">
            <div class="col-12 col-lg-8 m-auto">
              <form class="multisteps-form__form">
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="scaleIn">
                  <h3 class="multisteps-form__title">Your User Info</h3>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <div class="col-12 col-sm-6">
                        <input class="multisteps-form__input form-control" type="text" placeholder="First Name"/>
                      </div>
                      <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                        <input class="multisteps-form__input form-control" type="text" placeholder="Last Name"/>
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col-12 col-sm-6">
                        <input class="multisteps-form__input form-control" type="text" placeholder="Login"/>
                      </div>
                      <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                        <input class="multisteps-form__input form-control" type="email" placeholder="Email"/>
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col-12 col-sm-6">
                        <input class="multisteps-form__input form-control" type="password" placeholder="Password"/>
                      </div>
                      <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                        <input class="multisteps-form__input form-control" type="password" placeholder="Repeat Password"/>
                      </div>
                    </div>
                    <div class="button-row d-flex mt-4">
                      <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                    </div>
                  </div>
                </div>
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                  <h3 class="multisteps-form__title">Your Address</h3>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <div class="col">
                        <input class="multisteps-form__input form-control" type="text" placeholder="Address 1"/>
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col">
                        <input class="multisteps-form__input form-control" type="text" placeholder="Address 2"/>
                      </div>
                    </div>
                    <div class="form-row mt-4">
                      <div class="col-12 col-sm-6">
                        <input class="multisteps-form__input form-control" type="text" placeholder="City"/>
                      </div>
                      <div class="col-6 col-sm-3 mt-4 mt-sm-0">
                        <select class="multisteps-form__select form-control">
                          <option selected="selected">State...</option>
                          <option>...</option>
                        </select>
                      </div>
                      <div class="col-6 col-sm-3 mt-4 mt-sm-0">
                        <input class="multisteps-form__input form-control" type="text" placeholder="Zip"/>
                      </div>
                    </div>
                    <div class="button-row d-flex mt-4">
                      <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                      <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                    </div>
                  </div>
                </div>
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                  <h3 class="multisteps-form__title">Your Order Info</h3>
                  <div class="multisteps-form__content">
                    <div class="row">
                      <div class="col-12 col-md-6 mt-4">
                        <div class="card shadow-sm">
                          <div class="card-body">
                            <h5 class="card-title">Item Title</h5>
                            <p class="card-text">Small and nice item description</p><a class="btn btn-primary" href="#" title="Item Link">Item Link</a>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-md-6 mt-4">
                        <div class="card shadow-sm">
                          <div class="card-body">
                            <h5 class="card-title">Item Title</h5>
                            <p class="card-text">Small and nice item description</p><a class="btn btn-primary" href="#" title="Item Link">Item Link</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="button-row d-flex mt-4 col-12">
                        <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                        <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!--single form panel-->
                <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                  <h3 class="multisteps-form__title">Additional Comments</h3>
                  <div class="multisteps-form__content">
                    <div class="form-row mt-4">
                      <textarea class="multisteps-form__textarea form-control" placeholder="Additional Comments and Requirements"></textarea>
                    </div>
                    <div class="button-row d-flex mt-4">
                      <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                      <button class="btn btn-success ml-auto" type="button" title="Send">Send</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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