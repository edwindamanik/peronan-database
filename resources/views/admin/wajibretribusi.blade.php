@extends('home')

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@if (session()->has('success'))
<div class="alert text-white bg-success" role="alert">
    <div class="iq-alert-text">{{ session()->get('success') }}</div>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span class="fa fa-close"></span>
    </button>
</div>
@elseif (session()->has('danger'))
<div class="alert text-white bg-danger" role="alert">
    <div class="iq-alert-text">{{ session()->get('danger') }}</div>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span class="fa fa-close"></span>
    </button>
</div>
@endif

<div class="messages"></div>
<main>
    <div class="container-fluid">
        <h2 class="mt-4">Daftar Wajib Retribusi</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Daftar Wajib Retribusi</li>
            </ol>
        </nav>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalTambah">
            Tambah Wajib Retribusi
        </button>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table mr-1"></i>
                Daftar Wajib Retribusi
            </div>
            <div class="card-body">
                @if (session()->has('deleteMessage'))
                <div class="alert alert-success" role="alert">
                    {{ session('deleteMessage') }}
                </div>
                @endif
                @if (session()->has('storeMessage'))
                <div class="alert alert-success" role="alert">
                    {{ session('storeMessage') }}
                </div>
                @endif
                @if (session()->has('updateMessage'))
                <div class="alert alert-primary" role="alert">
                    {{ session('updateMessage') }}
                </div>
                @endif
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <div class="input-group mb-3">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Pekerjaan</th>
                                <th>KTP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->pekerjaan }}</td>
                                <td><a href="#" class="image-link" data-url="{{ $item->ktp }}">Lihat KTP</a></td>
                                <td>
                                    <button type="button" class="btn btn-warning edit-button" data-toggle="modal"
                                        data-target="#myModalEdit" data-jsondata="{{ json_encode($item) }}">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#myModalDelete" data-pasar-id="{{ $item->id }}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        {{ $data->appends(['search' => request()->input('search')])->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div class="modal fade" id="myModalTambah">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Wajib Retribusi</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="{{ URL('/wajib-retribusi/store') }}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="inputText">NIK</label>
                            <input type="number" class="form-control" name="nik" placeholder="NIK" required>
                        </div>
                        <div class="form-group">
                            <label for="inputText">KTP</label>
                            <input type="file" class="form-control" name="ktp" placeholder="KTP" required>
                        </div>
                        <div class="form-group">
                            <label for="inputText">Pekerjaan</label>
                            <input type="text" class="form-control" name="pekerjaan" placeholder="Pekerjaan" required>
                        </div>
                        <div class="form-group">
                            <label for="inputText">Jenis Usaha</label>
                            <input type="text" class="form-control" name="jenisUsaha" placeholder="Jenis Usaha"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="inputText">Alamat</label>
                            <input type="text" class="form-control" name="alamat" placeholder="Alamat" required>
                        </div>
                        <div class="form-group">
                            <label for="inputText">Pengguna</label>
                            <select class="form-control" name="usersId" required>
                                <option value="">Pilih User</option>
                                @foreach ($users as $itemUsers)
                                <option value="{{ $itemUsers->id }}">{{ $itemUsers->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div class="modal fade" id="myModalEdit">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header">
                    <h5 class="modal-title">Edit Wajib Retribusi</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data" id="editForm">
                        @csrf
                        <div class="form-group">
                            <label for="inputText">NIK</label>
                            <input type="number" class="form-control" id="nik" name="nik" placeholder="NIK" required>
                        </div>
                        <div class="form-group">
                            <label for="inputText">KTP</label>
                            <input type="file" class="form-control" name="ktp" required>
                        </div>
                        <div class="form-group">
                            <label for="inputText">Pekerjaan</label>
                            <input type="text" class="form-control" id="pekerjaan" name="pekerjaan"
                                placeholder="Pekerjaan" required>
                        </div>
                        <div class="form-group">
                            <label for="inputText">Jenis Usaha</label>
                            <input type="text" class="form-control" id="jenisUsaha" name="jenisUsaha"
                                placeholder="Jenis Usaha" required>
                        </div>
                        <div class="form-group">
                            <label for="inputText">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat"
                                required>
                        </div>
                        <input type="hidden" class="form-control" id="userId" name="usersId" placeholder="Nama Pengguna">
                        <div class="form-group">
                            <label for="inputText">Nama Wajib Retribusi</label>
                            <input type="text" class="form-control" id="users" name="users" placeholder="Nama Pengguna"
                                disabled>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL HAPUS --}}
    <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalDeleteLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col text-center">
                        <img src="{{ URL::asset('source-image/trash-icon.png') }}" alt="">
                    </div>
                    {{-- <p>apakah anda yakin ingin menghapus pasar {{ $item->nama_pasar }}?</p> --}}
                    <div class="col text-center">
                        <p class="font-weight-bold">Apakah anda yakin ?</p>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <a href="/wajib-retribusi/delete/{{ $item->id }}" class="btn btn-danger delete-button">Delete</a>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL KTP --}}
    <div class="modal" id="myModalKtp">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <!-- Image container -->
                    <div class="text-center">
                        <img src="" alt="Image" id="modalImage" width="450px" height="400px" style="position: relative;">
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<script>
    $(document).ready(function () {
        $('#searchInput').keyup(function () {
            var searchText = $(this).val().toLowerCase();

            $('#dataTable tbody tr').each(function () {
                var rowText = $(this).text().toLowerCase();
                if (rowText.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });

    $(document).ready(function () {
        $('.edit-button').click(function () {
            var jsonData = $(this).data('jsondata');

            $('#nik').val(jsonData.nik);
            $('#ktp').val(jsonData.ktp);
            $('#jenisUsaha').val(jsonData.jenis_usaha);
            $('#pekerjaan').val(jsonData.pekerjaan);
            $('#alamat').val(jsonData.alamat);
            $('#userId').val(jsonData.users_id);
            $('#users').val(jsonData.nama);

            var updateForm = $('#editForm');
            var actionUrl = '/wajib-retribusi/update/' + jsonData.id;

            updateForm.attr('action', actionUrl);
        });
    });

    $(document).ready(function () {
        $('#myModalDelete').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var pasarId = button.data('pasar-id'); // Retrieve the value of data-pasar-id

            var modal = $(this);
            var deleteButton = modal.find('.delete-button'); // Get the delete button inside the modal

            // Update the href with the pasarId value
            deleteButton.attr('href', '/wajib-retribusi/delete/' + pasarId);
        });
    });

    $(document).ready(function () {
        $('.image-link').click(function (e) {
            e.preventDefault();
            var imageUrl = 'http://127.0.0.1:8000/ktp/' + $(this).data('url');
            $('#modalImage').attr('src', imageUrl);
            $('#myModalKtp').modal('show');
        });
    });

</script>

@endsection
