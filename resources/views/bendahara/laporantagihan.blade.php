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
            <h2 class="mt-4">Daftar Bukti Bayar</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Daftar Tagihan</li>
                </ol>
            </nav>
            <button type="button" class="btn " data-toggle="modal" data-target="#myModalTambah"  style="background-color: #243763 color:white;">
                Export Laporan
            </button><br>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Daftar Bukti Bayar
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
                                    <th>Nomor Bukti Bayar</th>
                                    <th>Pasar</th>
                                    <th>Petugas</th>
                                    <th>No Unit</th>
                                    <th>Retribusi</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ret as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->no_bukti_pembayaran }}</td>
                                        <td>{{ $item->nama_pasar }}</td>
                                        <td>{{ $item->officers }}</td>
                                        <td>Unit</td>
                                        <td>Rp {{ number_format($item->biaya_retribusi, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                        @if ($item->status == 1)
                                            <td>Sudah Dibayar</td>
                                        @else
                                            <td>Belum Dibayar</td>
                                        @endif
                                        {{-- <td>Rp {{ number_format($item->jumlah_setoran, 0, ',', '.') }},-</td>
                                <td>{{ $item->penyetoran_melalui }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_penyetoran)->format('d M Y') }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_disetor)->format('d M Y') }}
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
@endsection
