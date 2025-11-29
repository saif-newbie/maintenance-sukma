<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title', 'Sistem Informasi')</title>

    <link href="{{ asset('asset/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="{{ asset('asset/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        /* =========================================
           1. CSS UNTUK LAYOUT FIX (Agar Rapi & Sticky)
           ========================================= */
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            /* KUNCI: Body diam */
        }

        #wrapper {
            display: flex;
            height: 100vh;
            /* Full Layar */
            overflow: hidden;
            width: 100%;
        }

        /* Sidebar dengan scroll sendiri */
        ul.navbar-nav.sidebar {
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            flex-shrink: 0;
        }

        /* Konten Kanan dengan scroll sendiri */
        #content-wrapper {
            flex: 1;
            height: 100vh;
            overflow-y: auto;
            /* Scroll konten ada di sini */
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Sembunyikan scrollbar sidebar agar cantik */
        ul.navbar-nav.sidebar::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }

        /* Responsive HP */
        @media (max-width: 768px) {

            html,
            body {
                overflow: auto;
                height: auto;
            }

            #wrapper {
                height: auto;
                overflow: visible;
            }

            ul.navbar-nav.sidebar {
                height: auto;
                min-height: 100vh;
            }

            #content-wrapper {
                height: auto;
                overflow-y: visible;
            }
        }

        /* =========================================
           2. CSS UNTUK TEMA BARU (Biru Tua Elegan)
           ========================================= */

        /* Warna Gradient Sidebar Baru */
        .bg-gradient-dark-blue {
            background-color: #1a237e;
            background-image: linear-gradient(180deg, #1a237e 10%, #283593 100%);
            background-size: cover;
        }

        /* Styling Brand/Logo */
        .sidebar-brand-text {
            font-weight: 700;
            letter-spacing: 1px;
        }

        .sidebar-brand-icon i {
            font-size: 1.5rem;
        }

        /* Styling Navbar Atas */
        nav.topbar {
            box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15) !important;
            height: auto !important; /* Allow expansion for mobile menu */
            min-height: 4.375rem; /* Maintain original minimum height */
        }

        /* Responsive Width Utility */
        @media (min-width: 992px) {
            .w-lg-auto {
                width: auto !important;
            }
        }
    </style>
    @stack('styles')

</head>

<body id="page-top">

    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand-lg navbar-dark bg-gradient-dark-blue topbar mb-4 static-top shadow">
                    <div class="container">
                        <!-- Sidebar Brand (Logo) -->
                        <a class="navbar-brand d-flex align-items-center justify-content-center" href="{{ route('penduduk.index') }}">
                            <div class="sidebar-brand-icon rotate-n-15 mr-2">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="sidebar-brand-text font-weight-bold">SISTEM INFORMASI</div>
                        </a>

                        <!-- Topbar Navbar Toggle (Mobile) -->
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <!-- Topbar Navbar (Links) -->
                        <!-- Topbar Navbar (Links & User Info) -->
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            
                            <!-- 1. User Profile (Top on Mobile, Right on Desktop) -->
                            <ul class="navbar-nav ml-auto align-items-center order-1 order-lg-2 mb-3 mb-lg-0">
                                <li class="nav-item d-flex align-items-center">
                                    <span class="mr-2 text-white small font-weight-bold">Admin</span>
                                    <img class="img-profile rounded-circle" src="{{ asset('asset/img/undraw_profile.svg') }}" style="height: 2rem; width: 2rem;" onerror="this.src='https://source.unsplash.com/QAB-WJcbgJk/60x60'">
                                </li>
                            </ul>

                            <!-- 2. Links (Middle on Mobile, Left on Desktop) -->
                            <ul class="navbar-nav mr-auto order-2 order-lg-1 mb-3 mb-lg-0">
                                <li class="nav-item {{ request()->routeIs('penduduk.*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('penduduk.index') }}">
                                        <i class="fas fa-fw fa-users mr-1"></i>
                                        <span>Penduduk</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->routeIs('mutasi.*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('mutasi.index') }}">
                                        <i class="fas fa-fw fa-exchange-alt mr-1"></i>
                                        <span>Mutasi</span>
                                    </a>
                                </li>
                            </ul>

                            <!-- 3. Logout Button (Bottom on Mobile, Right on Desktop) -->
                            <div class="d-flex align-items-center order-3 order-lg-3 ml-lg-3 w-100 w-lg-auto">
                                <a class="btn btn-danger btn-sm w-100 w-lg-auto" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-1"></i>
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Sistem Informasi Manajemen {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
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
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('asset/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('asset/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('asset/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <script src="{{ asset('asset/js/sb-admin-2.min.js') }}"></script>

    @yield('script')
    @stack('scripts')

</body>

</html>
