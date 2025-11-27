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
        }
    </style>

</head>

<body id="page-top">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-dark-blue sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center"
                href="{{ route('penduduk.index') }}">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SISTEM INFORMASI</div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item {{ request()->routeIs('penduduk.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('penduduk.index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Penduduk</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('mutasi.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('mutasi.index') }}">
                    <i class="fas fa-fw fa-exchange-alt"></i>
                    <span>Mutasi</span>
                </a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                            </a>
                        </li>
                    </ul>

                </nav>
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Sistem Informasi Manajemen {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
        </div>
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

</body>

</html>
