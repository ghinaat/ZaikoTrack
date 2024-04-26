@extends('layouts.demo')
@section('title', 'List Inventaris')
@section('css')
<link rel="stylesheet" href="{{asset('fontawesome-free-6.4.2-web\css\all.min.css')}}">
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
                    <div class="table-responsive ">
                        <table id="myTable" class="table table-bordered table-striped align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Ruangan</th>
                                    <th>List Barang</th>
                                    @can('isTeknisi')
                                    <th>Opsi</th>
                                    @endcan
                                    @can('isKabeng')
                                    <th>Opsi</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inventaris as $key => $inventaris)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$inventaris->nama_ruangan}}</td>
                                    <td>
                                        <a href="{{ route('inventaris.showDetail', $inventaris->id_ruangan) }}"
                                            class="btn btn-info btn-xs mx-1">
                                            <i class="fa fa-rectangle-list"></i>
                                        </a>

                                    </td>
                                    @can("isTeknisi")
                                    <td>
                                        <a href="{{ route('inventaris.destroyRuangan', $inventaris->id_ruangan) }}"
                                            onclick="notificationBeforeDelete(event, this, {{$key+1}})"
                                            class="btn btn-danger btn-xs mx-1">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                    @endcan
                                    @can("isKabeng")
                                    <td>
                                        <a href="{{ route('inventaris.destroyRuangan', $inventaris->id_ruangan) }}"
                                            onclick="notificationBeforeDelete(event, this, {{$key+1}})"
                                            class="btn btn-danger btn-xs mx-1">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                    @endcan
                                </tr>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach
    </tbody>
    </table>
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
</script>

@endpush