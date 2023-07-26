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
        <h2 class="mt-4">REKONSILIASI</h2>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table mr-1"></i>
                Rekonsiliasi Keuangan
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
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex align-items-center pb-4">
                                    <label class="m-0 p-4" for="">Rentang Tanggal :</label>
                                    <div class="d-flex align-items-center pr-4">
                                        <input type="date" class="form-control p-4" name="tanggalAwal" required>
                                        <label class="m-0 p-4" for="">-</label>
                                        <input type="date" class="form-control p-4" name="tanggalAkhir" required>
                                    </div>
                                    <button class="btn btn-info px-4" type="submit">Terapkan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead style="background-color: #B9B9B9">
                            <tr>
                                <th align="center"></th>
                                <th align="center">SYSTEM</th>
                                <th align="center">REAL</th>
                                <th align="center">SELISIH</th>
                                <th align="center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight: 800">CASH</td>
                                <td>Rp {{ number_format($totalCash, 0, ',', '.') }} ,-</td>
                                <td>Rp {{ number_format($depositCash->total_setoran, 0, ',', '.') }} ,-</td>
                                <td>Rp {{ number_format(abs($totalCash - $depositCash->total_setoran), 0, ',', '.') }} ,-</td>
                                <td><a href="/rekondetail/cash">Lihat detail selengkapnya</a></td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td style="font-weight: 800">VA</td>
                                <td>Rp {{ number_format($totalVa->total_setoran, 0, ',', '.') }} ,-</td>
                                <td>Rp {{ number_format($depositVa->total_setoran, 0, ',', '.') }} ,-</td>
                                <td>Rp {{ number_format(abs($totalVa->total_setoran - $depositVa->total_setoran), 0, ',', '.') }} ,-</td>
                                <td><a href="/rekondetail/va">Lihat detail selengkapnya</a></td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td style="font-weight: 800">QRIS</td>
                                <td>Rp 0</td>
                                <td>Rp 0</td>
                                <td>Rp 0</td>
                                <td><a href="#">Lihat detail selengkapnya</a></td>
                            </tr>
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
    <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalDeleteLabel" aria-hidden="true">
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