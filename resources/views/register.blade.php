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
            <p class="fw-bold">1. Data Dinas</p>
            <form action="{{ URL('/registerDinas') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="text" class="form-control" placeholder="Nama Dinas" name="nama_dinas" required>
                <input type="text" class="form-control mt-4" placeholder="Nama Kepala Dinas" name="kepala_dinas" required>
                <input type="text" class="form-control mt-4" placeholder="Nomor Telepon" name="no_telp_dinas" required>
                <input type="email" class="form-control mt-4" placeholder="Email" name="email_dinas" required>
                <select class="form-control mt-4" name="provinsi" required>
                    <option value="">Pilih Provinsi</option>
                    <option value="Sumatera Utara">Sumatera Utara</option>
                </select>
                <select class="form-control mt-4" name="kabupaten" required>
                    <option value="">Pilih Kabupaten</option>
                    <option value="Humbang Hasundutan">Humbang Hasundutan</option>
                    <option value="Tobasa">Tobasa</option>
                    <option value="Tarutung">Tarutung</option>
                </select>
                <input type="text" class="form-control mt-4" placeholder="Peraturan Daerah" name="perda" required>
                <div class="mt-4">
                    <span>Upload Perda</span>
                    <input type="file" name="upload_perda" class="form-control" required>
                </div>
                <div class="mt-3">
                    <span>Upload logo</span>
                    <input type="file" name="logo" class="form-control" required>
                </div>
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