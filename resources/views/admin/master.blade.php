<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/AdminLTE.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/_all-skins.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.min.css
    " rel="stylesheet">

    <!-- Thêm jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
     <!-- Thêm jQuery Toast Plugin -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('backend_area/assets/images/favicon.ico') }}">
    <!-- DataTables -->
    {{-- <link href="{{ asset('backend_area/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend_area/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" /> --}}

    <!-- Select datatable -->
    {{-- <link href="{{ asset('backend_area/assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" /> --}}

    <link href="{{ asset('backend_area/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Sweet Alert-->
    <link href="{{ asset('backend_area/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"
        type="text/css" />
    <!-- Responsive datatable -->
    <link href="{{ asset('backend_area/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />

    <!-- Bootstrap Css -->

    {{--Dòng chỉnh Bootstrap của web vể phiên bản cũ --}}

    {{-- <link href="{{ asset('backend_area/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
        type="text/css" /> --}}

    {{-- Dòng chỉnh Bootstrap của web vể phiên bản cũ --}}

    <!-- Icons Css -->
    <link href="{{ asset('backend_area/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    {{-- Chỗ chỉnh sữa cái toartr thông báo xóa --}}
    <link href="{{ asset('backend_area/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <!-- Thư viện Bootstrap Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" rel="stylesheet">


    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --}}

        {{-- <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css"
    integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> --}}

        <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <script src="{{ asset('assets') }}/js/angular.min.js"></script>
    <script src="{{ asset('assets') }}/js/app.js"></script>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        @include('admin.layouts.header')

        <!-- =============================================== -->

        <!-- Left side column. contains the sidebar -->
        @include('admin.layouts.menu')

        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="page-content" style="margin-left: 10px;">
                <div class="container-fluid">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <h1>
                            @yield('title-page')
                        </h1>
                    </section>

                    <!-- Main content -->
                    @yield('main-content')
                    <!-- /.content -->
                </div>
            </div>
        </div>
        <!-- /.content-wrapper -->

        {{-- <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 0.0.1
    </div>
    <strong>Copyright &copy; 2018 <a href="https://adminlte.io">TTPM_BKAP</a>.</strong>
  </footer> --}}

    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->

    <script src="{{ asset('assets') }}/js/jquery.min.js"></script>
    <script src="{{ asset('assets') }}/js/jquery-ui.js"></script>
    <script src="{{ asset('assets') }}/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets') }}/js/adminlte.min.js"></script>
    <script src="{{ asset('assets') }}/js/dashboard.js"></script>
    <script src="{{ asset('assets') }}/js/function.js"></script>

    {{-- <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script> --}}


    <script src="{{ asset('backend_area/assets/ckeditor/ckeditor.js') }}"></script>
    {{-- Thư biên JS BACKEND --}}
    <!-- JAVASCRIPT -->

    <script src="{{ asset('backend_area/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend_area/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend_area/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('backend_area/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend_area/assets/libs/node-waves/waves.min.js') }}"></script>

    <!-- Required datatable js -->
    {{-- <script src="{{ asset('backend_area/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend_area/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('backend_area/assets/libs/select2/js/select2.min.js') }}"></script> --}}

    <!-- Buttons examples -->
    {{-- <script src="{{ asset('backend_area/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend_area/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}">
    </script>
    <script src="{{ asset('backend_area/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('backend_area/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend_area/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script> --}}
    {{-- <script src="{{ asset('backend_area/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend_area/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend_area/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('backend_area/assets/libs/datatables.net-keyTable/js/dataTables.keyTable.min.html') }}"> --}}
    </script>
    {{-- <script src="{{ asset('backend_area/assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>
    <!-- apexcharts -->
    <script src="{{ asset('backend_area/assets/libs/apexcharts/apexcharts.min.js') }}"></script> --}}

    <!-- Responsive examples -->

    {{-- <script src="{{ asset('backend_area/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('backend_area/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
    </script> --}}

    <!-- Sweet Alerts js -->
    <script src="{{ asset('backend_area/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Sweet alert init js-->
    <script src="{{ asset('backend_area/assets/js/pages/sweet-alerts.init.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('backend_area/assets/js/pages/datatables.init.js') }}"></script>
    <!-- dashboard init -->
    <script src="{{ asset('backend_area/assets/js/pages/dashboard.init.js') }}"></script>


    {{-- Thư biên JS BACKEND --}}

</body>
<script src="
https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js
"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    @if (Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif

    @if (Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif

    @if (Session::has('warning'))
        toastr.warning("{{ Session::get('warning') }}");
    @endif

    @if (Session::has('info'))
        toastr.info("{{ Session::get('info') }}");
    @endif
</script>

</html>
