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
            <h2 class="mt-4">Daftar Pasar</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Pasar</li>
                </ol>
            </nav>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalTambah">
                Tambah Pasar
            </button>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Daftar Pasar
                </div>
                <div class="card-body">
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
                                    <th>Nama Pasar</th>
                                    <th>Kode Pasar</th>
                                    <th>Kelompok Pasar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_pasar }}</td>
                                        <td>{{ $item->kode_pasar }}</td>
                                        <td>{{ $item->kelompok_pasar }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary detail-button" data-toggle="modal"
                                                data-target="#myModalDetail" data-jsondata="{{ json_encode($item) }}">
                                                Detail
                                            </button>
                                            <button type="button" class="btn btn-warning edit-button" data-toggle="modal"
                                                data-target="#myModaledit" data-jsondata="{{ json_encode($item) }}">
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

        {{-- MODAL DETAIL --}}
        <div class="modal fade" id="myModalDetail">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal header -->
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Pasar</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tbody id="modal-table-body">
                                <tr>
                                    <th>Nama Pasar</th>
                                    <td id="modal-property1"></td>
                                </tr>
                                <tr>
                                    <th>Kode Pasar</th>
                                    <td id="modal-property2"></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td id="modal-property3"></td>
                                </tr>
                                <tr>
                                    <th>Koordinat</th>
                                    <td id="modal-property4"></td>
                                </tr>
                                <tr>
                                    <th>Luas Lahan</th>
                                    <td id="modal-property5"></td>
                                </tr>
                                <tr>
                                    <th>Tahun Berdiri</th>
                                    <td id="modal-property6"></td>
                                </tr>
                                <tr>
                                    <th>Tahun Pembangunan</th>
                                    <td id="modal-property7"></td>
                                </tr>
                                <tr>
                                    <th>Kondisi Pasar</th>
                                    <td id="modal-property8"></td>
                                </tr>
                                <tr>
                                    <th>Pengelola</th>
                                    <td id="modal-property9"></td>
                                </tr>
                                <tr>
                                    <th>Operasional Pasar</th>
                                    <td id="modal-property10"></td>
                                </tr>
                                <tr>
                                    <th>Jumlah Pedagang</th>
                                    <td id="modal-property11"></td>
                                </tr>
                                <tr>
                                    <th>Omzet Perbulan</th>
                                    <td id="modal-property12"></td>
                                </tr>
                                <tr>
                                    <th>Kelompok Pasar</th>
                                    <td id="modal-property13"></td>
                                </tr>
                                <tr>
                                    <th>Petugas</th>
                                    <td id="modal-property14"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- MODAL TAMBAH --}}
        <div class="modal fade" id="myModalTambah">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal header -->
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Pasar</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form action="{{ URL('/pasar/create') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="inputText">Nama Pasar</label>
                                <input type="text" class="form-control" name="namaPasar" placeholder="Nama Pasar">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Kode Pasar</label>
                                <input type="text" class="form-control" name="kodePasar" placeholder="Kode Pasar">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Alamat Pasar</label>
                                <input type="text" class="form-control" name="alamatPasar"
                                    placeholder="Alamat Pasar">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Koordinat Pasar</label>
                                <input type="text" class="form-control" name="koordinatPasar"
                                    placeholder="Koordinat Pasar">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Luas Lahan</label>
                                <input type="text" class="form-control" name="luasLahan" placeholder="Luas Lahan">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Tahun Berdiri</label>
                                <input type="text" class="form-control" name="tahunBerdiri"
                                    placeholder="Tahun Berdiri">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Tahun Pembangunan</label>
                                <input type="text" class="form-control" name="tahunPembangunan"
                                    placeholder="Tahun Pembangunan">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Kondisi Pasar</label>
                                <input type="text" class="form-control" name="kondisiPasar"
                                    placeholder="Kondisi Pasar">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Pengelola</label>
                                <input type="text" class="form-control" name="pengelola" placeholder="Pengelola">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Operasional Pasar</label>
                                <input type="text" class="form-control" name="operasionalPasar"
                                    placeholder="Operasional Pasar">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Jumlah Pedagang</label>
                                <input type="number" class="form-control" name="jumlahPedagang"
                                    placeholder="Jumlah Pedagang">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Omzet Perbulan</label>
                                <input type="text" class="form-control" name="omzetPerbulan"
                                    placeholder="Omzet Perbulan">
                            </div>
                            <div class="form-group">
                                <label for="kelompokPasarSelect">Kelompok Pasar:</label>
                                <select id="kelompokPasarSelect" name="kelompokPasar" class="form-control">
                                    <option value="">Pilih Kelompok Pasar</option>
                                    @foreach ($kelompok_pasar as $item)
                                        <option value="{{ $item->id }}">{{ $item->kelompok_pasar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="selectedPetugas">Selected Petugas:</label>
                                <div id="selectedPetugasContainer"></div>
                            </div>
                            <div class="form-group">
                                <label for="petugasSelect">Pilih Petugas:</label>
                                <select id="petugasSelect" class="form-control">
                                    <option value="">Pilih Petugas</option>
                                    @foreach ($petugas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
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
        <div class="modal fade" id="myModaledit">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal header -->
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Pasar</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form action="" method="post" id="editForm">
                            @csrf
                            <div class="form-group">
                                <label for="inputText">Nama Pasar</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    placeholder="Nama Pasar">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Kode Pasar</label>
                                <input type="text" class="form-control" id="kode" name="kodePasar"
                                    placeholder="Kode Pasar">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Alamat Pasar</label>
                                <input type="text" class="form-control" id="alamat" name="alamatPasar"
                                    placeholder="Alamat Pasar">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Koordinat Pasar</label>
                                <input type="text" class="form-control" id="koordinat" name="koordinatPasar"
                                    placeholder="Koordinat Pasar">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Luas Lahan</label>
                                <input type="text" class="form-control" id="luas" name="luasLahan"
                                    placeholder="Luas Lahan">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Tahun Berdiri</label>
                                <input type="text" class="form-control" id="berdiri" name="tahunBerdiri"
                                    placeholder="Tahun Berdiri">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Tahun Pembangunan</label>
                                <input type="text" class="form-control" id="pembangunan" name="tahunPembangunan"
                                    placeholder="Tahun Pembangunan">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Kondisi Pasar</label>
                                <input type="text" class="form-control" id="kondisi" name="kondisiPasar"
                                    placeholder="Kondisi Pasar">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Pengelola</label>
                                <input type="text" class="form-control" id="pengelola" name="pengelola"
                                    placeholder="Pengelola">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Operasional Pasar</label>
                                <input type="text" class="form-control" id="operasional" name="operasionalPasar"
                                    placeholder="Operasional Pasar">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Jumlah Pedagang</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlahPedagang"
                                    placeholder="Jumlah Pedagang">
                            </div>
                            <div class="form-group">
                                <label for="inputText">Omzet Perbulan</label>
                                <input type="text" class="form-control" id="omzet" name="omzetPerbulan"
                                    placeholder="Omzet Perbulan">
                            </div>
                            <div class="form-group">
                                <label for="kelompokPasarSelect">Kelompok Pasar:</label>
                                <select id="kelompokPasarSelect" name="kelompokPasar" class="form-control">
                                    <option value="">Pilih Kelompok Pasar</option>
                                    @foreach ($kelompok_pasar as $item)
                                        <option value="{{ $item->id }}">{{ $item->kelompok_pasar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="selectedPetugas">Selected Petugas:</label>
                                <div id="selectedPetugasContainer"></div>
                            </div>
                            <div class="form-group">
                                <label for="petugasSelect">Pilih Petugas:</label>
                                <select id="petugasSelect" class="form-control">
                                    <option value="">Pilih Petugas</option>
                                    @foreach ($petugas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
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
                        <a href="/pasar/delete/{{ $item->id }}" class="btn btn-danger delete-button">Delete</a>
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
            $('.detail-button').click(function() {
                var jsonData = $(this).data('jsondata');
                var petugasNames = jsonData.petugas.map(function(petugas) {
                    return petugas.nama;
                });

                var petugasString = petugasNames.join(', ');
                $('#modal-property1').text(jsonData.nama_pasar);
                $('#modal-property2').text(jsonData.kode_pasar);
                $('#modal-property3').text(jsonData.alamat);
                $('#modal-property4').text(jsonData.koordinat);
                $('#modal-property5').text(jsonData.luas_lahan);
                $('#modal-property6').text(jsonData.tahun_berdiri);
                $('#modal-property7').text(jsonData.tahun_pembangunan);
                $('#modal-property8').text(jsonData.kondisi_pasar);
                $('#modal-property9').text(jsonData.pengelola);
                $('#modal-property10').text(jsonData.operasional_pasar);
                $('#modal-property11').text(jsonData.jumlah_pedagang);
                $('#modal-property12').text(jsonData.omzet_perbulan);
                $('#modal-property13').text(jsonData.kelompok_pasar);
                $('#modal-property14').text(petugasString);
            });
        });

        $(document).ready(function() {
            $('.edit-button').click(function() {
                var jsonData = $(this).data('jsondata');
                var petugasNames = jsonData.petugas.map(function(petugas) {
                    return petugas.nama;
                });


                $('#nama').val(jsonData.nama_pasar);
                $('#kode').val(jsonData.kode_pasar);
                $('#alamat').val(jsonData.alamat);
                $('#koordinat').val(jsonData.koordinat);
                $('#luas').val(jsonData.luas_lahan);
                $('#berdiri').val(jsonData.tahun_berdiri);
                $('#pembangunan').val(jsonData.tahun_pembangunan);
                $('#kondisi').val(jsonData.kondisi_pasar);
                $('#pengelola').val(jsonData.pengelola);
                $('#operasional').val(jsonData.operasional_pasar);
                $('#jumlah').val(jsonData.jumlah_pedagang);
                $('#omzet').val(jsonData.omzet_perbulan);
                $('#kelompok').val(jsonData.kelompok_pasar);

                var updateForm = $('#editForm');
                var actionUrl = '/pasar/update/' + jsonData.id;

                updateForm.attr('action', actionUrl);
            });
        });



        $(document).ready(function() {
            var selectedPetugasIds = []; // Array to store the selected petugas IDs

            $('#petugasSelect').change(function() {
                var selectedPetugasId = $(this).val();
                var selectedPetugasName = $(this).find(':selected').text();
                if (selectedPetugasId) {
                    var selectedPetugasElement = '<div class="selected-petugas-item">' +
                        '<input type="hidden" name="selectedPetugas[]" value="' + selectedPetugasId + '">' +
                        '<span class="petugas-name">' + selectedPetugasName + '</span>' +
                        '<i class="fas fa-times remove-petugas"></i>' +
                        '</div>';

                    $('#selectedPetugasContainer').append(selectedPetugasElement);
                    selectedPetugasIds.push(selectedPetugasId); // Add the selected petugas ID to the array

                    $(this).find('option[value="' + selectedPetugasId + '"]')
                        .remove(); // Remove the selected petugas from the select option
                }

            });

            $('#selectedPetugasContainer').on('click', '.remove-petugas', function() {
                var selectedPetugasId = $(this).siblings('input[name="selectedPetugas[]"]').val();
                $(this).parent().remove();
                var index = selectedPetugasIds.indexOf(selectedPetugasId);
                if (index !== -1) {
                    selectedPetugasIds.splice(index, 1); // Remove the deleted petugas ID from the array
                    $('#petugasSelect').append('<option value="' + selectedPetugasId + '">' + $(this)
                        .siblings('.petugas-name').text() + '</option>'
                    ); // Add the removed petugas back to the select option
                }
            });

        });



        $(document).ready(function() {
            $('#myModalDelete').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var pasarId = button.data('pasar-id'); // Retrieve the value of data-pasar-id

                var modal = $(this);
                var deleteButton = modal.find('.delete-button'); // Get the delete button inside the modal

                // Update the href with the pasarId value
                deleteButton.attr('href', '/pasar/delete/' + pasarId);
            });
        });
    </script>

@endsection
