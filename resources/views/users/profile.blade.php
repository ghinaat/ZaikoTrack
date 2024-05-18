@extends('layouts.demo')
@section('title', 'Profile')
@section('css')
<style>
    .border-divider {
        border-bottom: 0.3vh solid #e5e5e5;
    }
    </style>
@endsection
@section('breadcrumb-name')
Profile
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
         <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            @if ($profile)
                            @if ($profile->image == null)
                                <img class="profile-user-img img-fluid img-rounded"
                                    src="{{ asset( '/img/no_pp.png') }}" alt="User profile picture">
                            @else
                                <img class="profile-user-img img-fluid img-rounded"
                                    src="{{ asset('../storage/profile/' . $profile->image) }}" alt="User profile picture">
                            @endif
                        @else
                            <img class="profile-user-img img-fluid img-rounded"
                                src="{{ asset( '/img/no_pp.png') }}" alt="User profile picture">
                        @endif
                                <br><br>
                            <h3 class="profile-username text-center">{{ $user->name }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @if (Route::currentRouteName() === 'users.profile')
                @include('layouts.partials.nav-pills')
                @else
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Profile: {{$user->name ?? ''}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="card mb-4">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item border-0 d-flex justify-content-between border-radius-lg mb-2 border-divider">
                                <div class="col-md-6">
                                    <div class="d-flex flex-column">
                                        <h6 class="mt-2 text-dark fw-bold">Nama Lengkap :</h6>
                                        <div class="text-dark fw-bold mb-3">
                                            {{ $user->name ?? '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex flex-column">
                                        <h6 class="mt-2 text-dark fw-bold">Email :</h6>
                                        <div class="text-dark fw-bold mb-2">
                                            {{ $user->email ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </li>
                           @if($user->level == 'siswa')
                            <li class="list-group-item border-0 d-flex justify-content-between border-radius-lg mb-2 border-divider">
                                <div class="col-md-6">
                                    <div class="d-flex flex-column">
                                        <h6 class="mt-2 text-dark fw-bold">NIS :</h6>
                                        <div class="text-dark fw-bold mb-3">
                                            {{ $profile->nis ?? '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex flex-column">
                                        <h6 class="mt-2 text-dark fw-bold">Kelas :</h6>
                                        <div class="text-dark fw-bold mb-2">
                                            {{ $profile->kelas ?? '' }} {{ $profile->jurusan ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif
                          
                    
                          
                          </ul>
                          <div class=" text-end mt-3">
                            <button class="btn btn-primary mb-2" data-toggle="modal"
                            data-target="#editModal{{$user->id_users}}"
                            data-id="{{$user->id_users}}">Edit</button>
                        </div>
                    </div> 
            </div>
        </div>
       
    </div>
</div>
<div class="modal fade" id="editModal{{$user->id_users}}" tabindex="-1" role="dialog"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-dismiss="modal"
                    aria-label="Close">
                    <i class="fa fa-close" style="color: black;"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('profile.update', $user->id_users)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Nama User</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class=" form-group">
                        <label for="exampleInputEmail">Email</label>
                        <input type="email" name="email" id="exampleInputEmail"
                            class="form-control"
                            value="{{ old('email', $user->email) }}" required>
                    </div>

                    @can('isTeknisi')
                    @if($user->level == "siswa")
                    <div class="form-group">
                        <label for="nis">NIS</label>
                        <input type="text" name="nis" id="nis" class="form-control"
                            value="{{ old('nis', $profile->nis) }}" >
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6 kelas">
                            <div class="form-group">
                                <label for="kelas" class="mb-0">Kelas</label>
                                <input class="multisteps-form__input form-control" type="text" name="kelas" id="kelas"  value="{{ old('kelas', $profile->kelas) }}">
                            </div>
                        </div>
                        <div class="col-6 col-md-6 jurusan">
                            <div class="form-group">
                                <label for="jurusan" class="mb-0">Jurusan</label>
                                <input class="multisteps-form__input form-control" type="text" name="jurusan" id="jurusan"  value="{{ old('jurusan', $profile->jurusan) }}">
                            </div>
                        </div>
                    </div>
                    @endif
                    @endcan
                    <div class="form-group">
                        <label for="image" class="form-label">Image Profile</label>
                        <img class="img-preview img-fluid mb-1 col-sm-3 d-block">
                        <input class="form-control @error('image') is-invalid @enderror" type="file"
                            id="image" name="image" onchange="previewImageTambah()"
                            accept="image/jpeg, image/jpg, image/png">
                        <small class="form-text text-muted">Allow file extensions : .jpeg .jpg .png</small>
                        @error('image') <span class="textdanger">{{$message}}</span> @enderror
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
@stop

@push('js')
<script>
function previewImageTambah() {
    const foto = document.querySelector('#image');
    const imgPreview = document.querySelector('.img-preview');

    imgPreview.style.display = 'block';

    const fileReader = new FileReader();
    
    fileReader.onload = function(event) {
        imgPreview.src = event.target.result;
    };

    fileReader.readAsDataURL(foto.files[0]);
}


</script>
@endpush