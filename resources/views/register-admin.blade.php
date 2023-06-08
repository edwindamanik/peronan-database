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
    <div class="container-fluid d-flex justify-content-center align-items-center" style="padding: 0 100px">
        <div class="card shadow-lg border-0 rounded-lg" style="width: 400px;">
            <div class="card-body">
                <h4 class="card-title text-center fw-bold mb-4">Registrasi</h4>
                <form action="{{ route('registerDinas') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Nama Dinas" name="nama_dinas" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Nama Kepala Dinas" name="kepala_dinas" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Nomor Telepon" name="no_telp_dinas" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email_dinas" required>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" name="provinsi" required>
                            <option value="">Pilih Provinsi</option>
                            <option value="Sumatera Utara">Sumatera Utara</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" name="kabupaten" required>
                            <option value="">Pilih Kabupaten</option>
                            <option value="Humbang Hasundutan">Humbang Hasundutan</option>
                            <option value="Tobasa">Tobasa</option>
                            <option value="Tarutung">Tarutung</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Peraturan Daerah" name="perda" required>
                    </div>
                    <div class="mb-3">
                        <label for="upload_perda" class="form-label">Upload Perda</label>
                        <input type="file" id="upload_perda" name="upload_perda" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Upload Logo</label>
                        <input type="file" id="logo" name="logo" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Selanjutnya</button>
                </form>
            </div>
        </div>
        <div style="background-color: #243763; height: 650px; width: 400px;">
            <img src="{{ URL::asset('source-image/wood_hand.png') }}" alt="" style="height: 100%; width: 100%; object-fit: cover;">
        </div>
    </div>
</body>

</html>
