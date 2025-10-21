<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="">
    <meta name="author" content="Md. Imranul Islam">

    <title>PrintR | @yield('title')</title>

    <!-- Custom fonts for this template-->
    <link href="{{ url('admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ url('admin/css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="{{ url('admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/plug-ins/1.10.11/features/searchHighlight/dataTables.searchHighlight.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <link href="{{ url('admin/css/custom.css') }}" rel="stylesheet">
    <link rel="icon" href="{{asset('admin/printden_main.svg')}}" type="image/gif" sizes="16x16">
    @stack('styles')
</head>

<body id="page-top">
    <div id="wrapper">
        @include('admin.layout.sidebar')
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                {{-- @include('admin.layout.topbar') --}}
                <div class="container-fluid pt-5">

                    @yield('content')
                </div>
            </div>
            {{-- @include('admin.layout.footer') --}}
            
        </div>
    </div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" href="{{ route('admin.logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ url('admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('admin/vendor/jquery/popper.min.js') }}"></script>
    <script src="{{ url('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ url('admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ url('admin/js/sb-admin-2.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ url('admin/vendor/chart.js/Chart.min.js') }}"></script> 
    <script src="{{ url('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.11/features/searchHighlight/dataTables.searchHighlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xregexp/3.1.1/xregexp-all.min.js"></script>
    <script src="https://bartaz.github.io/sandbox.js/jquery.highlight.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>


    <!-- Page level custom scripts -->
    
    {{-- <script src="{{ url('admin/js/chart/datalables.js') }}"></script> --}}
    <script src="{{ url('admin/js/sweetAlert2.js') }}"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="{{ url('admin/js/custom.js') }}"></script>

    @stack('scripts')
    

</body>

</html>