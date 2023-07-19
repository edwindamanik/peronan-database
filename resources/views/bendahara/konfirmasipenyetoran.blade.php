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
            <h2 class="mt-4">Daftar Permohonan Persetujuan Setoran</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Daftar Permohonan Persetujuan </li>
                </ol>
            </nav>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    
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

                        {{-- <form method="GET" action="{{ route('data.limit') }}">
                            <button type="submit" name="penyetoran_melalui" value="tunai" class="btn" style="background-color:#fcfcfc; color:rgb(0, 0, 0); position:absolute; top:10px; border:1px solid #000000;">Tunai</button>
                            <button type="submit" name="penyetoran_melalui" value="nontunai" class="btn" style="background-color:#ffffff; color:rgb(0, 0, 0); position:absolute; top:10px; left:100px; border:1px solid #000000;">Non Tunai</button><br><br>
                            <label for="limit">Jumlah Baris:</label>
                            <input type="number" name="limit" id="limit" min="1" max="100" value="{{ $limit }}">
                            <button type="submit" class="btn" style="background-color:#192C58; color:white;">Terapkan</button>
                        </form> --}}

                        <form method="GET" action="{{ route('data.limit') }}">
                            <button type="submit" name="penyetoran_melalui" value="tunai" class="btn" style="background-color:#fcfcfc; color:rgb(0, 0, 0); position:absolute; top:10px; border:1px solid #000000;">Tunai</button>
                            <button id="nonTunaiButton" type="button" class="btn" style="background-color:#ffffff; color:rgb(0, 0, 0); position:absolute; top:10px; left:100px; border:1px solid #000000;">Non Tunai</button>

                            <button id="vaButton" type="submit" name="penyetoran_melalui" value="nontunai-va" class="btn" style="background-color:#ffffff; color:rgb(0, 0, 0); position:absolute; top:55px;  border:1px solid #000000; display: none;">VA</button>
                            <button id="qrisButton" type="submit" name="penyetoran_melalui" value="nontunai-qris" class="btn" style="background-color:#ffffff; color:rgb(0, 0, 0); position:absolute; top:55px; left:100px; border:1px solid #000000; display: none;">QRIS</button>
                            
                            
                            <label for="limit">Jumlah Baris:</label>
                            <input type="number" name="limit" id="limit" min="1" max="100" value="{{ $limit }}">
                            <button type="submit" class="btn" style="background-color:#192C58; color:white;">Terapkan</button>
                          </form>
                          
                        
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pasar</th>
                                    <th>Petugas</th>
                                    <th>Jumlah Setoran</th>
                                    <th>Penyetoran Melalui</th>
                                    <th>Tanggal Disetor</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_pasar }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>Rp {{ number_format($item->jumlah_setoran, 0, ',', '.') }}</td>
                                        <td>{{ $item->penyetoran_melalui }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_disetor)->format('d M Y') }}</td>
                                        <td>
                                            @if ($item->status == 'belum_setor')
                                            {{-- <a href="#" data-toggle="modal" data-target="#myModalAlasan" class="alasan-button" data-jsondata="{{ json_encode($item) }}" style="text-decoration: underline; color:#243763;">Belum Disetor</a>
                                             --}}
                                             <a href type="submit" class=" alasan-button" style="background-color:#ffffff; color:rgb(45, 67, 211);" data-toggle="modal" data-target="#myModalAlasan" data-jsondata="{{ json_encode($item) }}">Belum Disetor</a>



                                            @elseif ($item->status == 'menunggu_konfirmasi')
                                                <p style=" color:#000000;">Menunggu</p>
                                            @else
                                                {{ $item->status }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->status == 'disetujui')
                                                <button type="button" disabled class="btn btn-block" style="background-color:#192C58; color:white;" disabled>Konfirmasi</button>
                                            @elseif($item->status == 'belum_setor')
                                                <button type="button" disabled class="btn btn-block" style="background-color:#192C58; color:white;" disabled>Konfirmasi</button>
                                            @else
                                                <button type="submit" class="btn btn-block detail-button" style="background-color:#192C58; color:white;" data-toggle="modal" data-target="#myModalDetail" data-jsondata="{{ json_encode($item) }}">Konfirmasi</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        {{-- <div class="d-flex justify-content-end">
                        {{ $data->appends(['search' => request()->input('search')])->links('pagination::bootstrap-4') }}
                </div> --}}
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
                        <h5 class="modal-title">Rincian Permohonan Persetujuan Setoran</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <tbody id="modal-table-body">
                                <tr>
                                    <th>Nama Pasar</th>
                                    <td id="modal-property1"></td>
                                </tr>
                                <tr>
                                    <th>Petugas</th>
                                    <td id="modal-property2"></td>
                                </tr>
                                <tr>
                                    <th>Jumlah Setoran</th>
                                    <td id="modal-property3"></td>
                                </tr>
                                <tr>
                                    <th>Penyetoran Melalui</th>
                                    <td id="modal-property4"></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Disetor</th>
                                    <td id="modal-property5"></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Penyetoran</th>
                                    <td id="modal-property6"></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td id="modal-property7"></td>
                                </tr>
                                <tr>
                                    <th>Bukti Penyetoran</th>
                                    <td><a href="#" class="image-link">Lihat Bukti Setoran</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <form action="{{ route('setor-deposit', ['depositId' => $item->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-block" style="background-color:#192C58; color:white;"
                                data-toggle="modal" data-target="#confirmationModal{{ $item->id }}">Konfirmasi</button>
                        </form>
                        <form action="{{ route('tolak-deposit', ['depositId' => $item->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-block btn-danger" style=" color:white;"
                                data-toggle="modal" data-target="#confirmationModal{{ $item->id }}">Tolak</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


         {{-- MODAL Alasan --}}
         <div class="modal fade" id="myModalAlasan">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal header -->
                    <div class="modal-header">
                        <h5 class="modal-title">Rincian Permohonan Persetujuan Setoran</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <tbody id="modal-table-body">
                                <tr>
                                    
                                    <td id="tiann"><input type="text" id="tiann"></td>
                                </tr>
                               
                            </tbody>
                        </table>
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
                        {{-- <a href="/remove-user/{{ $item->id }}" class="btn btn-danger delete-button">Delete</a> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="myModalKtp">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bukti</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <img src="" alt="Image" id="modalImage"
                                style="max-width: 100%; max-height: 400px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </main>
    <script>
        $(document).ready(function() {
            $('.detail-button').click(function() {
                var jsonData = $(this).data('jsondata');

                $('#modal-property1').text(jsonData.nama_pasar);
                $('#modal-property2').text(jsonData.nama);
                $('#modal-property3').text(jsonData.jumlah_setoran);
                $('#modal-property4').text(jsonData.penyetoran_melalui);
                $('#modal-property5').text(jsonData.tanggal_disetor);
                $('#modal-property6').text(jsonData.tanggal_penyetoran);
                $('#modal-property7').text(jsonData.status);
                $('#modal-property8').text(jsonData.bukti_setoran);
                $('.image-link').attr('data-url', jsonData.bukti_setoran);
            });
        });

       


        

        $(document).ready(function() {
            $('.image-link').click(function(e) {
                e.preventDefault();
                var jsonData = $(this).data('jsondata');
                var imageUrl = 'http://127.0.0.1:8000/images/' + $(this).data('url');
                $('#modalImage').attr('src', imageUrl);
                $('#myModalKtp').modal('show');
            });
        });
    </script>
    <script>
        document.getElementById('nonTunaiButton').addEventListener('click', function() {
          document.getElementById('vaButton').style.display = 'inline-block';
          document.getElementById('qrisButton').style.display = 'inline-block';
        });
      </script>
      

<script>
    $(document).ready(function() {
       $('.alasan-button').click(function() {
          var jsonData = $(this).data('jsondata');
          $('#tiann').text(jsonData.alasan_tidak_setor);
       });
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
    </script>

    <script>
        document.getElementById('id="btnKonfirmasi{{ $item->id }}"').addEventListener('click', function() {
            // Ambil ID deposit dari tombol yang diklik
            var depositId = {{ $item->id }};

            // Kirim permintaan AJAX untuk mengubah status deposit
            axios.post('{{ route('setor-deposit', ['depositId' => $item->id]) }}')
                .then(function(response) {
                    // Tampilkan pesan berhasil
                    alert(response.data.success);
                    // Refresh halaman
                    location.reload();
                })
                .catch(function(error) {
                    // Tampilkan pesan gagal
                    alert(error.response.data.error);
                });
        });
    </script>
@endsection
