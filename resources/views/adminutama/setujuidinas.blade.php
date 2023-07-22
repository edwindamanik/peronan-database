@extends('home')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .card {
            font-size: 13px;
        }

        /* Ganti lebar dan tinggi card */
        .card {
            width: 408px;
            height: 295px;
            background-color: #F2F2F2;
        }

        .card-body {}

        /* Ganti lebar dan tinggi tombol Setujui dan Tolak */


        .card-button.setujui {
            background-color: #4CAF50;
            color: white;
        }

        .card-button.tolak {
            background-color: #f44336;
            color: white;
        }
        .error-message {
            color: red;
        }

        #cardsContainer {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(408px, 1fr));
        grid-gap: 1rem;
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
        <div class="container" id="cardsContainer">
            @foreach ($data as $data)
                <div class="card mb-4 ">
                    <b>
                        <div class="card-header">Permintaan Bergabung {{ $data->nama_dinas }}</div>

                    </b>
                    <div class="card-body">
                        <p class="card-text">Nama Dinas: {{ $data->nama_dinas }}</p>
                        <p class="card-text">Kepala Dinas: {{ $data->kepala_dinas }}</p>
                        <p class="card-text">Nomor Telepon: {{ $data->no_telp_dinas }}</p>
                        <p class="card-text">Email: {{ $data->email_dinas }}</p>
                        <p class="card-text">Lokasi: {{ $data->provinsi }}, {{ $data->kabupaten }}</p>
                        <!-- Menggabungkan data provinsi dan kabupaten -->
                    </div>
                    <div class="card-footer">
                        <!-- Menggunakan kelas Bootstrap untuk menampilkan tombol -->
                        <button class="btn btn-success card-button setujui" data-id="{{ $data->id }}" data-status="aktif" style="position: relative; padding-left: 30px; background-image: url('/cek.png'); background-repeat: no-repeat; background-position: left center; background-size: 20px 20px;">Setujui</button>
                        {{-- <button class="btn btn-success card-button setujui" data-id="{{ $data->id }}" data-status="aktif">Setujui</button> --}}
                        {{-- <button class="btn btn-danger card-button tolak" data-id="{{ $data->id }}" data-status="ditolak">Tolak</button> --}}
                        <button class="btn btn-danger card-button tolak" data-id="{{ $data->id }}" data-status="ditolak" style="position: relative; padding-left: 30px; background-image: url('/salah.png'); background-repeat: no-repeat; background-position: left center; background-size: 20px 20px;">Tolak</button>
                
                    </div>
                </div>
            @endforeach
        </div>
        





    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttonsSetujui = document.querySelectorAll('.card-button.setujui');
            const buttonsTolak = document.querySelectorAll('.card-button.tolak');
    
            buttonsSetujui.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const status = this.getAttribute('data-status');
                    handleStatusChange(id, status);
                });
            });
    
            buttonsTolak.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const status = this.getAttribute('data-status');
                    handleStatusChange(id, status);
                });
            });
    
            function handleStatusChange(id, status) {
                fetch(`/api/ubah-status/${id}/${status}`, {
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
                        // Jika berhasil, lakukan aksi sesuai kebutuhan
                        window.location.reload();
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
