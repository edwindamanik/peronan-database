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
            <h2 class="mt-4">Daftar Konfirmasi Pembatalan</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Daftar Konfirmasi Pembatalan</li>
                </ol>
            </nav>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Daftar Konfirmasi Pembatalan
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
                                    <th>No Bukti Bayar</th>
                                    <th>Pasar</th>
                                    <th>Petugas</th>
                                    <th>Tanggal </th>
                                    <th>Aksi </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->no_bukti_pembayaran }}</td>
                                        <td>{{ $item->nama_pasar }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>


                                        <td>
                                            <div class="d-flex">
                                                @if ($item->status == 'batal')
                                                    <button type="submit" class="btn  detail-button mr-2"
                                                        style="background-color:#192C58; color:white;" data-toggle="modal"
                                                        data-target="#myModalDetail"
                                                        data-jsondata="{{ json_encode($item) }}">Konfirmasi</button>
                                                @else
                                                    <button type="button" class="btn  mr-2"
                                                        style="background-color:#192C58; color:white;"
                                                        disabled>Konfirmasi</button>
                                                @endif
                                                <form action="{{ route('batalkan-kali', ['batalId' => $item->id]) }}"
                                                    method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn "
                                                        style="background-color:#b6a9a9; color:white;">Batal</button>
                                                </form>
                                            </div>
                                        </td>


                                        {{-- <td>
                                            @if ($item->status !== '0')
                                                <form action="{{ route('batalkan-tagihan', ['batalId' => $item->id]) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-block detail-button" style="background-color:#192C58; color:white; width: 100%;">Konfirmasi</button>
                                                </form>
                                        
                                                <form action="{{ route('batalkan-tagihan', ['batalId' => $item->id]) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-block" style="background-color:#d50202; color:white; width: 100%;">Batal</button>
                                                </form>
                                            @else
                                                Sudah Dibatalkan
                                            @endif
                                        </td> --}}

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
        {{-- MODAL Batal --}}
        <div class="modal fade" id="myModalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalDetailLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <img src="{{ URL::asset('source-image/x.png') }}" alt="" style="max-width: 100px;">
                        </div>
                        <div class="text-center">
                            <p class="font-weight-bold">Apakah Anda yakin?</p>
                            <p>Apakah Anda yakin untuk membatalkan konfirmasi Bukti Pembayaran?</p>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <form action="{{ route('batalkan-tagihan', ['batalId' => $item->id]) }}" method="POST"
                            style="display: inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-danger">YA</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
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
    </script>
    <script>
        $(document).ready(function() {
            $('.detail-button').click(function() {
                var jsonData = $(this).data('jsondata');

            });
        });
    </script>
@endsection
