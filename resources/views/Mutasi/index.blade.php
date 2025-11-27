@extends('layouts.master')

@section('title', 'Data Mutasi Penduduk')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Mutasi Penduduk</h1>
            <a href="{{ route('mutasi.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Mutasi
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
                            <tbody>
                                @php
                                    $nomor = 1;
                                @endphp

                                @foreach($mutasi as $item)
                                    <tr>
                                        <td class="text-center align-middle">{{ $nomor++ }}</td>
                                        <td class="align-middle">
                                            <strong>{{ $item->penduduk->nama ?? 'Tidak Diketahui' }}</strong>
                                            @if($item->penduduk && $item->penduduk->hubungan_keluarga == 'Kepala Keluarga')
                                                <span class="badge badge-primary ml-2">KK</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $item->penduduk->nik ?? '-' }}</td>
                                        <td class="align-middle">
                                            {{ $item->penduduk && $item->penduduk->kartuKeluarga ? $item->penduduk->kartuKeluarga->no_kk : '-' }}
                                        </td>
                                        <td class="align-middle">
                                            @switch($item->jenis_mutasi)
                                                @case('LAHIR')
                                                    <span class="badge badge-success">👶 Lahir</span>
                                                    @break
                                                @case('MENINGGAL')
                                                    <span class="badge badge-dark">⚰️ Meninggal</span>
                                                    @break
                                                @case('DATANG')
                                                    <span class="badge badge-info">🏠 Datang</span>
                                                    @break
                                                @case('PINDAH')
                                                    <span class="badge badge-warning">🚚 Pindah</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">{{ $item->jenis_mutasi }}</span>
                                            @endswitch
                                        </td>
                                        <td class="align-middle">{{ \Carbon\Carbon::parse($item->tanggal_kejadian)->format('d/m/Y') }}</td>
                                        <td class="align-middle">{{ $item->lokasi_detail ?: '-' }}</td>
                                        <td class="align-middle">
                                            <span title="{{ $item->keterangan }}">
                                                {{ \Illuminate\Support\Str::limit($item->keterangan ?: '-', 30) }}
                                            </span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('mutasi.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('mutasi.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('mutasi.destroy', $item->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mutasi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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