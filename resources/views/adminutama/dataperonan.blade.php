@extends('home')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .error-message {
            color: red;
        }
    </style>

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
            <h2 class="mt-4">Data PErOnan</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Data PErOnan</li>
                </ol>
            </nav>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Data PErOnan
                </div>
                <div class="card-body">
                    @if (session()->has('updateMessage'))
                        <div class="alert alert-primary" role="alert">
                            {{ session('updateMessage') }}
                        </div>
                    @endif
                    @if (session()->has('storeMessage'))
                        <div class="alert alert-success" role="alert">
                            {{ session('storeMessage') }}
                        </div>
                    @endif
                    @if (session()->has('storeMessagee'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('storeMessagee') }}
                        </div>
                    @endif
                    @if (session()->has('deleteMessage'))
                        <div class="alert alert-success" role="alert">
                            {{ session('deleteMessage') }}
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
                                    <th>Email</th>
                                    <th>Alamat</th>
                                    <th>No Telepon</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->alamat }}</td>
                                        <td>{{ $item->notelepon }}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning edit-button" data-toggle="modal"
                                                data-target="#myModalEdit" data-jsondata="{{ json_encode($item) }}">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#myModalDelete" data-faq-id="{{ $item->id }}">
                                                Hapus
                                            </button>

                                            {{-- Formulir Tersembunyi untuk Penghapusan --}}
                                            <form id="deleteForm" action="/faq/delete/{{ $item->id }}" method="post"
                                                style="display: none;">
                                                @csrf
                                            </form>

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

       



        {{-- MODAL EDIT --}}
        <div class="modal fade" id="myModalEdit">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal header -->
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data PErOnan</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form action="" method="POST" id="editForm">
                            @csrf
                            <div class="form-group">
                                <label for="inputText">Email</label>
                                <input type="text" class="form-control" id="emailD" name="emailD"
                                    placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Alamat</label>
                                <input type="text" class="form-control" id="alamatD" name="alamatD"
                                    placeholder="Alamat">
                            </div>
                            <div class="form-group">
                                <label for="inputText">No Telepon</label>
                                <input type="text" class="form-control" id="noteleponD" name="noteleponD"
                                    placeholder="No Telepon">
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
                        <a href="/faq/delete/{{ $item->id }}" class="btn btn-danger delete-button">Delete</a>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        $(document).ready(function() {
            $('#searchInput').keyup(function() {
                var searchText = $(this).val().toLowerCase();

                $('#dataTable tbody tr').each(function() {
                    var rowText = $(this).text().toLowerCase();
                    if (rowText.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });

        $(document).ready(function() {
            $('.edit-button').click(function() {
                var jsonData = $(this).data('jsondata');
                $('#emailD').val(jsonData.email);
                $('#alamatD').val(jsonData.alamat);
                $('#noteleponD').val(jsonData.notelepon);

                var updateForm = $('#editForm');
                var actionUrl = '/dataperonan/update/' + jsonData.id;

                updateForm.attr('action', actionUrl);
            });
        });


        // $(document).ready(function() {
        //     $('#myModalDelete').on('show.bs.modal', function(event) {
        //         var button = $(event.relatedTarget); // Button that triggered the modal
        //         var faqId = button.data('faq-id'); // Retrieve the value of data-pasar-id

        //         var modal = $(this);
        //         var deleteButton = modal.find('.delete-button'); // Get the delete button inside the modal

        //         // Update the href with the pasarId value
        //         deleteButton.attr('href', '/faq/delete/' + faqId);
        //     });
        // });
        $(document).ready(function() {
            $('#myModalDelete').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Tombol yang memicu modal
                var faqId = button.data('faq-id'); // Dapatkan nilai data-pasar-id

                var modal = $(this);
                var deleteForm = modal.find('#deleteForm'); // Dapatkan formulir tersembunyi di dalam modal

                // Perbarui URL aksi formulir dengan nilai faqId
                deleteForm.attr('action', '/faq/delete/' + faqId);
            });

            // Ketika tombol "Delete" di dalam modal diklik, kirimkan formulir
            $('.delete-button').on('click', function() {
                $('#deleteForm').submit();
            });
        });
    </script>
@endsection
