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
        <h2 class="mt-4">Daftar User</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Daftar User</li>
            </ol>
        </nav>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalTambah">
            Tambah User
        </button>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table mr-1"></i>
                Daftar User
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
                                <th>Email</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->role }}</td>
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
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="{{ URL('/register') }}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="inputText">Nama</label>
                            <input type="text" class="form-control" name="nama" placeholder="Nama" required>
                        </div>
                        <div class="form-group">
                            <label for="inputText">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Email"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="inputText">Username</label>
                            <input type="text" class="form-control" name="username" placeholder="Username"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="inputText">Password</label>
                            <input type="text" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <label for="inputText">Role</label>
                            <select class="form-control" name="role" required>
                                <option value="">Pilih Role</option>
                                <option value="wajib_retribusi">Wajib Retribusi</option>
                                <option value="admin">Admin</option>
                                <option value="petugas">Petugas</option>
                            </select>
                        </div>
                        <input type="hidden" name="kabupaten_id" value="{{ Auth::user()->kabupaten_id }}">
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
                    <h5 class="modal-title">Edit Kontrak</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data" id="editForm">
                        @csrf
                        <div class="form-group">
                            <label for="inputText">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" required>
                        </div>
                        <div class="form-group">
                            <label for="inputText">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="inputText">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                                required>
                        </div>
                        <input type="hidden" id="password" name="password">
                        <div class="form-group">
                            <label for="inputText">Role</label>
                            <select class="form-control" id="roleUser" name="role" required>
                                <option value="">Pilih Role</option>
                                <option value="wajib_retribusi">Wajib Retribusi</option>
                                <option value="admin">Admin</option>
                                <option value="petugas">Petugas</option>
                            </select>
                        </div>
                        <input type="hidden" name="kabupaten_id" value="{{ Auth::user()->kabupaten_id }}">
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
                    <a href="/remove-user/{{ $item->id }}" class="btn btn-danger delete-button">Delete</a>
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
                        <img src="" alt="Image" id="modalImage">
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

            $('#nama').val(jsonData.nama);
            $('#email').val(jsonData.email);
            $('#username').val(jsonData.username);
            $('#password').val(jsonData.password);
            $('#roleUser').val(jsonData.role.toString());

            var updateForm = $('#editForm');
            var actionUrl = '/update-user/' + jsonData.id;

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
            deleteButton.attr('href', '/remove-user/' + pasarId);
        });
    });

</script>

@endsection
