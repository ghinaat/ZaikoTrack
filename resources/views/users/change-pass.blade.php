@extends('layouts.demo')
@section('title', 'Change Password')
@section('css')
@endsection
@section('breadcrumb-name')
Change Password
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
                @include('layouts.partials.nav-pills')
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('user.saveChangePassword', $user->id_users ) }}">
                        @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="old_password" class="form-label">Password Lama</label>
                            <input type="password" class="form-control @error('old_password') is-invalid @enderror"
                                id="old_password" name="old_password" autofocus >
                            @error('old_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" >
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                id="password_confirmation" name="password_confirmation" >
                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <div class="col-md-10"></div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100"
                                    onclick="notificationBeforeSubmit(event, this)">Edit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')

@endpush