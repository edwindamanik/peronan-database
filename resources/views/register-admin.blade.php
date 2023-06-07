<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link href="{{ URL::asset('styles/styles.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('styles/styles.css') }}" rel="stylesheet" />
</head>
<body>
    <div class="d-flex justify-content-center align-items-center" style="padding: 0 300px 0 300px">
        <div style="height: 100vh; width: 100%" class="py-5 px-3">
            <h4 class="text-center fw-bold">Registrasi</h4>
            <p class="fw-bold">1. Data Administrator</p>
            <form action="{{ URL('/process-admin') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="text" class="form-control" placeholder="Nama" name="nama" required>
                <input type="email" class="form-control mt-4" placeholder="Email" name="email" required>
                <input type="text" class="form-control mt-4" placeholder="Username" name="username" required>
                <input type="password" class="form-control mt-4" placeholder="Password" name="password" required>
                <input type="hidden" class="form-control mt-4" value="admin" name="role">
                @php
                    $segments = request()->segments();
                    $number = end($segments);
                @endphp
                <input type="hidden" class="form-control mt-4" value="{{ $number }}" name="kabupaten_id">
                <input type="password" class="form-control mt-4" placeholder="Konfirmasi Password" name="konfirmasi_password" required>
                <button type="submit" class="form-control mt-4">Selanjutnya</button>
            </form>
        </div>
        <div style="background-color: #243763; height: 100vh; width: 100%">
            <img src="{{ URL::asset('source-image/wood_hand.png') }}" alt="">
        </div>
    </div>
</body>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script> --}}
</html>