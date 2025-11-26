<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Detail Mutasi Penduduk</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset ('asset/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('asset/css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('penduduk.index') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SISTEM INFORMASI</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('penduduk.index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Penduduk</span>
                </a>
            </li>

            <!-- Nav Item - Mutasi -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('mutasi.index') }}">
                    <i class="fas fa-fw fa-exchange-alt"></i>
                    <span>Mutasi</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Detail Mutasi Penduduk</h1>
                        <div>
                            <a href="{{ route('mutasi.edit', $mutasi->id) }}" class="btn btn-warning btn-sm mr-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('mutasi.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Mutasi Information -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Informasi Mutasi</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                            <a class="dropdown-item" href="{{ route('mutasi.edit', $mutasi->id) }}">Edit Data</a>
                                            <form action="{{ route('mutasi.destroy', $mutasi->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data mutasi ini?')">Hapus Data</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="text-gray-600 font-weight-bold">Jenis Mutasi</label>
                                                <div>
                                                    @switch($mutasi->jenis_mutasi)
                                                        @case('LAHIR')
                                                            <span class="badge badge-success p-2">👶 Lahir</span>
                                                            @break
                                                        @case('MATI')
                                                            <span class="badge badge-dark p-2">⚰️ Meninggal</span>
                                                            @break
                                                        @case('DATANG')
                                                            <span class="badge badge-info p-2">🏠 Datang</span>
                                                            @break
                                                        @case('PINDAH')
                                                            <span class="badge badge-warning p-2">🚚 Pindah</span>
                                                            @break
                                                        @default
                                                            <span class="badge badge-secondary p-2">{{ $mutasi->jenis_mutasi }}</span>
                                                    @endswitch
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="text-gray-600 font-weight-bold">Tanggal Kejadian</label>
                                                <div class="h5 text-gray-800">{{ \Carbon\Carbon::parse($mutasi->tanggal_kejadian)->format('d F Y') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="text-gray-600 font-weight-bold">Lokasi Detail</label>
                                                <div class="text-gray-800">{{ $mutasi->lokasi_detail ?: '-' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="text-gray-600 font-weight-bold">Tanggal Pencatatan</label>
                                                <div class="text-gray-800">{{ \Carbon\Carbon::parse($mutasi->created_at)->format('d F Y H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($mutasi->keterangan)
                                        <div class="mb-3">
                                            <label class="text-gray-600 font-weight-bold">Keterangan</label>
                                            <div class="text-gray-800 bg-light p-3 rounded">{{ $mutasi->keterangan }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Penduduk Information -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Data Penduduk</h6>
                                </div>
                                <div class="card-body">
                                    @if($mutasi->penduduk)
                                        <div class="text-center mb-3">
                                            <div class="icon-circle bg-primary">
                                                <i class="fas fa-user fa-2x text-white"></i>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="text-gray-600 font-weight-bold">Nama Lengkap</label>
                                            <div class="h5 text-gray-800">{{ $mutasi->penduduk->nama }}</div>
                                            @if($mutasi->penduduk->hubungan_keluarga == 'Kepala Keluarga')
                                                <span class="badge badge-primary">Kepala Keluarga</span>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <label class="text-gray-600 font-weight-bold">NIK</label>
                                            <div class="text-gray-800">{{ $mutasi->penduduk->nik }}</div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="text-gray-600 font-weight-bold">Jenis Kelamin</label>
                                                    <div class="text-gray-800">{{ $mutasi->penduduk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="text-gray-600 font-weight-bold">Usia</label>
                                                    <div class="text-gray-800">{{ $mutasi->penduduk->usia }} tahun</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="text-gray-600 font-weight-bold">Tempat Lahir</label>
                                                    <div class="text-gray-800">{{ $mutasi->penduduk->tempat_lahir }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="text-gray-600 font-weight-bold">Tanggal Lahir</label>
                                                    <div class="text-gray-800">{{ \Carbon\Carbon::parse($mutasi->penduduk->tgl_lahir)->format('d/m/Y') }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="text-gray-600 font-weight-bold">Pekerjaan</label>
                                            <div class="text-gray-800">{{ $mutasi->penduduk->pekerjaan }}</div>
                                        </div>

                                        @if($mutasi->penduduk->kartuKeluarga)
                                            <div class="mb-3">
                                                <label class="text-gray-600 font-weight-bold">Nomor KK</label>
                                                <div class="text-gray-800">{{ $mutasi->penduduk->kartuKeluarga->no_kk }}</div>
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <label class="text-gray-600 font-weight-bold">Status</label>
                                            <div>
                                                @switch($mutasi->penduduk->status)
                                                    @case('HIDUP')
                                                        <span class="badge badge-success">Hidup</span>
                                                        @break
                                                    @case('MENINGGAL')
                                                        <span class="badge badge-dark">Meninggal</span>
                                                        @break
                                                    @case('PINDAH')
                                                        <span class="badge badge-warning">Pindah</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-secondary">{{ $mutasi->penduduk->status }}</span>
                                                @endswitch
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-user-slash fa-3x text-gray-300 mb-3"></i>
                                            <h5 class="text-gray-500">Data Penduduk Tidak Ditemukan</h5>
                                            <p class="text-gray-400">Data penduduk untuk mutasi ini tidak tersedia.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

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
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
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

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('asset/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('asset/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('asset/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('asset/js/sb-admin-2.min.js') }}"></script>

</body>

</html>