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
        <nav aria-label="breadcrumb">
        </nav>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table mr-1"></i>
                Rekonsiliasi Wajib Retribusi
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
                        <div class="col-md-12">
                            <div class="d-flex align-items-center pb-4">
                                <label class="m-0 p-4" for="">Rentang Tanggal :</label>
                                <div class="d-flex align-items-center pr-4">
                                    <input type="date" class="form-control p-4">
                                    <label class="m-0 p-4" for="">-</label>
                                    <input type="date" class="form-control p-4">
                                </div>
                                <button class="btn btn-info px-4">Terapkan</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th align="center">TANGGAL</th>
                                <th align="center">WAJIB RETRIBUSI</th>
                                <th align="center">UNIT</th>
                                <th align="center">SYSTEM</th>
                                <th align="center">REAL</th>
                                <th align="center">SELISIH</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($depo->isEmpty())
                            <tr>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>Rp 0</td>
                                <td>Rp 0</td>
                                <td>Rp 0</td>
                            </tr>
                            @else
                            @foreach ($wajibr->unique('wajib_retribusi_id') as $wj)
                            <?php $totalRetri = 0; ?>
                            @foreach ($manda->where('wajib_retribusi_id',$wj->wajib_retribusi_id) as $item)
                            <?php $total = 0; ?>
                            <tr>
                                @foreach ($depo as $dt)
                                <td>{{$dt->tanggal_penyetoran}}</td>
                                @endforeach
                                </td>
                                <td>{{$wj->nama}}</td>
                                <td>{{$wj->no_unit}}</td>
                                @foreach ($depo as $dt)
                                <?php $total += $dt->jumlah_setoran;  ?>
                                @endforeach
                                <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                                <?php $totalRetri += $item->total_retribusi;  ?>
                                <td>Rp. {{ number_format($totalRetri, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($total - $totalRetri, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            @endforeach
                            @endif
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