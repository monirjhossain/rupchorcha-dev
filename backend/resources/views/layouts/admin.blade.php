<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin Dashboard</title>
    <!-- Custom fonts for this template-->
    <link href="{{ asset('adminpanel/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{ asset('adminpanel/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<style>
    html, body, #wrapper {
        height: 100%;
        min-height: 100vh;
    }
    #wrapper {
        display: flex !important;
        min-height: 100vh;
    }
    #content-wrapper {
        flex: 1 1 0%;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    #content {
        flex: 1 1 0%;
    }
    .sidebar-sticky {
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        height: 100vh;
        min-height: 100vh;
        z-index: 1030;
        overflow-y: auto;
    }
    #wrapper {
        padding-left: 250px;
    }
    @media (max-width: 991.98px) {
        .sidebar-sticky {
            position: static;
            width: 100%;
            height: auto;
            min-height: 0;
        }
        #wrapper {
            padding-left: 0;
        }
    }
</style>
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper" class="d-flex min-vh-100">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column flex-grow-1 min-vh-100">
            <!-- Main Content -->
            <div id="content" class="flex-grow-1">
                <!-- Topbar -->
                @include('layouts.partials.topbar')
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            @include('layouts.partials.footer')
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('adminpanel/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminpanel/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{ asset('adminpanel/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('adminpanel/js/sb-admin-2.min.js') }}"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @stack('scripts')
    <script>
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif
        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif
        @if(session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif
        @if(session('info'))
            toastr.info("{{ session('info') }}");
        @endif
    </script>
</body>
</html>
