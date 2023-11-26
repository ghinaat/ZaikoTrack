@extends('layouts.demo')
@section('title', 'List Inventaris')
@section('css')
<style>
/* Hide all steps by default: */
.tab {
    display: none;
}

button {
    background-color: #04AA6D;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    font-size: 17px;
    font-family: Raleway;
    cursor: pointer;
}

button:hover {
    opacity: 0.8;
}

#prevBtn {
    background-color: #bbbbbb;
}   

/* Make circles that indicate the steps of the form: */
.step {
    height: 15px;
    width: 15px;
    margin: 0 2px;
    background-color: #bbbbbb;
    border: none;
    border-radius: 50%;
    display: inline-block;
    opacity: 0.5;
}

.step.active {
    opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
    background-color: #04AA6D;
}
</style>
@endsection
@section('breadcrumb-name')
Inventaris
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h4 class="m-0 text-dark">List Inventaris</h4>
                </div>
                <div class="card-body m-0">
                    <div class="mb-2">
                        <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#addModal">Tambah</button>
                    </div>
                    <div class="table-responsive ">
                        <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                            <!-- <thead> -->
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Ruangan</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah Barang</th>
                                    <th>Kondisi</th>
                                    <th style="width:130px;">Keterangan</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inventaris as $key => $inventaris)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$inventaris->ruangan->nama_ruangan}}</td>
                                    <td>{{$inventaris->barang->nama_barang}}</td>
                                    <td>{{$inventaris->jumlah_barang}}</td>
                                    <td>
                                        @if($inventaris->kondisi_barang == 'rusak')
                                        Rusak
                                        @elseif($inventaris->kondisi_barang == 'tidak_lengkap')
                                        Tidak Lengkap
                                        @else
                                        Lengkap
                                        @endif
                                    </td>
                                    <td>{{$inventaris->ket_barang}}</td>
                                    <td>
                                        @include('components.action-buttons', ['id' => $inventaris->id_inventaris, 'key'
                                        =>
                                        $key,
                                        'route' => 'inventaris'])
                                    </td>
                                </tr>
                                <!-- Modal Edit Pegawai -->
                                <div class="modal fade" id="editModal{{$inventaris->id_inventaris}}" tabindex="-1"
                                    role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Pegawai</h5>
                                                <button type="button" class="btn-close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <i class="fa fa-close" style="color: black;"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form
                                                    action="{{ route('inventaris.update', $inventaris->id_inventaris)}}"
                                                    method="post">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="jenis_barang">Jenis Barang</label>
                                                        <select class="form-select" name="id_jenis_barang"
                                                            id="id_jenis_barang" required>
                                                            @foreach($ruangan as $key => $jb)
                                                            <option value="{{$jb->id_jenis_barang}}" @if(
                                                                old('id_jenis_barang')==$jb->
                                                                id_jenis_barang)selected @endif>
                                                                {{$jb->nama_jenis_barang}}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="nama_inventaris">Nama inventaris</label>
                                                        <input type="text" name="nama_inventaris" id="nama_inventaris"
                                                            class="form-control"
                                                            value="{{ old('nama_inventaris', $inventaris->nama_inventaris) }}"
                                                            required>
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
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Inventaris</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class=" modal-body">
                <form id="addForm" action="{{ route('inventaris.store') }}" method="post">
                    @csrf
                    <div class="tab">
                        <div class="form-step" data-step="1">
                            <div class="form-group">
                                <label for="id_ruangan">Ruangan</label>
                                <select class="form-select" name="id_ruangan" id="id_ruangan" required>
                                    @foreach($ruangan as $key => $ruangan)
                                    <option value="{{$ruangan->id_ruangan}}" @if( old('id_ruangan')==$ruangan->
                                        id_ruangan)selected @endif>
                                        {{$ruangan->nama_ruangan}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Include other fields for Step 1 here -->
                        </div>
                    </div>
                    <div class="tab">
                        <div class="form-step" data-step="2">
                            <div class="form-group">
                                <label for="id_barang">Nama Barang</label>
                                <select class="form-select" name="id_barang" id="id_barang" required>
                                    @foreach($barang as $key => $br)
                                    <option value="{{$br->id_barang}}" @if( old('id_barang')==$br->
                                        id_barang)selected @endif>
                                        {{$br->nama_barang}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-step" data-step="3">
                        <div class="form-group">
                            <!-- Step 3 fields -->
                        </div>
                    </div>

                    <div style="overflow:auto;">
                        <div style="float:right;">
                            <button type="button" id="prevBtn" onclick="nextPrev(-1)"
                                class="btn btn-secondary">Previous</button>
                            <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                        </div>
                    </div>
                    <!-- Circles which indicates the steps of the form: -->
                    <div style="text-align:center;margin-top:40px;">
                        <span class="step"></span>
                        <span class="step"></span>
                        <span class="step"></span>
                        <span class="step"></span>
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

var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
    // This function will display the specified tab of the form...
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";
    //... and fix the Previous/Next buttons:
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Submit";
    } else {
        document.getElementById("nextBtn").innerHTML = "Next";
    }
    //... and run a function that will display the correct step indicator:
    fixStepIndicator(n)
}

function nextPrev(n) {
    // This function will figure out which tab to display
    var x = document.getElementsByClassName("tab");
    // Exit the function if any field in the current tab is invalid:
    if (n == 1 && !validateForm()) return false;
    // Hide the current tab:
    x[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;
    // if you have reached the end of the form...
    if (currentTab >= x.length) {
        // ... the form gets submitted:
        document.getElementById("regForm").submit();
        return false;
    }
    // Otherwise, display the correct tab:
    showTab(currentTab);
}

function validateForm() {
    // This function deals with validation of the form fields
    var x, y, i, valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTab].getElementsByTagName("input");
    // A loop that checks every input field in the current tab:
    for (i = 0; i < y.length; i++) {
        // If a field is empty...
        if (y[i].value == "") {
            // add an "invalid" class to the field:
            y[i].className += " invalid";
            // and set the current valid status to false
            valid = false;
        }
    }
    // // If the valid status is true, mark the step as finished and valid:
    // if (valid) {
    //     document.getElementsByClassName("step")[currentTab].className += " finish";
    // }
    return valid; // return the valid status
}

function fixStepIndicator(n) {
    // This function removes the "active" class of all steps...
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }
    //... and adds the "active" class on the current step:
    x[n].className += " active";
}
</script>

</body>

</html>
</script>
@endpush