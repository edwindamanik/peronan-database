<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>PErOnan</title>
    <link href="{{ asset('styles/styles.css') }}" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="bg" style="background-color: #212429">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <div class="text-center">
                                        <img src="{{ URL::asset('source-image/logo.png') }}" class="img-fluid" alt=""
                                            width="50%" height="50%">
                                        <p>PErOnan (Platform e-retribusi Onan)
                                        <p>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ URL('/login') }}">
                                        @csrf
                                        @if (session('error'))
                                            <div class="alert alert-danger">{{ session('error') }}</div>
                                        @endif
                                        <div class="form-group">
                                            <label class="small mb-1" for="username">Username/Email</label>
                                            <input id="username" type="username"
                                                class="form-control py-4 @error('username') is-invalid @enderror"
                                                name="username" value="{{ old('username') }}" autocomplete="username"
                                                autofocus>
                                            @error('username')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputPassword">Password</label>
                                            <input id="password" type="password"
                                                class="form-control py-4 @error('password') is-invalid @enderror"
                                                name="password" autocomplete="current-password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" name="remember"
                                                    id="remember" {{ old('remember') ? 'checked' : '' }} />
                                                <label class="custom-control-label" for="remember">Remember Me</label>
                                            </div>
                                        </div>
                                        <div
                                            class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            {{-- <a class="small" href="{{ route('password.request') }}">Forgot Password?</a> --}}
                                            <button type="submit" class="btn btn-primary">
                                                Login
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; <?php echo date('Y'); ?> | Dinas Koperasi, Perdagangan Dan
                            Industri Kabupaten Humbang Hasundutan</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('template/js/scripts.js') }}"></script>
</body>

</html>
