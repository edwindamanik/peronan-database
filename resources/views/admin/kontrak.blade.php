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
            <h2 class="mt-4">Daftar Kontrak</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Daftar Kontrak</li>
                </ol>
            </nav>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalTambah">
                Tambah Kontrak
            </button>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Daftar Kontrak
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
                                    <th>Wajib Retribusi</th>
                                    <th>Jenis Retribusi</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->jenis_pembayaran }}</td>
                                        <td>{{ $item->tanggal_mulai }}</td>
                                        <td
                                            style="font-weight: bold ;color: {{ $item->status === 'menunggu' ? '#FF9C07' : ($item->status === 'benar' ? '#32B83F' : '#343A40') }}">
                                            {{ $item->status }}</td>
                                        <td>
                                            @if ($item->status != 'benar')
                                                <button class="btn btn-success btn-setujui" data-id="{{ $item->id }}"
                                                    data-status="Setuju">Setujui</button>
                                            @endif
                                            <button type="button" class="btn btn-warning edit-button" data-toggle="modal"
                                                data-target="#myModalEdit" data-jsondata="{{ json_encode($item) }}">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#myModalDelete" data-pasar-id="{{ $item->id }}">
                                                Hapus
                                            </button>
                                            <button type="button" class="btn btn-primary" onclick="previewKontrak(event)" data-id="{{ $item->id }}" data-jsondata="{{ json_encode($item) }}">
                                                Lihat
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
                        <form action="{{ URL('/kontrak/store') }}" enctype="multipart/form-data" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="inputText">No Surat</label>
                                <input type="text" class="form-control" name="noSurat" placeholder="No Surat" required>
                            </div>
                            <div class="form-group">
                                <label for="inputText">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="tanggalMulai" placeholder="Tanggal Mulai"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="inputText">Tanggal Selesai</label>
                                <input type="date" class="form-control" name="tanggalSelesai"
                                    placeholder="Tanggal Selesai" required>
                            </div>
                            <div class="form-group">
                                <label for="inputText">File Kontrak</label>
                                <input type="file" class="form-control" name="file_pdf" required>
                            </div>
                            <input type="hidden" class="form-control" value="menunggu" name="status">
                            <div class="form-group">
                                <label for="inputText">Wajib Retribusi</label>
                                <select class="form-control" name="wajibRetribusi" required>
                                    <option value="">Pilih Wajib Retribusi</option>
                                    @foreach ($wajib_retribusi as $itemWajibRetribusi)
                                        <option value="{{ $itemWajibRetribusi->id }}">{{ $itemWajibRetribusi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputText">Unit</label>
                                <select class="form-control" name="unitId" required>
                                    <option value="">Pilih Unit</option>
                                    @foreach ($unit as $itemUnit)
                                        <option value="{{ $itemUnit->id }}">{{ $itemUnit->no_unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputText">Pengaturan Surat</label>
                                <select class="form-control" name="pengaturanSurat" required>
                                    <option value="">Pilih Pengaturan Surat</option>
                                    @foreach ($pengaturan as $itemPengaturan)
                                        <option value="{{ $itemPengaturan->id }}">{{ $itemPengaturan->judul }}</option>
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
                        <h5 class="modal-title">Edit Kontrak</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form action="" method="POST" enctype="multipart/form-data" id="editForm">
                            @csrf
                            <div class="form-group">
                                <label for="inputText">No Surat</label>
                                <input type="text" class="form-control" id="noSurat" name="noSurat"
                                    placeholder="No Surat" required>
                            </div>
                            <div class="form-group">
                                <label for="inputText">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="tanggalMulai" name="tanggalMulai"
                                    placeholder="Tanggal Mulai" required>
                            </div>
                            <div class="form-group">
                                <label for="inputText">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="tanggalSelesai" name="tanggalSelesai"
                                    placeholder="Tanggal Selesai" required>
                            </div>
                            <div class="form-group">
                                <label for="inputText">File Kontrak</label>
                                <input type="file" class="form-control" name="file_pdf" required>
                            </div>
                            <div class="form-group">
                                <label for="inputText">Status Kontrak</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="">Pilih Status Kontrak</option>
                                    <option value="menunggu">Menunggu</option>
                                    <option value="benar">Benar</option>
                                    <option value="kurang_benar">Butuh Review</option>
                                </select>
                            </div>
                            <input type="hidden" id="wajibRetribusi" name="wajibRetribusi">
                            <div class="form-group">
                                <label for="inputText">Unit</label>
                                <select class="form-control" id="unitId" name="unitId" required>
                                    <option value="">Pilih Unit</option>
                                    @foreach ($unit as $itemUnit)
                                        <option value="{{ $itemUnit->id }}">{{ $itemUnit->no_unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputText">Pengaturan Surat</label>
                                <select class="form-control" id="pengaturanSurat" name="pengaturanSurat" required>
                                    <option value="">Pilih Pengaturan Surat</option>
                                    @foreach ($pengaturan as $itemPengaturan)
                                        <option value="{{ $itemPengaturan->id }}">{{ $itemPengaturan->judul }}</option>
                                    @endforeach
                                </select>
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
                        {{-- <a href="/kontrak/delete/{{ $item->id }}" class="btn btn-danger delete-button">Delete</a>
                         --}}

                         <a href="#" class="btn btn-danger delete-button" id="delete-link">Delete</a>
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
        $(document).ready(function() {
            $(".delete-button").on("click", function() {
                // Get the item ID from the data-item-id attribute
                var itemId = $(this).data("item-id");
                // Build the delete link with the correct item ID
                var deleteLink = "/kontrak/delete/" + itemId;
                // Update the href of the delete link in the modal footer
                $("#delete-link").attr("href", deleteLink);
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttonsSetujui = document.querySelectorAll('.btn-setujui');

            buttonsSetujui.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const status = this.getAttribute('data-status');
                    handleStatusChange(id, status);
                });
            });

            function handleStatusChange(id, status) {
                fetch(`/contracts/setuju/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            id: id,
                            status: status
                        })
                    })
                    .then(response => {
                        if (response.ok) {
                            // Jika berhasil, lakukan aksi sesuai kebutuhan, misalnya tampilkan pemberitahuan
                            alert('Status kontrak berhasil diubah menjadi Setuju.');
                            // Halaman akan diperbarui secara otomatis setelah berhasil mengubah status
                            window.location.href = '{{ route('kontrak.index') }}'; // Redirect to the same page
                        } else {
                            console.error('Terjadi kesalahan saat mengubah status.');
                        }
                    })
                    .catch(error => {
                        console.error('Terjadi kesalahan saat mengirim permintaan:', error);
                    });
            }
        });
    </script>

    <!-- Include jQuery library (if you haven't already) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        function previewKontrak(event) {
            event.preventDefault(); // Prevent the default button behavior (form submission or link navigation)

            const jsonData = $(event.target).data('jsondata');
            const id = jsonData.id; // Assuming your JSON data has an 'id' property

            // Redirect the user to the specified route with the ID parameter
            window.location.href = `/${id}/kontrakpreview`;
        }
    </script>


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

                $('#noSurat').val(jsonData.no_surat);
                $('#tanggalMulai').val(jsonData.tanggal_mulai);
                $('#tanggalSelesai').val(jsonData.tanggal_selesai);
                $('#pekerjaan').val(jsonData.pekerjaan);
                $('#alamat').val(jsonData.alamat);
                $('#userId').val(jsonData.users_id);
                $('#users').val(jsonData.nama);
                $('#wajibRetribusi').val(jsonData.wajib_retribusi_id);
                $('#unitId').val(jsonData.unit_id.toString());
                $('#pengaturanSurat').val(jsonData.pengaturan_id.toString());
                $('#status').val(jsonData.status.toString());

                var updateForm = $('#editForm');
                var actionUrl = '/kontrak/update/' + jsonData.id;

                updateForm.attr('action', actionUrl);
            });
        });

        $(document).ready(function() {
            $('#myModalDelete').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var pasarId = button.data('pasar-id'); // Retrieve the value of data-pasar-id

                var modal = $(this);
                var deleteButton = modal.find('.delete-button'); // Get the delete button inside the modal

                // Update the href with the pasarId value
                deleteButton.attr('href', '/kontrak/delete/' + pasarId);
            });
        });
    </script>
@endsection
