<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Edit Mutasi Penduduk</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset ('asset/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('asset/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Select2 CSS untuk searchable dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css" rel="stylesheet" />

    <style>
        /* Custom styles untuk form */
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            padding-top: 3px;
        }

        .mutasi-type-display {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
            text-align: center;
            display: inline-block;
        }

        .badge-lahir {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }

        .badge-meninggal {
            background: linear-gradient(45deg, #6c757d, #343a40);
            color: white;
        }

        .badge-datang {
            background: linear-gradient(45deg, #17a2b8, #007bff);
            color: white;
        }

        .badge-pindah {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
            color: white;
        }
    </style>
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
                        <h1 class="h3 mb-0 text-gray-800">Edit Mutasi Penduduk</h1>
                        <a href="{{ route('mutasi.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
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

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Perbaiki kesalahan berikut:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Content Row -->
                    <div class="row justify-content-center">
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold">Edit Data Mutasi</h6>
                                </div>
                                <div class="card-body">
                                    <!-- Informasi Mutasi Saat Ini -->
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Informasi Mutasi Saat Ini:</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Jenis Mutasi:</strong>
                                                <span class="mutasi-type-display ml-2
                                                    @switch($mutasi->jenis_mutasi)
                                                        @case('LAHIR')
                                                            badge-lahir
                                                            @break
                                                        @case('MENINGGAL')
                                                            badge-meninggal
                                                            @break
                                                        @case('DATANG')
                                                            badge-datang
                                                            @break
                                                        @case('PINDAH')
                                                            badge-pindah
                                                            @break
                                                    @endswitch">
                                                    {{ $mutasi->jenis_mutasi }}
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Tanggal Kejadian:</strong> {{ \Carbon\Carbon::parse($mutasi->tanggal_kejadian)->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        @if($mutasi->penduduk)
                                        <div class="mt-2">
                                            <strong>Penduduk:</strong> {{ $mutasi->penduduk->nama }} ({{ $mutasi->penduduk->nik }})
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Form Edit Mutasi -->
                                    <form action="{{ route('mutasi.update', $mutasi->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <!-- Data Penduduk -->
                                        <div class="form-group">
                                            <label for="penduduk_id"><i class="fas fa-user"></i> Penduduk</label>
                                            <select class="form-control select2" id="penduduk_id" name="penduduk_id" required>
                                                <option value="">-- Pilih Penduduk --</option>
                                                @foreach($penduduk as $p)
                                                    <option value="{{ $p->id }}"
                                                        {{ $mutasi->penduduk_id == $p->id ? 'selected' : '' }}>
                                                        {{ $p->nama }} - {{ $p->nik }}
                                                        @if($p->kartuKeluarga)
                                                            (KK: {{ $p->kartuKeluarga->no_kk }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('penduduk_id')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- NIK (Editable for Birth Mutation) -->
                                        <div class="form-group">
                                            <label for="nik"><i class="fas fa-id-card"></i> NIK (Edit jika perlu)</label>
                                            <input type="number" class="form-control" id="nik" name="nik"
                                                   value="{{ old('nik', $mutasi->penduduk->nik) }}"
                                                   placeholder="Masukkan NIK baru jika ingin mengubah">
                                            <small class="form-text text-muted">Mengubah NIK di sini akan memperbarui data NIK penduduk terkait.</small>
                                            @error('nik')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Jenis Mutasi -->
                                        <div class="form-group">
                                            <label for="jenis_mutasi"><i class="fas fa-exchange-alt"></i> Jenis Mutasi</label>
                                            <select class="form-control" id="jenis_mutasi" name="jenis_mutasi" required>
                                                <option value="">-- Pilih Jenis Mutasi --</option>
                                                <option value="LAHIR" {{ $mutasi->jenis_mutasi == 'LAHIR' ? 'selected' : '' }}>👶 Lahir</option>
                                                <option value="MENINGGAL" {{ $mutasi->jenis_mutasi == 'MENINGGAL' ? 'selected' : '' }}>⚰️ Meninggal</option>
                                                <option value="DATANG" {{ $mutasi->jenis_mutasi == 'DATANG' ? 'selected' : '' }}>🏠 Datang</option>
                                                <option value="PINDAH" {{ $mutasi->jenis_mutasi == 'PINDAH' ? 'selected' : '' }}>🚚 Pindah</option>
                                            </select>
                                            @error('jenis_mutasi')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Tanggal Kejadian -->
                                        <div class="form-group">
                                            <label for="tanggal_kejadian"><i class="fas fa-calendar"></i> Tanggal Kejadian</label>
                                            <input type="date" class="form-control" id="tanggal_kejadian"
                                                   name="tanggal_kejadian"
                                                   value="{{ \Carbon\Carbon::parse($mutasi->tanggal_kejadian)->format('Y-m-d') }}"
                                                   required>
                                            @error('tanggal_kejadian')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Lokasi Detail -->
                                        <div class="form-group">
                                            <label for="lokasi_detail"><i class="fas fa-map-marker-alt"></i> Lokasi Detail</label>
                                            <input type="text" class="form-control" id="lokasi_detail"
                                                   name="lokasi_detail"
                                                   value="{{ old('lokasi_detail', $mutasi->lokasi_detail) }}"
                                                   placeholder="Contoh: RSUD Dunda, Puskesmas, dll">
                                            @error('lokasi_detail')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Keterangan -->
                                        <div class="form-group">
                                            <label for="keterangan"><i class="fas fa-comment"></i> Keterangan</label>
                                            <textarea class="form-control" id="keterangan"
                                                      name="keterangan" rows="3"
                                                      placeholder="Tambahkan keterangan tambahan jika diperlukan">{{ old('keterangan', $mutasi->keterangan) }}</textarea>
                                            @error('keterangan')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Informasi Peringatan -->
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <strong>Perhatian:</strong> Perubahan pada data mutasi akan mempengaruhi catatan demografis penduduk. Pastikan data yang dimasukkan sudah benar.
                                        </div>

                                        <!-- Buttons -->
                                        <div class="form-group text-center mt-4">
                                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                                <i class="fas fa-save"></i> Simpan Perubahan
                                            </button>
                                            <a href="{{ route('mutasi.show', $mutasi->id) }}" class="btn btn-secondary btn-lg px-4 ml-2">
                                                <i class="fas fa-times"></i> Batal
                                            </a>
                                        </div>
                                    </form>
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

    <!-- Select2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('asset/js/sb-admin-2.min.js') }}"></script>

    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: '-- Pilih Penduduk --',
                allowClear: true,
                width: '100%'
            });

            // Format tanggal ke format Indonesia untuk display
            $('#tanggal_kejadian').on('change', function() {
                var selectedDate = new Date($(this).val());
                var options = { day: 'numeric', month: 'long', year: 'numeric' };
                var formattedDate = selectedDate.toLocaleDateString('id-ID', options);
                console.log('Tanggal dipilih:', formattedDate);
            });
        });
    </script>

</body>

</html>