<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="{{ asset('assets/style/login.css') }}">
    <title>ZaikoTrack | Login</title>
</head>

<body>

    <!----------------------- Main Container -------------------------->

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <!----------------------- Login Container -------------------------->

        <div class="row border rounded-2 p-3 bg-white shadow box-area">

            <!--------------------------- Left Box ----------------------------->

            <div class="col-md-5 rounded-3 d-flex justify-content-center align-items-center flex-column left-box"
                style="background: rgba(221, 219, 219, 0.80)">
                <div class="featured-image mb-3">
                    <img src="{{ asset('assets/img/login/logo.png') }}" class="img-fluid" style="width: 500px;">
                </div>
            </div>

            <!-------------------- ------ Right Box ---------------------------->

            <div class="col-md-7 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4">
                        <h2>Hello, Again!</h2>
                        <p>We are happy to have you back.</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email field --}}
                        <div class="input-group mb-3">
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                                aria-label="Username" aria-describedby="basic-addon1" required autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        {{-- Password field --}}
                        <div class="input-group mb-1">
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" 
                            aria-label="Password" aria-describedby="basic-addon1" value="{{ old('password') }}" required autofocus>

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group mb-5 d-flex justify-content-between">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember" class="form-check-label text-secondary"><small>Remember Me</small></label>
                            </div>
                            <div class="forgot">
                                <small><a href="#">Forgot Password?</a></small>
                            </div>
                        </div>

                        {{-- Login field --}}
                        <div class="input-group mb-3">
                            <button type="submit" class="btn btn-lg btn-primary w-100 fs-6">Login</button>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </body>
</html>
