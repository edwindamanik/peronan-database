<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>PERORAN</title>

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.css"/>
--}}
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap-theme.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/fontAwesome.cs') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/hero-slider.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/owl-carousel.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/datepicker.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
    <link rel="stylesheet" href="css/fontAwesome.css">
    <link rel="stylesheet" href="css/hero-slider.css">
    <link rel="stylesheet" href="css/owl-carousel.css">
    <link rel="stylesheet" href="css/datepicker.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@300&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900" rel="stylesheet">

    <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>

    <!-- CSS & JS For Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.4/js/dataTables.select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <!-- -->

    <!-- CSS & JS For Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <!-- -->

    <!-- CSS & JS For DataPicker -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
    <!-- -->
    <style>
        <style>#gfg {
            margin: 3%;
            position: relative;
        }

        #first-txt {
            position: absolute;
            top: 17px;
            left: 50px;
            color: red;
        }
    </style>
    </style>
</head>

<style type="text/css">
    .dataTables_wrapper .dataTables_filter {
        float: right;
        text-align: right;
        visibility: hidden;
    }

    td.kpjsusulan {
        background-color: red;
    }

    td.kpj {
        background-color: green;
    }
</style>

<body style="font-family: Arial, Helvetica, sans-serif">

    <div class="wrap">
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <button id="primary-nav-button" type="button">Menu</button>
                        <a href="index.html">
                            <div class="logo">
                                <img src="img/logo1.png" width="220px" height="100px" alt="Venue Logo">
                            </div>
                        </a>
                        <nav id="primary-nav" class="dropdown cf">
                            <ul class="dropdown menu">
                                <li class='active'><a href="#">Beranda</a></li>
                                <li><a class="scrollTo" data-scrollTo="services" href="#">Tentang</a></li>
                                <li><a class="scrollTo" data-scrollTo="blog" href="#">Komoditi</a></li>
                                <li><a class="scrollTo" data-scrollTo="pricing-tables" href="#">Galeri</a></li>
                                <a href="/login" class="btn login"
                                    style="background-color: #243763; width: 100px; height: 30px; border: 2px solid;">Login</a
                                    </ul>

                        </nav><!-- / #primary-nav -->
                    </div>
                </div>
            </div>
        </header>
    </div>



    <section class="banner" id="top"
        style="background-image:url(img/gambarr.png);   width: 1535px; height: 500px; radius:20%; ">
        <div class="container">
            <div class="row">
                <div class="col-md-7 ">
                    <div class="banner-caption">
                        <div class="line-dec"></div>
                        <h2>PErOnan (Platform e-retribusi Onan)</h2>
                        <span align="justify">Aplikasi PerOnan digunakan untuk melakukan pembayaran retribusi pasar
                            secara elektronik</span>
                        <div class="blue-button">
                            <a class="scrollTo" data-scrollTo="popular" href="https://bit.ly/siappara">Unduh
                                Sekarang</a>
                        </div>
                    </div>

                </div>
                <div class="col-md-5 ">
                    <div class="banner-caption">
                        <img src="img/baner.png" alt="">
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="our-services" id="services">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading">
                        <h2>Apa itu PErOnan</h2>
                        <hr>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="down-services">
                        <div class="row">
                            <div class="col-md-5 col-md-offset-1">
                                <div class="left-content">
                                    <p align="justify">Aplikasi PErOnan (Platform e-retribusi Onan) adalah aplikasi
                                        yang
                                        dibangun untuk membantu melakukan administrasi dan penatausahaan retribusi pasar
                                        yang ada di kabupaten Humbang Hasundutan. Aplikasi yang dibangun berbasis web
                                        dan mobile. Aplikasi berbasis web digunakan oleh administrator dan bendahara,
                                        sedangkan aplikasi mobile digunakan oleh petugas pasar. Aplikasi ini digunakan
                                        di 12 pasar yang ada di kabupaten Humbang Hasundutan yang datanya terintegrasi
                                        ke dalam satu server. </p>

                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-1">
                                <div class="left-content">
                                    <p align="justify">Aplikasi PErOnan menjadi produk inovatif dalam pemungutan
                                        retribusi pasar secara yang digagas oleh Dinas Koperasi, Perdagangan Dan
                                        Industri Kabupaten Humbang Hasundutan. Pemungutan retribusi pasar menjadi lebih
                                        akuntabel karena seluruh proses dimulai dari pemungutan retribusi hingga
                                        pelaporan akan dilakukan secara elektronik. Inovasi ini diharapkan dapat
                                        menambah Pendapatan Asli Daerah (PAD) dari sektor pasar. </p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading">
                        <h2>Keuntungan Menggunakan aplikasi PErOnan</h2>

                        <hr>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 text-center">
                    <img src="img/aplikasi.png" height="467px" width="780px">
                </div>
                <div class="col-md-6 text-center" id="gfg" style="position: relative;">
                    <img src="img/biru.png" height="467px" width="700px">
                    <div
                        style="position: absolute; z-index: 20; top: 50%; transform: translateY(-50%); left: 1%; color: white; padding: 7px;">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td>
                                        <b>Mengontrol Jumlah Setoran Retribusi</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Membantu Dinas Koperasi, Perdagangan dan Perindustrian Kabupaten Humbang
                                        Hasundutan dalam mengontrol jumlah setoran retribusi pasar agar tidak terjadi
                                        penyimpangan setoran.
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Efisiensi</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Membantu penggunaan sumber daya yang digunakan baik tenaga, maupun material
                                        (karcis) yang kurang efisien.
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Transaparansi</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Membantu transparansi setoran retribusi pasar sehingga tidak menimbulkan
                                        keragu-raguan pedagang terhadap setoran retribusi pasar.
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Memudahkan pembayaran</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Memudahkan wajib retribusi untuk melakukan pembayaran secara tunai maupun
                                        non-tunai. Serta memantau history pembayaran serta tagihan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>





        </div>
    </section><br><br><br>




    <footer style="background-color: #243763">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="about-veno">
                        <div class="logo">
                            <img src="img/logo-footer.png" alt="Venue Logo" width="150">
                        </div>
                        <p>Kabupaten Humbang Hasundutan, Sumatra Utara, Indonesia</p>
                        <!-- <ul class="social-icons">
                            <li>
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-rss"></i></a>
                                <a href="#"><i class="fa fa-dribbble"></i></a>
                            </li>
                        </ul> -->
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="contact-info">
                        <div class="footer-heading">
                            <h4>Contact Information</h4>
                        </div>
                        <ul>
                            <li><span>PIC:</span><a href="#">Paber Colombus Simamora</a></li>
                            <li><span>Phone:</span><a href="#">+62 812 6307 3070</a></li>
                            <li><span>Email:</span><a href="#">kopedagin.humbanghasundutankab.go.id</a></li>
                            <li><span>Address:</span><a href="#">siappara.humbanghasundutankab.go.id</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="useful-links">
                        <div class="footer-heading">
                            <h4>Menu</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <ul>
                                    <li class='active'><a href="#">Beranda</a></li>
                                    <li><a class="scrollTo" data-scrollTo="services" href="#">Tentang</a></li>
                                    <li><a class="scrollTo" data-scrollTo="blog" href="#">Komoditi</a></li>
                                    <li><a class="scrollTo" data-scrollTo="pricing-tables" href="#">Galery</a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </footer>
    {{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
    <script>
    window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')
    </script>

    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('template/js/scripts.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('template/assets/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('template/assets/demo/chart-bar-demo.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.js"></script>
    <script src="{{ asset('template/assets/demo/datatables-demo.js') }}"></script>
    <script src="js/datepicker.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script> --}}

    <script type="text/javascript">
        $('#datatable_harga_komoditi thead tr:eq(0) th').each(function(index) {
            var col1 = [1];
            var col2 = [2];
            var col3 = [3];

            if (col1.includes(index)) {
                var title = $(this).text();
                $(this).html(title +
                    '<br><input type="text" id="komoditi" name="komoditi" style="width:100%;" class="form-control form-control-sm" placeholder="Search ' +
                    title + '" />');
            }
            if (col2.includes(index)) {
                var title = $(this).text();
                $(this).html(title +
                    '<br><input type="text" id="pasar" name="pasar" style="width:100%;" class="form-control form-control-sm" placeholder="Search ' +
                    title + '" />');
            }
            if (col3.includes(index)) {
                var title = $(this).text();
                $(this).html(title +
                    '<br><input type="text" id="harga" name="harga" style="width:100%;" class="form-control form-control-sm" placeholder="Search ' +
                    title + '" />');
            }



        });

        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {

                var komoditi = $('#komoditi').val();
                var array_komoditi = data[1];

                var pasar = $('#pasar').val();
                var array_pasar = data[2];

                var harga = $('#harga').val();
                var array_harga = data[3];

                var tempKomoditi = 0;
                var tempPasar = 0;
                var tempHarga = 0;

                if (array_komoditi.includes(komoditi)) {
                    tempKomoditi = 1;
                }
                if (array_pasar.includes(pasar)) {
                    tempPasar = 1;
                }
                if (array_harga.includes(harga)) {
                    tempHarga = 1;
                }

                if (tempKomoditi === 1 && tempPasar === 1 && tempHarga === 1) {
                    return true;
                }
                return false;

            }
        );

        var table = $('#datatable_harga_komoditi').DataTable({
            iDisplayLength: 10,
            bStateSave: false,
            lengthChange: false,
            ordering: true,
            info: false,
            //order:  [[4, "desc" ]],
            orderCellsTop: false,
            fixedHeader: true,
            processing: true,
            serverSide: false,
            //dom: 'Bfrtip',
            /*buttons: [
                {
                    align : 'right',
                    extend: 'excelHtml5',
                    footer: true,
                    text: '<i class="fa fa-file-excel-o"></i>  Download',
                    className: 'btn btn-outline-success rounded-pill mb-3',
                    title: 'DATA HARGA KOMODITI',

                }
            ],*/

            ajax: {
                url: '{{ url('data-index') }}',
            },

            columns: [{
                    "data": 'id',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },

                {
                    data: 'nama',
                    name: 'nama',
                    className: 'text-center'
                },
                {
                    data: 'nama_pasar',
                    name: 'nama_pasar',
                    className: 'text-center'
                },
                {
                    data: 'harga',
                    name: 'harga',
                    className: 'text-center'
                },
                {
                    data: 'tanggal',
                    name: 'tanggal',
                    className: 'text-right'
                },
            ],
            columnDefs: [{
                targets: [3],
                render: $.fn.dataTable.render.number(',', '.', 0),

            }, ],

        });

        $('#komoditi,#pasar,#harga').on('keyup change clear', function() {
            table.draw();
        });
    </script>
</body>

</html>
