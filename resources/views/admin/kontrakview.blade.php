<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>
    <style>
        .contract-letter {
            padding: 10px;
            width: 2480px;
            /* height: 3508px; */
        }

        .head {
            display: flex;
        }

        .title {
            margin-left: 20px;
        }

        .signature {
            display: flex
        }

        .signature .kadis {
            margin-left: auto
        }

        .letter-body {
            font-size: 12px;
        }
    </style>
    <div>
        <a @click.prevent="printme()" href="route('surat')"><button>eksport</button></a>
    </div>
    <div class="head justify-content-center contaiiner" style="">
        <div class="p-3 py-5 container contract-letter" style="border: 1px solid black;">


            <div class="row letter-head">
                <div class="col-md-3">
                    <div class="container" style="max-width: 120px">
                        <img class="" src=""{{ asset('img/logo.png') }}"" alt=""
                            style="width: 100%;">
                    </div>
                </div>
                <div class="col-md-7 mt-3">
                    <div class="title">
                        @foreach ($kontrak as $item)
                        {{-- <h4 class="container" style="font-weight: bolder">{{$kontrak->judul}}</h4> --}}
                        <h4 class="container" style="font-weight: bolder">{{$item->judul}}</h4>
                        <div class="container">Perda No.8 Tahun 2018</div>
                        @endforeach
                    </div>
                </div>
            </div><br><br><br>

            <div class="letter-body">
                <div class="container col-md-10">
                    <p>Yang bertanda tangan dibawah ini : </p>
                    <table class="" cellpadding="3">
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            @foreach ($kontrak as $item)
                            <td>{{$item->nama}}</td>
                            @endforeach
                            
                        </tr>
                        <tr>
                            <td>Instansi</td>
                            <td>:</td>
                            <td>DINAS KOPERASI PERDAGANGAN DAN TENAGA KERJA KABUPATEN HUMBANG HASUNDUTAN</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{Kepala Dinas}</td>
                        </tr>
                    </table>
                    <p class="mt-2">Dalam hal ini bertindak atas nama diri pribadi yang selanjutnya disebut PIHAK
                        PERTAMA</p>
                </div>
                <div class="mt-4 col-md-10 container">
                    <table class="" cellpadding="3">
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            @foreach ($kontrak as $item)
                            <td>{{$item->kepala_dinas}}</td> 
                            @endforeach
                        </tr>
                        <tr>
                            <td>Instansi</td>
                            <td>:</td>
                            @foreach ($kontrak as $item)
                            <td>DINAS KOPERASI PERDAGANGAN DAN TENAGA KERJA KABUPATEN {{ strtoupper($item->kabupaten) }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>Kepala Dinas</td>
                        </tr>
                    </table>
                    <p class="mt-2">Dalam hal ini bertindak atas nama diri pribadi yang selanjutnya disebut PIHAK
                        KEDUA</p>
                </div>
                <div class="mt-4 col-md-10 container">
                    <p>Dimana pihak kedua sepakat untuk menyewa bangunan</p>
                    <table class="" cellpadding="3">

                        <tr>
                            <td>Nomor Kios</td>
                            <td>:</td>
                            @foreach ($kontrak as $item)
                            <td>{{ $item->no_unit }}</td>
                            @endforeach
                            
                        </tr>
                        <tr>
                            <td>Jenis Bangunan</td>
                            <td>:</td>
                            @foreach ($kontrak as $item)
                            <td>{{ $item->jenis_dan_ukuran }}</td>
                            @endforeach
                            
                        </tr>
                        <tr>
                            <td>Berlokasi di</td>
                            <td>:</td>
                            @foreach ($kontrak as $item)
                            <td>{{ $item->blok }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Durasi Sewa</td>
                            <td>:</td>
                            @foreach ($kontrak as $item)
                            <td>{{ Carbon\Carbon::parse($item   ->tanggal_mulai)->format('d F y') }} -
                                {{ Carbon\Carbon::parse($item   ->tanggal_selesai)->format('d F y') }} </td>
                                @endforeach

                        </tr>
                        <tr>
                            <td>Biaya Sewa</td>
                            <td>:</td>
                            <td>Rp. 50.000 / bulan</td>
                        </tr>
                    </table>

                    <div class="mt-4" class="w-100">
                        @foreach ($kontrak as $item)
                        <div>
                            {!! $item->detail !!}
                        </div>
                        @endforeach
                       
                    </div>
                </div>


                <div class="mt-5 row"">
                    <div class="col-md-4 container">
                        <p>.</p>
                        <div class="mb-3 container">{!! DNS2D::getBarcodeHTML('{{ $kontrak->no_kontrak }}', 'QRCODE', 5, 5) !!}</div>
                        @foreach ($kontrak as $item)
                        <p>{{ $item->nama }}</p>
                        @endforeach
                    </div>
                    <div class="kadis col-md-4 container">
                        <p>Humbang Hasundutan, 22 Agustus 2022</p>
                        <div class="mb-3 container">{!! DNS2D::getBarcodeHTML('{{ $kontrak->kadis }}', 'QRCODE', 5, 5) !!}</div>
                        @foreach ($kontrak as $item)
                        <p>{{ $item->kepala_dinas }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- <script>
            printme(){
                window.print();
            }
        </script> --}}
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
            </script>
</body>

</html>
