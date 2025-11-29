@extends('layouts.master')

@section('title', 'Data Mutasi Penduduk')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Mutasi Penduduk</h1>
            <div class="d-flex align-items-center">
                <!-- Filter Dusun -->
                <select id="filterDusun" class="form-control mr-2" style="width: 200px;">
                    <option value="">Semua Dusun</option>
                    @if(isset($availableDusuns))
                        @foreach($availableDusuns as $dusun)
                            <option value="{{ $dusun }}">{{ $dusun }}</option>
                        @endforeach
                    @endif
                </select>

                <a href="{{ route('mutasi.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Mutasi
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

        <!-- Statistics Cards - Moved to Top -->
        @if($mutasi->count() > 0)
            <div class="row">
                <!-- Total Mutasi Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        <i class="fas fa-chart-line"></i> Total Mutasi
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $mutasi->count() }}</div>
                                    <div class="text-xs text-gray-500">Semua Jenis Mutasi</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exchange-alt fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Births Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        <i class="fas fa-baby"></i> Kelahiran
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $mutasi->where('jenis_mutasi', 'LAHIR')->count() }}</div>
                                    <div class="text-xs text-gray-500">Data Lahir Baru</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-baby fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Moves/Arrivals Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        <i class="fas fa-arrows-alt"></i> Pindah/Datang
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $mutasi->whereIn('jenis_mutasi', ['PINDAH', 'DATANG'])->count() }}</div>
                                    <div class="text-xs text-gray-500">Pindah: {{ $mutasi->where('jenis_mutasi', 'PINDAH')->count() }} | Datang: {{ $mutasi->where('jenis_mutasi', 'DATANG')->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-arrows-alt fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deaths Card - Fixed Calculation -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        <i class="fas fa-cross"></i> Kematian
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $mutasi->where('jenis_mutasi', 'MENINGGAL')->count() }}</div>
                                    <div class="text-xs text-gray-500">Data Meninggal</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-cross fa-2x text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Statistics Row -->
            <div class="row mb-4">
                <!-- This Month's Statistics -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-calendar-alt"></i> Statistik Bulan Ini
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 text-center">
                                    <div class="h4 font-weight-bold text-success">
                                        {{ $mutasi->filter(function($item) {
                                            return $item->jenis_mutasi === 'LAHIR' &&
                                                   \Carbon\Carbon::parse($item->tanggal_kejadian)->month == now()->month;
                                        })->count() }}
                                    </div>
                                    <small class="text-muted">Lahir</small>
                                </div>
                                <div class="col-6 text-center">
                                    <div class="h4 font-weight-bold text-danger">
                                        {{ $mutasi->filter(function($item) {
                                            return $item->jenis_mutasi === 'MENINGGAL' &&
                                                   \Carbon\Carbon::parse($item->tanggal_kejadian)->month == now()->month;
                                        })->count() }}
                                    </div>
                                    <small class="text-muted">Meninggal</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gender Distribution -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-info">
                                <i class="fas fa-users"></i> Distribusi Jenis Kelamin
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 text-center">
                                    <div class="h4 font-weight-bold text-primary">
                                        {{ $mutasi->filter(function($item) {
                                            return $item->penduduk && $item->penduduk->jenis_kelamin === 'L';
                                        })->count() }}
                                    </div>
                                    <small class="text-muted">Laki-laki</small>
                                </div>
                                <div class="col-6 text-center">
                                    <div class="h4 font-weight-bold text-warning">
                                        {{ $mutasi->filter(function($item) {
                                            return $item->penduduk && $item->penduduk->jenis_kelamin === 'P';
                                        })->count() }}
                                    </div>
                                    <small class="text-muted">Perempuan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

            <!-- Content Row -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list"></i> Daftar Mutasi Penduduk
                </h6>
                @if($mutasi->count() > 0)
                    <div class="dropdown">
                        <a class="btn btn-sm btn-outline-primary dropdown-toggle" href="#" role="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-download"></i> Export
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <a class="dropdown-item" href="#"><i class="fas fa-file-excel"></i> Excel</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-print"></i> Cetak</a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-body">
                @if($mutasi->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="mutasiTable">
                            <thead class="table-primary">
                                <tr>
                                    <th style="width: 60px;" class="text-center align-middle">No</th>
                                    <th class="align-middle">Nama Penduduk</th>
                                    <th class="align-middle">NIK</th>
                                    <th class="align-middle">No. KK</th>
                                    <th class="align-middle">Jenis Mutasi</th>
                                    <th class="align-middle">Tanggal Kejadian</th>
                                    <th class="align-middle">Lokasi Detail</th>
                                    <th class="align-middle">Keterangan</th>
                                    <th style="width: 120px;" class="text-center align-middle">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="mutasiTableBody">
                                @include('partials.mutasi_table')
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-exchange-alt fa-4x text-gray-300 mb-3"></i>
                        <h5 class="text-gray-500">Belum ada data mutasi</h5>
                        <p class="text-gray-400">Tambahkan data mutasi penduduk untuk melihat informasi di sini.</p>
                        <a href="{{ route('mutasi.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus"></i> Tambah Data Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Empty State Statistics Cards -->
        @if($mutasi->count() === 0)
            <div class="row mt-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        <i class="fas fa-chart-line"></i> Total Mutasi
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                    <div class="text-xs text-gray-500">Semua Jenis Mutasi</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exchange-alt fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        <i class="fas fa-baby"></i> Kelahiran
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                    <div class="text-xs text-gray-500">Data Lahir Baru</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-baby fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        <i class="fas fa-arrows-alt"></i> Pindah/Datang
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                    <div class="text-xs text-gray-500">Pindah: 0 | Datang: 0</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-arrows-alt fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        <i class="fas fa-cross"></i> Kematian
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                    <div class="text-xs text-gray-500">Data Meninggal</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-cross fa-2x text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

                </div>
                <!-- /.container-fluid -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Real-time Filter for Mutasi Data
            let filterTimeout;

            function fetchMutasi() {
                const dusunFilter = $('#filterDusun').val();

                $.ajax({
                    url: "{{ route('mutasi.index') }}",
                    type: "GET",
                    data: {
                        dusun: dusunFilter
                    },
                    success: function(response) {
                        $('#mutasiTableBody').html(response);
                    },
                    error: function(xhr) {
                        console.error("Error fetching mutasi data:", xhr);
                    }
                });
            }

            function updateDusunFilter() {
                $.ajax({
                    url: "{{ route('mutasi.available-dusuns') }}",
                    type: "GET",
                    success: function(dusuns) {
                        let select = $('#filterDusun');
                        let currentValue = select.val();

                        // Clear existing options except "Semua Dusun"
                        select.find('option:not(:first)').remove();

                        // Add new options
                        dusuns.forEach(function(dusun) {
                            select.append('<option value="' + dusun + '">' + dusun + '</option>');
                        });

                        // Restore previous selection if it still exists
                        if (currentValue) {
                            select.val(currentValue);
                        }
                    },
                    error: function(xhr) {
                        console.error("Error fetching available dusuns:", xhr);
                    }
                });
            }

            function refreshDataAndFilter() {
                updateDusunFilter();
                fetchMutasi();
            }

            // Event listener for dusun filter
            $('#filterDusun').on('change', function() {
                fetchMutasi();
            });

            // Auto-refresh after successful mutation creation
            // Check if there's a success message and refresh both table and filter
            @if(session('success'))
                // If there's a success message, refresh both table and filter to show new data
                refreshDataAndFilter();
            @endif
        });
    </script>
@endsection