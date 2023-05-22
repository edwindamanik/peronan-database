<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PeROnan</title>


    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('template/template-admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('template/template-admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('template/template-admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('template/template-admin/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/template-admin/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('template/template-admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('template/template-admin/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('template/template-admin/plugins/summernote/summernote-bs4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('css/select2.min.css')}}"/>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css"
          id="theme-styles">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">


    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous"/>
    <link rel="stylesheet"  href="{{asset('text-editor/rte_theme_default.css')}}" />
    <script type="text/javascript" src="{{asset('text-editor/rte.js')}}"></script>
    {{-- <script type="text/javascript" src="{{asset('text-editor/plugins/all_plugins.js')}}"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.5.0/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        trix-toolbar [data-trix-button-group="file-tools"] {
            display: none;
        }
    trix-editor {
		height: 250px !important;
		max-height: 150px !important;
	  	overflow-y: auto !important;
	}
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand " style = "background-color:#192C58;">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">

            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-header">{{ Auth::user()->nama }}</span >
                    <div class="dropdown-divider"></div>
                    <a href="{{route('logout')}}"
                       class="dropdown-item dropdown-footer"
                       onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();"
                       style="color: red"
                    >Logout</a>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar elevation-4" style = "background-color:#243763;">
        <!-- Brand Logo -->
        <a href="#" class="brand-link">
            <img src="{{asset('/source-image/logo-footer.png')}}" alt="SIAPPARA" class="brand-image " style="opacity: .8" style="position:absolute; left:5%"><br>
            <span class="brand-text font-weight-light" style="color: white; font-size: 18px;"></span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar" >

            <!-- Sidebar Menu -->
            <nav >
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" class="mt-2" style = "style="color:blue;">
                    <li class="nav-header">MENU</li>
                    @if (Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-poll"></i>
                                <p>
                                    Data Master
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/pasar" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Pasar</p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="/grup-pasar" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Grup Pasar</p>
                                    </a>
                                </li> --}}
                                <li class="nav-item">
                                    <a href="/kelompok-pasar" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Kelompok Pasar</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    {{-- <a href="{{route('jenis-tempat.index')}}" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Jenis Unit</p>
                                    </a> --}}
                                </li>
                                <li class="nav-item">
                                    {{-- <a href="{{route('unit-tempat.index')}}" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Unit Tempat</p>
                                    </a> --}}
                                </li>
                                <li class="nav-item">
                                    {{-- <a href="{{route('biaya-retribusi.index')}}" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Biaya Retribusi</p>
                                    </a> --}}
                                </li>
                                <li class="nav-item">
                                    {{-- <a href="{{route('penyewa.index')}}" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Penyewa</p>
                                    </a> --}}
                                </li>
                                <li class="nav-item">
                                    {{-- <a href="/kontrak" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Kontrak</p>
                                    </a> --}}
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="/user" class="nav-link">
                                <i class="nav-icon fas fa-user-circle"></i>
                                <p>Manajemen Pengguna</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/backup" class="nav-link">
                                <i class="nav-icon far fa-file-archive"></i>
                                <p>Unduh Pencadangan</p>
                            </a>
                        </li>

                    @else
                         
                    @if (Auth::user()->role == 'ADMINTOBA')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-poll"></i>
                                <p>
                                    Data Master
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/pasar/indextoba" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Pasar</p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="/grup-pasar" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Grup Pasar</p>
                                    </a>
                                </li> --}}
                                <li class="nav-item">
                                    <a href="/pasar/tipe" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Kelompok Pasar</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('jenis-tempat.index')}}" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Jenis Unit</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('unit-tempat.index')}}" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Unit Tempat</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('biaya-retribusi.index')}}" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Biaya Retribusi</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('penyewa.index')}}" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Penyewa</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/kontrak" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Kontrak</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="/user" class="nav-link">
                                <i class="nav-icon fas fa-user-circle"></i>
                                <p>Manajemen Pengguna</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/backup" class="nav-link">
                                <i class="nav-icon far fa-file-archive"></i>
                                <p>Unduh Pencadangan</p>
                            </a>
                        </li>

                    @endif

                    @if (Auth::user()->role == 'ADMINTAPUT')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-poll"></i>
                                <p>
                                    Data Master
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/pasar" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Pasar</p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="/grup-pasar" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Grup Pasar</p>
                                    </a>
                                </li> --}}
                                <li class="nav-item">
                                    <a href="/pasar/tipe" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Kelompok Pasar</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('jenis-tempat.index')}}" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Jenis Unit</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('unit-tempat.index')}}" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Unit Tempat</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('biaya-retribusi.index')}}" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Biaya Retribusi</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('penyewa.index')}}" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Penyewa</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/kontrak" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Data Kontrak</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="/user" class="nav-link">
                                <i class="nav-icon fas fa-user-circle"></i>
                                <p>Manajemen Pengguna</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/backup" class="nav-link">
                                <i class="nav-icon far fa-file-archive"></i>
                                <p>Unduh Pencadangan</p>
                            </a>
                        </li>

                    @endif

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    Penyetoran
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @if(Auth::user()->role == 'BENDAHARA')
                                <li class="nav-item">
                                    {{-- <a href="{{route('penyetoran-request.index')}}" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Konfirmasi Penyetoran</p>
                                    </a> --}}
                                </li>
                                @endif
                                <li class="nav-item">
                                    {{-- <a href="{{route('penyetoran.index')}}" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Laporan Penyetoran</p>
                                    </a> --}}
                                </li>

                                <li class="nav-item">
                                    {{-- <a href="{{route('tagihan-header.index')}}" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Laporan Tagihan</p>
                                    </a> --}}
                                </li>

                                <li class="nav-item">
                                    {{-- <a href="/rekonsiliasi/" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Rekonsiliasi</p>
                                    </a> --}}
                                </li>
                            </ul>
                        </li>


                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-sticky-note"></i>
                                <p>
                                    Bukti Bayar
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @if(Auth::user()->role == 'BENDAHARA')
                                <li class="nav-item">
                                    <a href="/receipt/request/pembatalan" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Konfirmasi Pembatalan</p>
                                    </a>
                                </li>
                                @endif
                                <li class="nav-item">
                                    <a href="/receipt/pembatalan" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Laporan Pembatalan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/receipt/laporan" class="nav-link">
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <p>Laporan Bukti Bayar</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="/UserManualSiappara.pdf" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Unduh User Manual</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        
        @if (request()->is('/'))
            Halaman Administrator
        @else
            @yield('content')
        @endif

    <!-- /.content-wrapper -->
    </div>

    <footer class="main-footer" style="margin-top: 20%; display:none;">
        <span class="text-muted">Copyright &copy; <?php echo date("Y"); ?> | Dinas Koperasi, Perdagangan Dan Industri Kabupaten ....</span>
    </footer>

</div>
<!-- ./wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

<!-- jQuery -->
<script src="{{ asset('template/template-admin/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('template/template-admin/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('template/template-admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('template/template-admin/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('template/template-admin/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('template/template-admin/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('template/template-admin/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('template/template-admin/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('template/template-admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('template/template-admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('template/template-admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('template/template-admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('template/template-admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('template/template-admin/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('template/template-admin/dist/js/demo.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('template/template-admin/dist/js/pages/dashboard.js') }}"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.5.0/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('template/assets/demo/datatables-demo.js') }}"></script>
<!-- Select2  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@stack('select2')
@stack('select_posisi')

{{--perbaikan-julio--}}
@if(Session::has('setoran_confirm'))
    <script>
        Swal.fire({
            title: "Pemberitahuan",
            text: "Setoran berhasil dikonfirmasi",
            icon: 'success',
            type: "success",
        });
    </script>
    {{ Session::forget('setoran_confirm') }}
@endif

@if(Session::has('user_created'))
    <script>
        Swal.fire({
            title: "Pemberitahuan",
            text: "User berhasil di tambahkan",
            icon: 'success',
            type: "success",
        });
    </script>
    {{ Session::forget('user_created') }}
@endif

@if(Session::has('user_deleted'))
    <script>
        Swal.fire({
            title: "Pemberitahuan",
            text: "User berhasil di hapus",
            icon: 'success',
            type: "success",
        });
    </script>
    {{ Session::forget('user_deleted') }}
@endif
@if(Session::has('user_updated'))
    <script>
        Swal.fire({
            title: "Pemberitahuan",
            text: "User berhasil di ubah",
            icon: 'success',
            type: "success",
        });
    </script>
    {{ Session::forget('user_updated') }}
@endif
{{--@if($message = Session::get('success'))
    <script>
        Swal.fire({
            title: "Pemberitahuan",
            text: "Export Harga Komoditi Berhasil",
            icon: 'success',
            type: "success",
        });
    </script>
    {{ Session::forget('success') }}
@endif--}}
@if(Session::has('success_edit'))
    <script>
        Swal.fire({
            title: "Pemberitahuan",
            text: "{{Session::get('success_edit')}}",
            icon: 'success',
            type: "success",
        });
    </script>
    {{ Session::forget('success_edit') }}
@endif
@if(Session::has('success_delete'))
    <script>
        Swal.fire({
            title: "Pemberitahuan",
            text: "{{Session::get('success_delete')}}",
            icon: 'success',
            type: "success",
        });
    </script>
    {{ Session::forget('success_delete') }}
@endif
@if(Session::has('success_insert'))
    <script>
        Swal.fire({
            title: "Pemberitahuan",
            text: "{{Session::get('success_insert')}}",
            icon: 'success',
            type: "success",
        });
    </script>
    {{ Session::forget('success_insert') }}
@endif
<script>
    // var editor1 = new RichTextEditor("#inp_editor1");
    //var editor2 = new RichTextEditor("#inp_editor2");
</script>
<script>
    // var editor1 = new RichTextEditor("#inp_editor1");
</script>
<script>
    function deleteUser(id) {
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus user ini?',
            showCancelButton: true,
            cancelButtonText: `Batal`,
            confirmButtonText: `Hapus`,
            icon: 'warning'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                window.location.href = `/user/delete/${id}`
            } else if (result.isDenied) {
                Swal.fire('Penghapusan User dibatalkan', '', 'info')
            }
        })
    }

    function deleteDataPasar(id) {
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus data pasar ini?',
            showCancelButton: true,
            cancelButtonText: `Batal`,
            confirmButtonText: `Hapus`,
            icon: 'warning'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                window.location.href = `/pasar/delete/${id}`
            } else if (result.isDenied) {
                Swal.fire('Penghapusan data pasar dibatalkan', '', 'info')
            }
        })
    }

    function deleteGrupPasar(id) {
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus grup pasar ini?',
            showCancelButton: true,
            confirmButtonText: `Hapus`,
            icon: 'warning'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                window.location.href = `/grup-pasar/delete/${id}`
            } else if (result.isDenied) {
                Swal.fire('Penghapusan grup pasar dibatalkan', '', 'info')
            }
        })
    }

    function deleteTipePasar(id) {
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus tipe pasar ini?',
            showCancelButton: true,
            cancelButtonText: `Batal`,
            confirmButtonText: `Hapus`,
            icon: 'warning'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                window.location.href = `/pasar/delete/tipe/${id}`
            } else if (result.isDenied) {
                Swal.fire('Penghapusan tipe pasar dibatalkan', '', 'info')
            }
        })
    }

    function deleteJenisTempat(id) {
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus jenis tempat ini?',
            showCancelButton: true,
            cancelButtonText: `Batal`,
            confirmButtonText: `Hapus`,
            icon: 'warning'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                window.location.href = `/tagihan/delete/jenis/${id}`
            } else if (result.isDenied) {
                Swal.fire('Penghapusan jenis tempat dibatalkan', '', 'info')
            }
        })
    }

    function deleteDetailJenisTempat(id) {
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus detail jenis tempat ini?',
            showCancelButton: true,
            cancelButtonText: `Batal`,
            confirmButtonText: `Hapus`,
            icon: 'warning'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                window.location.href = `/tagihan/detail/delete/jenis/${id}`
            } else if (result.isDenied) {
                Swal.fire('Penghapusan jenis tempat dibatalkan', '', 'info')
            }
        })
    }

    function deleteUnit(id) {
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus unit tempat ini?',
            showCancelButton: true,
            cancelButtonText: `Batal`,
            confirmButtonText: `Hapus`,
            icon: 'warning'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                window.location.href = `/tagihan/delete/unit/${id}`
            } else if (result.isDenied) {
                Swal.fire('Penghapusan unit tempat dibatalkan', '', 'info')
            }
        })
    }

    function deleteJenisBiaya(id) {
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus jenis biaya ini?',
            showCancelButton: true,
            cancelButtonText: `Batal`,
            confirmButtonText: `Hapus`,
            icon: 'warning'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                window.location.href = `/tagihan/delete/biaya/${id}`
            } else if (result.isDenied) {
                Swal.fire('Penghapusan jenis biaya dibatalkan', '', 'info')
            }
        })
    }

    function deleteKomoditi(id) {
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus komoditi ini?',
            showCancelButton: true,
            cancelButtonText: `Batal`,
            confirmButtonText: `Hapus`,
            icon: 'warning'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                window.location.href = `/komoditi/delete/daftar/${id}`
            } else if (result.isDenied) {
                Swal.fire('Penghapusan komoditi dibatalkan', '', 'info')
            }
        })
    }

    function confirmPembatalanReceipt(id) {
        Swal.fire({
            title: 'Anda yakin ingin melakukan konfirmasi?',
            showCancelButton: true,
            cancelButtonText: `Batal`,
            confirmButtonText: `Konfirmasi`,
            icon: 'warning'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                window.location.href = `/receipt/request/pembatalan/konfirmasi/${id}`
            } else if (result.isDenied) {
                Swal.fire('Konfirmasi dibatalkan', '', 'info')
            }
        })
    }

    function confirmPenyetoran(id) {
        Swal.fire({
            title: 'Pastikan jumlah setoran telah sesuai, anda yakin ingin melakukan konfirmasi?',
            showCancelButton: true,
            cancelButtonText: `Batal`,
            confirmButtonText: `Konfirmasi`,
            icon: 'warning'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                window.location.href = `/penyetoran/request/konfirmasi/${id}`
            } else if (result.isDenied) {
                Swal.fire('Konfirmasi dibatalkan', '', 'info')
            }
        })
    }

    function showEditUser(id) {
        window.location.href = `/user/edit/${id}`;
    }

    function deletePenyewa(id) {
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus Penyewa ini?',
            showCancelButton: true,
            cancelButtonText: `Batal`,
            confirmButtonText: `Hapus`,
            icon: 'warning'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                window.location.href = `/tagihan/delete/jenis/${id}`
            } else if (result.isDenied) {
                Swal.fire('Penghapusan Penyewa dibatalkan', '', 'info')
            }
        })
    }

</script>
</body>
</html>
