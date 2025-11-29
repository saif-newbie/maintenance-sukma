@extends('layouts.master')

@section('title', 'Tambah Mutasi Penduduk')

@push('styles')
    <!-- Select2 CSS untuk searchable dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css" rel="stylesheet" />

    <style>
        /* Custom styles untuk conditional form */
        .form-section {
            display: none;
            transition: all 0.3s ease;
        }

        .form-section.active {
            display: block;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .mutasi-badge {
            padding: 15px 25px;
            border-radius: 15px;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            margin-bottom: 15px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .mutasi-badge:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .mutasi-badge.selected {
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            border-color: #fff;
            z-index: 1;
        }

        .mutasi-badge.selected::after {
            content: '\f00c';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.2rem;
            opacity: 0.5;
        }

        .badge-lahir {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .badge-meninggal {
            background: linear-gradient(135deg, #6c757d 0%, #343a40 100%);
            color: white;
        }

        .badge-datang {
            background: linear-gradient(135deg, #17a2b8 0%, #007bff 100%);
            color: white;
        }

        .badge-pindah {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
        }

        .select2-container--default .select2-selection--single {
            height: 45px;
            padding: 8px 12px;
            border-radius: 0.35rem;
            border: 1px solid #d1d3e2;
        }

        .select2-container--bootstrap4 .select2-selection--single {
            height: 45px !important;
            padding: 8px 12px !important;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .input-group-text {
            background-color: #f8f9fc;
            border-color: #d1d3e2;
        }

        /* Custom styles for KK number input */
        .kk-input-container {
            position: relative;
        }

        #kkSuggestions {
            max-height: 200px;
            overflow-y: auto;
            border-radius: 0.35rem !important;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        }

        #kkSuggestions .alert-info {
            margin-bottom: 0;
            border-radius: 0.35rem;
        }

        /* Adjust Select2 dropdown for better UX */
        .select2-results__option {
            padding: 8px 12px !important;
        }

        .select2-results__option[aria-selected="true"] {
            background-color: #e9ecef !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: #667eea !important;
            color: white !important;
        }

        /* Make the Dusun field match height with KK select */
        #dusun {
            height: 38px !important;
        }

        /* Better responsive layout for small screens */
        @media (max-width: 768px) {
            .kk-input-container {
                margin-bottom: 1rem;
            }

            #dusun {
                margin-top: 1rem;
            }
        }

        /* Auto-fill feedback styles */
        .border-success {
            border-color: #28a745 !important;
            border-width: 2px !important;
            transition: all 0.3s ease;
        }

        .bg-light {
            background-color: #f8f9fa !important;
            transition: all 0.3s ease;
        }

        .alert-sm {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.35rem;
            margin-bottom: 0;
        }

        /* Enhanced Select2 options for better UX */
        .select2-results__option {
            padding: 12px !important;
            border-bottom: 1px solid #f1f3f5;
        }

        .select2-results__option:last-child {
            border-bottom: none;
        }

        .select2-results__option[aria-selected="true"] {
            background-color: #e9ecef;
            font-weight: 500;
        }

        /* Feedback animation */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert.alert-success[style*="position: absolute"] {
            animation: slideDown 0.3s ease;
        }
    </style>
@endpush

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus-circle text-primary mr-2"></i>Tambah Mutasi Penduduk
            </h1>
            <a href="{{ route('mutasi.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6 class="alert-heading"><i class="fas fa-exclamation-triangle mr-2"></i>Perbaiki kesalahan berikut:</h6>
                <ul class="mb-0 mt-2 pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold">Formulir Mutasi Penduduk</h6>
                    </div>
                    <div class="card-body p-4">
                        
                        <form action="{{ route('mutasi.store') }}" method="POST" id="mutasiForm">
                            @csrf
                            
                            <!-- Step 1: Pilih Jenis Mutasi -->
                            <div class="mb-5">
                                <h5 class="text-gray-800 mb-3 border-bottom pb-2">1. Pilih Jenis Mutasi</h5>
                                
                                <div class="row text-center">
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="mutasi-badge badge-lahir" data-mutasi="LAHIR">
                                            <i class="fas fa-baby fa-2x mb-2"></i>
                                            <span>LAHIR</span>
                                        </div>
                                        <input type="radio" name="jenis_mutasi" value="LAHIR" class="d-none" id="mutasi_lahir">
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="mutasi-badge badge-meninggal" data-mutasi="MENINGGAL">
                                            <i class="fas fa-tombstone fa-2x mb-2"></i>
                                            <span>MENINGGAL</span>
                                        </div>
                                        <input type="radio" name="jenis_mutasi" value="MENINGGAL" class="d-none" id="mutasi_meninggal">
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="mutasi-badge badge-datang" data-mutasi="DATANG">
                                            <i class="fas fa-walking fa-2x mb-2"></i>
                                            <span>DATANG</span>
                                        </div>
                                        <input type="radio" name="jenis_mutasi" value="DATANG" class="d-none" id="mutasi_datang">
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="mutasi-badge badge-pindah" data-mutasi="PINDAH">
                                            <i class="fas fa-truck-moving fa-2x mb-2"></i>
                                            <span>PINDAH</span>
                                        </div>
                                        <input type="radio" name="jenis_mutasi" value="PINDAH" class="d-none" id="mutasi_pindah">
                                    </div>
                                </div>
                                <div id="jenis_mutasi_error" class="text-danger small mt-1 text-center" style="display:none;">Silakan pilih jenis mutasi terlebih dahulu.</div>
                            </div>

                            <!-- Arrival Mode Toggle (Hanya muncul jika DATANG dipilih) -->
                            <div id="arrivalModeToggle" class="form-section mb-4" style="display: none;">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Mode Kedatangan</div>
                                                <div class="btn-group w-100" role="group" aria-label="Mode Kedatangan">
                                                    <button type="button" class="btn btn-outline-info active" id="modeIndividu" data-mode="individu">
                                                        <i class="fas fa-user mr-1"></i> Individu
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info" id="modeKeluarga" data-mode="keluarga">
                                                        <i class="fas fa-users mr-1"></i> Seluruh Keluarga
                                                    </button>
                                                </div>
                                                <input type="hidden" name="arrival_mode" id="arrivalMode" value="individu">
                                                <div class="mt-2 small text-muted" id="modeDescription">
                                                    <i class="fas fa-info-circle mr-1"></i> Satu orang bergabung dengan keluarga yang sudah ada
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Section untuk LAHIR / DATANG INDIVIDU -->
                            <div id="formPendudukBaru" class="form-section">
                                <!-- Individual Arrival Mode -->
                                <div id="individuForm">
                                    <h5 class="text-gray-800 mb-3 border-bottom pb-2">2. Data Penduduk Baru (Individu)</h5>

                                    <!-- Family Card Number and Dusun at Top -->
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <label for="kartu_keluarga_id" class="font-weight-bold">
                                                Nomor Kartu Keluarga <span class="text-danger">*</span>
                                            </label>
                                            <div class="kk-input-container position-relative">
                                                <select class="form-control select2-kk @error('kartu_keluarga_id') is-invalid @enderror"
                                                        id="kartu_keluarga_id" name="kartu_keluarga_id"
                                                        data-placeholder="Ketik nomor KK atau nama kepala keluarga..."
                                                        style="width: 100%;">
                                                    <option value="">Pilih Kartu Keluarga</option>
                                                    @foreach($kartuKeluarga as $kk)
                                                        <option value="{{ $kk->id }}" {{ old('kartu_keluarga_id') == $kk->id ? 'selected' : '' }}>
                                                            {{ $kk->no_kk }} - {{ $kk->penduduk->first()->nama ?? 'Tidak ada Kepala Keluarga' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <!-- Auto-suggestions container for new KK numbers -->
                                                <div id="kkSuggestions" class="position-absolute w-100 bg-white border rounded shadow-sm mt-1" style="z-index: 1000; display: none;">
                                                    <div class="p-2">
                                                        <small class="text-muted">Nomor KK tidak ditemukan. Tekan Enter untuk menggunakan nomor baru:</small>
                                                        <div id="newKkPreview" class="alert alert-info small mt-1" style="display: none;"></div>
                                                    </div>
                                                </div>
                                                @error('kartu_keluarga_id')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    Ketik nomor KK yang sudah ada atau masukkan nomor KK baru
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="dusun" class="font-weight-bold">Dusun</label>
                                            <select class="form-control @error('dusun') is-invalid @enderror"
                                                    id="dusun" name="dusun"
                                                    style="height: 38px; width: 100%;">
                                                <option value="">Pilih Dusun</option>
                                                <option value="Dusun 1" {{ old('dusun') == 'Dusun 1' ? 'selected' : '' }}>Dusun 1</option>
                                                <option value="Dusun 2" {{ old('dusun') == 'Dusun 2' ? 'selected' : '' }}>Dusun 2</option>
                                                <option value="Dusun 3" {{ old('dusun') == 'Dusun 3' ? 'selected' : '' }}>Dusun 3</option>
                                            </select>
                                            @error('dusun')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Pilih dusun yang tersedia atau ketik manual untuk dusun baru
                                            </small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nik" class="font-weight-bold">NIK <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nik') is-invalid @enderror"
                                                   id="nik" name="nik" value="{{ old('nik') }}"
                                                   placeholder="Nomor Induk Kependudukan (16 digit)" maxlength="16">
                                            @error('nik')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Untuk kelahiran bisa dikosongkan untuk generate otomatis.
                                            </small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nama" class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                                   id="nama" name="nama" value="{{ old('nama') }}"
                                                   placeholder="Masukkan nama lengkap">
                                            @error('nama')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="jenis_kelamin" class="font-weight-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                                            <select class="form-control @error('jenis_kelamin') is-invalid @enderror"
                                                    id="jenis_kelamin" name="jenis_kelamin">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                            @error('jenis_kelamin')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="tempat_lahir" class="font-weight-bold">Tempat Lahir <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                                   id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                                   placeholder="Kota/Kabupaten Kelahiran">
                                            @error('tempat_lahir')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="tgl_lahir" class="font-weight-bold">Tanggal Lahir <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror"
                                                   id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir') }}">
                                            @error('tgl_lahir')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="pekerjaan" class="font-weight-bold">Pekerjaan <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('pekerjaan') is-invalid @enderror"
                                                   id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan') }}"
                                                   placeholder="Pekerjaan">
                                            @error('pekerjaan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="hubungan_keluarga" class="font-weight-bold">Hubungan Keluarga <span class="text-danger">*</span></label>
                                            <select class="form-control @error('hubungan_keluarga') is-invalid @enderror"
                                                    id="hubungan_keluarga" name="hubungan_keluarga">
                                                <option value="">Pilih Hubungan</option>
                                                <option value="Kepala Keluarga" {{ old('hubungan_keluarga') == 'Kepala Keluarga' ? 'selected' : '' }}>Kepala Keluarga</option>
                                                <option value="Istri" {{ old('hubungan_keluarga') == 'Istri' ? 'selected' : '' }}>Istri</option>
                                                <option value="Anak" {{ old('hubungan_keluarga') == 'Anak' ? 'selected' : '' }}>Anak</option>
                                                <option value="Orang Tua" {{ old('hubungan_keluarga') == 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                                                <option value="Lainnya" {{ old('hubungan_keluarga') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            @error('hubungan_keluarga')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="tamatan" class="font-weight-bold">Pendidikan <span class="text-danger">*</span></label>
                                            <select class="form-control @error('tamatan') is-invalid @enderror"
                                                    id="tamatan" name="tamatan">
                                                <option value="">Pilih Pendidikan</option>
                                                <option value="Tidak Sekolah" {{ old('tamatan') == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                                                <option value="SD" {{ old('tamatan') == 'SD' ? 'selected' : '' }}>SD</option>
                                                <option value="SMP" {{ old('tamatan') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                                <option value="SMA" {{ old('tamatan') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                                <option value="D3" {{ old('tamatan') == 'D3' ? 'selected' : '' }}>D3</option>
                                                <option value="S1" {{ old('tamatan') == 'S1' ? 'selected' : '' }}>S1</option>
                                                <option value="S2" {{ old('tamatan') == 'S2' ? 'selected' : '' }}>S2</option>
                                                <option value="S3" {{ old('tamatan') == 'S3' ? 'selected' : '' }}>S3</option>
                                            </select>
                                            @error('tamatan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <!-- Individual Form End -->

                                <!-- Family Arrival Mode -->
                                <div id="keluargaForm" style="display: none;">
                                    <h5 class="text-gray-800 mb-3 border-bottom pb-2">2. Data Seluruh Keluarga yang Datang</h5>
                                    
                                    <!-- HEADER SECTION - Data Kartu Keluarga (Single) -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="font-weight-bold text-primary mb-3">
                                                <i class="fas fa-home mr-1"></i> Data Kartu Keluarga
                                            </h6>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="family_nomor_kk" class="font-weight-bold">Nomor KK <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('family_nomor_kk') is-invalid @enderror"
                                                   id="family_nomor_kk" name="family_nomor_kk" value="{{ old('family_nomor_kk') }}">
                                            @error('family_nomor_kk')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="family_dusun" class="font-weight-bold">Dusun</label>
                                            <select class="form-control @error('family_dusun') is-invalid @enderror"
                                                    id="family_dusun" name="family_dusun"
                                                    style="height: 38px; width: 100%;">
                                                <option value="">Pilih Dusun</option>
                                                <option value="Dusun 1" {{ old('family_dusun') == 'Dusun 1' ? 'selected' : '' }}>Dusun 1</option>
                                                <option value="Dusun 2" {{ old('family_dusun') == 'Dusun 2' ? 'selected' : '' }}>Dusun 2</option>
                                                <option value="Dusun 3" {{ old('family_dusun') == 'Dusun 3' ? 'selected' : '' }}>Dusun 3</option>
                                            </select>
                                            @error('family_dusun')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Pilih dusun yang tersedia atau Ketik manual untuk dusun baru
                                            </small>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="family_kategori_sejahtera" class="font-weight-bold">Kategori Sejahtera <small class="text-muted">(Opsional)</small></label>
                                            <select class="form-control @error('family_kategori_sejahtera') is-invalid @enderror"
                                                    id="family_kategori_sejahtera" name="family_kategori_sejahtera">
                                                <option value="">Pilih Kategori Sejahtera</option>
                                                <option value="KS1" {{ old('family_kategori_sejahtera') == 'KS1' ? 'selected' : '' }}>KS1</option>
                                                <option value="KS2" {{ old('family_kategori_sejahtera') == 'KS2' ? 'selected' : '' }}>KS2</option>
                                                <option value="KS3" {{ old('family_kategori_sejahtera') == 'KS3' ? 'selected' : '' }}>KS3</option>
                                            </select>
                                            @error('family_kategori_sejahtera')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="family_jenis_bangunan" class="font-weight-bold">Jenis Bangunan <small class="text-muted">(Opsional)</small></label>
                                            <input type="text" class="form-control @error('family_jenis_bangunan') is-invalid @enderror"
                                                   id="family_jenis_bangunan" name="family_jenis_bangunan" value="{{ old('family_jenis_bangunan') }}" placeholder="Contoh: Rumah, Apartemen">
                                            @error('family_jenis_bangunan')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- REPEATER SECTION - Data Anggota Keluarga (Multiple) -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="font-weight-bold text-primary mb-0">
                                                    <i class="fas fa-users mr-1"></i> Data Anggota Keluarga
                                                </h6>
                                                <button type="button" class="btn btn-success btn-sm" id="tambahFamilyAnggota">
                                                    <i class="fas fa-plus-circle mr-1"></i> Tambah Anggota
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-sm" id="tableFamilyAnggota" style="font-size: 13px;">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th width="40px" style="text-align: center;">No</th>
                                                            <th width="120px">NIK</th>
                                                            <th width="150px">Nama Lengkap</th>
                                                            <th width="80px">Gender</th>
                                                            <th width="120px">Tempat Lahir</th>
                                                            <th width="120px">Tanggal Lahir</th>
                                                            <th width="60px" style="text-align: center;">Usia</th>
                                                            <th width="130px">Pekerjaan</th>
                                                            <th width="100px">Hubungan</th>
                                                            <th width="100px">Pendidikan</th>
                                                            <th width="60px" style="text-align: center;">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="familyAnggotaContainer">
                                                        <!-- Baris pertama (Default) -->
                                                        <tr class="family-anggota-row" data-index="0">
                                                            <td class="text-center">1</td>
                                                            <td>
                                                                <input type="number" class="form-control form-control-sm family-nik-input @error('family_anggota.0.nik') is-invalid @enderror"
                                                                       name="family_anggota[0][nik]" value="{{ old('family_anggota.0.nik') }}" placeholder="16 digit NIK">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm family-nama-input @error('family_anggota.0.nama') is-invalid @enderror"
                                                                       name="family_anggota[0][nama]" value="{{ old('family_anggota.0.nama') }}" placeholder="Nama lengkap">
                                                            </td>
                                                            <td>
                                                                <select class="form-control form-control-sm family-gender-input @error('family_anggota.0.jenis_kelamin') is-invalid @enderror" name="family_anggota[0][jenis_kelamin]">
                                                                    <option value="">Pilih</option>
                                                                    <option value="L" {{ old('family_anggota.0.jenis_kelamin') == 'L' ? 'selected' : '' }}>L</option>
                                                                    <option value="P" {{ old('family_anggota.0.jenis_kelamin') == 'P' ? 'selected' : '' }}>P</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm family-tempat-lahir-input @error('family_anggota.0.tempat_lahir') is-invalid @enderror"
                                                                       name="family_anggota[0][tempat_lahir]" value="{{ old('family_anggota.0.tempat_lahir') }}" placeholder="Tempat lahir">
                                                            </td>
                                                            <td>
                                                                <input type="date" class="form-control form-control-sm family-tgl-lahir-input @error('family_anggota.0.tgl_lahir') is-invalid @enderror"
                                                                       name="family_anggota[0][tgl_lahir]" value="{{ old('family_anggota.0.tgl_lahir') }}">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" class="form-control form-control-sm text-center family-usia-input"
                                                                       name="family_anggota[0][usia]" value="{{ old('family_anggota.0.usia') }}" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm family-pekerjaan-input @error('family_anggota.0.pekerjaan') is-invalid @enderror"
                                                                       name="family_anggota[0][pekerjaan]" value="{{ old('family_anggota.0.pekerjaan') }}" placeholder="Pekerjaan">
                                                            </td>
                                                            <td>
                                                                <select class="form-control form-control-sm family-hubungan-input @error('family_anggota.0.hubungan_keluarga') is-invalid @enderror" name="family_anggota[0][hubungan_keluarga]">
                                                                    <option value="">Pilih</option>
                                                                    <option value="Kepala Keluarga" {{ old('family_anggota.0.hubungan_keluarga') == 'Kepala Keluarga' ? 'selected' : '' }}>K.Keluarga</option>
                                                                    <option value="Istri" {{ old('family_anggota.0.hubungan_keluarga') == 'Istri' ? 'selected' : '' }}>Istri</option>
                                                                    <option value="Anak" {{ old('family_anggota.0.hubungan_keluarga') == 'Anak' ? 'selected' : '' }}>Anak</option>
                                                                    <option value="Menantu" {{ old('family_anggota.0.hubungan_keluarga') == 'Menantu' ? 'selected' : '' }}>Menantu</option>
                                                                    <option value="Cucu" {{ old('family_anggota.0.hubungan_keluarga') == 'Cucu' ? 'selected' : '' }}>Cucu</option>
                                                                    <option value="Lainnya" {{ old('family_anggota.0.hubungan_keluarga') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-control form-control-sm family-tamatan-input @error('family_anggota.0.tamatan') is-invalid @enderror" name="family_anggota[0][tamatan]">
                                                                    <option value="">Pilih</option>
                                                                    <option value="SD" {{ old('family_anggota.0.tamatan') == 'SD' ? 'selected' : '' }}>SD</option>
                                                                    <option value="SMP" {{ old('family_anggota.0.tamatan') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                                                    <option value="SMA/SMK" {{ old('family_anggota.0.tamatan') == 'SMA/SMK' ? 'selected' : '' }}>SMA</option>
                                                                    <option value="D1/D2/D3" {{ old('family_anggota.0.tamatan') == 'D1/D2/D3' ? 'selected' : '' }}>D3</option>
                                                                    <option value="S1" {{ old('family_anggota.0.tamatan') == 'S1' ? 'selected' : '' }}>S1</option>
                                                                    <option value="S2" {{ old('family_anggota.0.tamatan') == 'S2' ? 'selected' : '' }}>S2</option>
                                                                    <option value="S3" {{ old('family_anggota.0.tamatan') == 'S3' ? 'selected' : '' }}>S3</option>
                                                                    <option value="Tidak Tamat SD" {{ old('family_anggota.0.tamatan') == 'Tidak Tamat SD' ? 'selected' : '' }}>Tidak Tamat</option>
                                                                    <option value="Tidak Sekolah" {{ old('family_anggota.0.tamatan') == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                                                                </select>
                                                            </td>
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-secondary btn-sm hapus-family-anggota" disabled
                                                                        title="Tidak bisa menghapus anggota terakhir">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Family Form End -->
                            </div>

                            <!-- Form Section untuk MENINGGAL / PINDAH (Pilih Penduduk yang Ada) -->
                            <div id="formPendudukAda" class="form-section">
                                <h5 class="text-gray-800 mb-3 border-bottom pb-2">2. Pilih Penduduk</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="penduduk_id" class="font-weight-bold">
                                            Cari dan Pilih Penduduk <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control @error('penduduk_id') is-invalid @enderror"
                                                id="penduduk_id" name="penduduk_id">
                                            <option value="">Pilih Penduduk</option>
                                            @foreach($penduduk as $p)
                                                <option value="{{ $p->id }}" {{ old('penduduk_id') == $p->id ? 'selected' : '' }}>
                                                    {{ $p->nama }} ({{ $p->nik }}) - {{ $p->kartuKeluarga->no_kk ?? 'Tidak ada KK' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('penduduk_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Hanya penduduk dengan status HIDUP yang muncul dalam daftar.
                                        </small>
                                    </div>
                                </div>

                                <!-- Detail Penduduk yang Dipilih -->
                                <div id="detailPenduduk" class="alert alert-info d-none">
                                    <h6 class="font-weight-bold"><i class="fas fa-info-circle mr-1"></i> Detail Penduduk Terpilih:</h6>
                                    <div id="detailContent"></div>
                                </div>
                            </div>

                            <!-- Form Detail Mutasi (Selalu Muncul) -->
                            <div id="formDetailMutasi" class="form-section mt-4">
                                <h5 class="text-gray-800 mb-3 border-bottom pb-2">3. Detail Mutasi</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_kejadian" class="font-weight-bold" id="tanggal_label">
                                            Tanggal Kejadian <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control @error('tanggal_kejadian') is-invalid @enderror"
                                               id="tanggal_kejadian" name="tanggal_kejadian" value="{{ old('tanggal_kejadian', date('Y-m-d')) }}" required>
                                        @error('tanggal_kejadian')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted" id="tanggal_hint">
                                            Tanggal terjadinya peristiwa mutasi
                                        </small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="lokasi_detail" class="font-weight-bold">Lokasi Detail</label>
                                        <input type="text" class="form-control @error('lokasi_detail') is-invalid @enderror"
                                               id="lokasi_detail" name="lokasi_detail" value="{{ old('lokasi_detail') }}"
                                               placeholder="Contoh: RSUD Kota, Puskesmas, Desa Sebelah, dll">
                                        @error('lokasi_detail')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="keterangan" class="font-weight-bold">Keterangan</label>
                                        <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                                  id="keterangan" name="keterangan" rows="3"
                                                  placeholder="Masukkan keterangan tambahan jika ada">{{ old('keterangan') }}</textarea>
                                        @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row mt-4">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn" disabled>
                                            <i class="fas fa-save mr-1"></i> Simpan Data Mutasi
                                        </button>
                                        <a href="{{ route('mutasi.index') }}" class="btn btn-secondary btn-lg px-5 ml-2">
                                            <i class="fas fa-times mr-1"></i> Batal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection

@push('scripts')
    <!-- Select2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 untuk dropdown penduduk
            $('#penduduk_id').select2({
                placeholder: 'Cari nama atau NIK penduduk...',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap4'
            });

            // Inisialisasi Select2 untuk dropdown Kartu Keluarga
            $('.select2-kk').select2({
                placeholder: 'Ketik nomor KK atau nama kepala keluarga...',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap4',
                tags: true, // Allow creating new tags
                createTag: function (params) {
                    // Check if the input looks like a KK number (16 digits)
                    const kkNumber = params.term.trim();
                    if (/^\d{16}$/.test(kkNumber)) {
                        return {
                            id: 'new_' + kkNumber,
                            text: kkNumber + ' (Nomor KK Baru)',
                            newOption: true
                        }
                    }
                    return null;
                }
            });

            // Handle Select2 events for KK number validation
            $('#kartu_keluarga_id').on('select2:select', function (e) {
                const selectedData = e.params.data;
                const suggestionsDiv = $('#kkSuggestions');
                const newKkPreview = $('#newKkPreview');
                const dusunField = $('#dusun');

                if (selectedData.newOption) {
                    // New KK number entered
                    const kkNumber = selectedData.id.replace('new_', '');
                    suggestionsDiv.show();
                    newKkPreview.show().html(`
                        <strong>Nomor KK Baru:</strong> ${kkNumber}<br>
                        <small class="text-success">✓ Nomor KK baru akan dibuat otomatis</small><br>
                        <small class="text-info">ℹ Isi dusun secara manual untuk KK baru</small>
                    `);

                    // Add hidden field to track new KK number
                    if (!$('#new_kk_number').length) {
                        $('form').append('<input type="hidden" id="new_kk_number" name="new_kk_number" value="">');
                    }
                    $('#new_kk_number').val(kkNumber);

                    // Clear dusun field for new KK (user needs to fill manually)
                    dusunField.val('').prop('readonly', false);

                    // Add handler for Enter key when input is focused on suggestions
                    $(document).one('keydown', function(e) {
                        if (e.key === 'Enter' && suggestionsDiv.is(':visible')) {
                            suggestionsDiv.hide();
                            newKkPreview.hide();
                        }
                    });
                } else {
                    // Existing KK selected
                    const kkId = selectedData.id;
                    const kkData = dataKartuKeluarga[kkId];

                    suggestionsDiv.hide();
                    newKkPreview.hide();
                    // Remove hidden field for new KK if it exists
                    $('#new_kk_number').remove();

                    // Auto-fill dusun field if data exists
                    if (kkData && kkData.dusun) {
                        dusunField.val(kkData.dusun);

                        // Show visual feedback that dusun was auto-filled
                        dusunField.addClass('bg-light border-success');
                        setTimeout(function() {
                            dusunField.removeClass('bg-light border-success');
                        }, 2000);

                        // Show brief notification
                        const feedback = '<div class="alert alert-success alert-sm fade show" style="position: absolute; z-index: 1000; margin-top: 5px;">' +
                            '<i class="fas fa-check-circle mr-1"></i> Dusun: ' + kkData.dusun + ' (terisi otomatis)' +
                            '</div>';
                        dusunField.parent().append(feedback);
                        setTimeout(function() {
                            $(feedback).fadeOut('slow', function() { $(this).remove(); });
                        }, 3000);
                    } else {
                        dusunField.val('').prop('readonly', false);
                    }
                }
            });

            // Enhanced form validation to handle KK submissions
            $('#mutasiForm').submit(function(e) {
                const kartuKeluargaId = $('#kartu_keluarga_id').val();
                const newKkNumber = $('#new_kk_number').val();

                // If we have a new KK number, ensure the kartu_keluarga_id contains the new_ prefix
                if (newKkNumber && !kartuKeluargaId.startsWith('new_')) {
                    $('#kartu_keluarga_id').val('new_' + newKkNumber);
                }
            });

            // Handle typing in Select2 search to detect new KK numbers
            $('#kartu_keluarga_id').on('select2:open', function() {
                const searchField = $('.select2-search__field');
                let typingTimer;

                searchField.on('input', function() {
                    clearTimeout(typingTimer);
                    const searchTerm = $(this).val().trim();

                    typingTimer = setTimeout(() => {
                        const suggestionsDiv = $('#kkSuggestions');
                        const newKkPreview = $('#newKkPreview');

                        // Check if input looks like a KK number (16 digits) and doesn't match existing options
                        if (/^\d{16}$/.test(searchTerm)) {
                            const existingKK = $('#kartu_keluarga_id option').filter(function() {
                                return $(this).text().startsWith(searchTerm);
                            }).length > 0;

                            if (!existingKK) {
                                suggestionsDiv.show();
                                newKkPreview.show().html(`
                                    <strong>Nomor KK Baru Terdeteksi:</strong> ${searchTerm}<br>
                                    <small class="text-success">Tekan Enter atau pilih untuk menggunakan nomor KK ini</small>
                                `);
                            } else {
                                suggestionsDiv.hide();
                                newKkPreview.hide();
                            }
                        } else {
                            suggestionsDiv.hide();
                            newKkPreview.hide();
                        }
                    }, 300);
                });
            });

            // Handle Select2 searching to show existing KK info
            $('#kartu_keluarga_id').on('select2:opening', function (e) {
                // Add custom styling for better visibility of existing options
                setTimeout(() => {
                    $('.select2-results__option[role="option"]').each(function() {
                        if (!$(this).text().includes('(Nomor KK Baru)')) {
                            // Style existing KK options to show additional info
                            const text = $(this).text();
                            if (text.includes(' - ')) {
                                const parts = text.split(' - ');
                                $(this).html(`
                                    <div>
                                        <strong>${parts[0]}</strong>
                                        <br><small class="text-muted">Kepala Keluarga: ${parts[1]}</small>
                                    </div>
                                `);
                            }
                        }
                    });
                }, 100);
            });

            // Hide suggestions when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.select2-container, #kkSuggestions').length) {
                    $('#kkSuggestions').hide();
                }
            });

            // Data penduduk untuk detail
            const dataPenduduk = {
                @foreach($penduduk as $p)
                    '{{ $p->id }}': {
                        nama: '{{ $p->nama }}',
                        nik: '{{ $p->nik }}',
                        no_kk: '{{ $p->kartuKeluarga->no_kk ?? 'Tidak ada KK' }}',
                        usia: '{{ $p->usia }}',
                        pekerjaan: '{{ $p->pekerjaan }}',
                        dusun: '{{ $p->dusun ?? 'Tidak diketahui' }}'
                    }@if(!$loop->last),@endif
                @endforeach
            };

            // Data Kartu Keluarga untuk auto-fill dusun
            const dataKartuKeluarga = {
                @foreach($kartuKeluarga as $kk)
                    '{{ $kk->id }}': {
                        no_kk: '{{ $kk->no_kk }}',
                        dusun: '{{ $kk->dusun ?? '' }}',
                        kepala_keluarga: '{{ $kk->penduduk->first()->nama ?? 'Tidak ada Kepala Keluarga' }}'
                    }@if(!$loop->last),@endif
                @endforeach
            };

            // Function untuk update dusun field di family form
            function updateFamilyDusun(kkId) {
                const kkData = dataKartuKeluarga[kkId];
                const dusunField = $('#family_dusun');

                if (kkData && kkData.dusun) {
                    dusunField.val(kkData.dusun);
                    // Show visual feedback that dusun was auto-filled
                    dusunField.addClass('bg-light border-success');
                    setTimeout(function() {
                        dusunField.removeClass('bg-light border-success');
                    }, 2000);
                } else {
                    dusunField.val('').prop('readonly', false);
                }
            }

            // Handle click pada badge jenis mutasi
            $('.mutasi-badge').click(function() {
                const mutasiType = $(this).data('mutasi');

                // Reset semua badge
                $('.mutasi-badge').removeClass('selected');
                $('.form-section').removeClass('active');
                $('#submitBtn').prop('disabled', true);

                // Select badge yang diklik
                $(this).addClass('selected');
                $(`#mutasi_${mutasiType.toLowerCase()}`).prop('checked', true);

                // Enable submit button since mutation type is selected
                $('#submitBtn').prop('disabled', false);

                // Show form section yang sesuai
                if (mutasiType === 'LAHIR' || mutasiType === 'DATANG') {
                    $('#formPendudukBaru').addClass('active');

                    // Show arrival mode toggle only for DATANG
                    if (mutasiType === 'DATANG') {
                        $('#arrivalModeToggle').addClass('active');
                        // Set default arrival mode
                        switchArrivalMode('individu');
                    } else {
                        $('#arrivalModeToggle').removeClass('active');
                        // For LAHIR, set individual form fields as required
                        $('#individuForm input, #individuForm select').prop('required', true);
                        $('#nik').prop('required', false); // NIK is optional for LAHIR (can be auto-generated)
                    }

                    $('#formPendudukAda input, #formPendudukAda select').prop('required', false);
                    $('#penduduk_id').prop('required', false);
                } else if (mutasiType === 'MENINGGAL' || mutasiType === 'PINDAH') {
                    $('#formPendudukAda').addClass('active');
                    // Hide arrival mode toggle for non-DATANG mutations
                    $('#arrivalModeToggle').removeClass('active');
                    // Enable required fields untuk penduduk yang ada
                    $('#penduduk_id').prop('required', true);
                    $('#formPendudukBaru input, #formPendudukBaru select').prop('required', false);
                } else {
                    $('#arrivalModeToggle').removeClass('active');
                }
                
                // Show detail mutasi section
                $('#formDetailMutasi').addClass('active');

                // Update form title dan deskripsi
                updateFormInfo(mutasiType);
                
                // Scroll to form
                $('html, body').animate({
                    scrollTop: $("#mutasiForm").offset().top - 100
                }, 500);
            });

            // Handle change pada select penduduk
            $('#penduduk_id').change(function() {
                const pendudukId = $(this).val();
                const detailDiv = $('#detailPenduduk');
                const detailContent = $('#detailContent');

                if (pendudukId && dataPenduduk[pendudukId]) {
                    const penduduk = dataPenduduk[pendudukId];
                    detailContent.html(`
                        <div class="row">
                            <div class="col-md-6"><strong>Nama:</strong> ${penduduk.nama}</div>
                            <div class="col-md-6"><strong>NIK:</strong> ${penduduk.nik}</div>
                            <div class="col-md-6"><strong>No. KK:</strong> ${penduduk.no_kk}</div>
                            <div class="col-md-6"><strong>Usia:</strong> ${penduduk.usia} tahun</div>
                            <div class="col-md-6"><strong>Pekerjaan:</strong> ${penduduk.pekerjaan}</div>
                            <div class="col-md-6"><strong>Dusun:</strong> ${penduduk.dusun}</div>
                        </div>
                    `);
                    detailDiv.removeClass('d-none');
                } else {
                    detailDiv.addClass('d-none');
                }
            });

            // Function untuk update form info
            function updateFormInfo(mutasiType) {
                const buttonTexts = {
                    'LAHIR': 'Simpan Data Kelahiran',
                    'DATANG': 'Simpan Data Kedatangan',
                    'MENINGGAL': 'Simpan Data Kematian',
                    'PINDAH': 'Simpan Data Kepindahan'
                };

                // Dynamic label update untuk tanggal field
                const dateLabels = {
                    'LAHIR': {
                        label: '<i class="fas fa-baby mr-1"></i> Tanggal Kelahiran',
                        hint: 'Tanggal kelahiran penduduk (tercantum pada akta kelahiran)'
                    },
                    'DATANG': {
                        label: '<i class="fas fa-sign-in-alt mr-1"></i> Tanggal Kedatangan',
                        hint: 'Tanggal penduduk resmi pindah masuk ke desa'
                    },
                    'MENINGGAL': {
                        label: '<i class="fas fa-cross mr-1"></i> Tanggal Meninggal',
                        hint: 'Tanggal kematian (sesuai surat keterangan kematian)'
                    },
                    'PINDAH': {
                        label: '<i class="fas fa-sign-out-alt mr-1"></i> Tanggal Pindah',
                        hint: 'Tanggal penduduk resmi pindah keluar dari desa'
                    }
                };

                // Update tanggal label dan hint
                const dateInfo = dateLabels[mutasiType] || dateLabels['PINDAH'];
                $('#tanggal_label').html(`${dateInfo.label} <span class="text-danger">*</span>`);
                $('#tanggal_hint').html(dateInfo.hint);

                // Update submit button text
                $('#submitBtn').html(`<i class="fas fa-save mr-1"></i> ${buttonTexts[mutasiType]}`);
            }

            // Form validation submit
            $('#mutasiForm').submit(function(e) {
                const jenisMutasi = $('input[name="jenis_mutasi"]:checked').val();

                if (!jenisMutasi) {
                    e.preventDefault();
                    alert('Silakan pilih jenis mutasi terlebih dahulu!');
                    return false;
                }

                // Validate tanggal_kejadian for all mutation types
                const tanggalKejadian = $('#tanggal_kejadian').val();
                if (!tanggalKejadian) {
                    e.preventDefault();
                    alert('Silakan pilih tanggal kejadian!');
                    $('#tanggal_kejadian').focus();
                    return false;
                }

                if ((jenisMutasi === 'MENINGGAL' || jenisMutasi === 'PINDAH') && !$('select[name="penduduk_id"]').val()) {
                    e.preventDefault();
                    alert('Silakan pilih penduduk yang akan dimutasikan!');
                    return false;
                }

                // Validation for LAHIR and DATANG (individual mode)
                if ((jenisMutasi === 'LAHIR' || (jenisMutasi === 'DATANG' && $('#arrivalMode').val() === 'individu'))) {
                    // Check required fields for birth/individual arrival
                    const nama = $('#nama').val().trim();
                    const jenisKelamin = $('#jenis_kelamin').val();
                    const tempatLahir = $('#tempat_lahir').val().trim();
                    const tglLahir = $('#tgl_lahir').val();
                    const pekerjaan = $('#pekerjaan').val().trim();
                    const hubunganKeluarga = $('#hubungan_keluarga').val();
                    const tamatan = $('#tamatan').val();
                    const kartuKeluargaId = $('#kartu_keluarga_id').val();

                    // Validate each field individually
                    if (!nama) {
                        e.preventDefault();
                        alert('Silakan isi nama lengkap!');
                        $('#nama').focus();
                        return false;
                    }
                    if (!jenisKelamin) {
                        e.preventDefault();
                        alert('Silakan pilih jenis kelamin!');
                        $('#jenis_kelamin').focus();
                        return false;
                    }
                    if (!tempatLahir) {
                        e.preventDefault();
                        alert('Silakan isi tempat lahir!');
                        $('#tempat_lahir').focus();
                        return false;
                    }
                    if (!tglLahir) {
                        e.preventDefault();
                        alert('Silakan pilih tanggal lahir!');
                        $('#tgl_lahir').focus();
                        return false;
                    }
                    if (!pekerjaan) {
                        e.preventDefault();
                        alert('Silakan isi pekerjaan!');
                        $('#pekerjaan').focus();
                        return false;
                    }
                    if (!hubunganKeluarga) {
                        e.preventDefault();
                        alert('Silakan pilih hubungan keluarga!');
                        $('#hubungan_keluarga').focus();
                        return false;
                    }
                    if (!tamatan) {
                        e.preventDefault();
                        alert('Silakan pilih pendidikan!');
                        $('#tamatan').focus();
                        return false;
                    }
                    if (!kartuKeluargaId) {
                        e.preventDefault();
                        alert('Silakan pilih atau masukkan nomor kartu keluarga!');
                        $('#kartu_keluarga_id').focus();
                        return false;
                    }

                    // Additional validation for new KK numbers
                    if ($('#new_kk_number').val() && !/^\d{16}$/.test($('#new_kk_number').val())) {
                        e.preventDefault();
                        alert('Nomor KK baru harus 16 digit!');
                        $('#kartu_keluarga_id').focus();
                        return false;
                    }
                }

                // Validation for DATANG (family mode)
                if (jenisMutasi === 'DATANG' && $('#arrivalMode').val() === 'keluarga') {
                    const familyNomorKk = $('#family_nomor_kk').val().trim();
                    if (!familyNomorKk) {
                        e.preventDefault();
                        alert('Silakan isi nomor KK!');
                        $('#family_nomor_kk').focus();
                        return false;
                    }

                    // Check if at least one family member is filled
                    let hasValidMember = false;
                    $('tr.family-anggota-row').each(function() {
                        const nik = $(this).find('input[name*="[nik]"]').val().trim();
                        const nama = $(this).find('input[name*="[nama]"]').val().trim();
                        const jenisKelamin = $(this).find('select[name*="[jenis_kelamin]"]').val();

                        if (nik && nama && jenisKelamin) {
                            hasValidMember = true;
                            return false; // break out of loop
                        }
                    });

                    if (!hasValidMember) {
                        e.preventDefault();
                        alert('Silakan lengkapi data minimal satu anggota keluarga!');
                        return false;
                    }
                }

                // Add confirmation
                const confirmMessages = {
                    'LAHIR': 'Apakah Anda yakin ingin menyimpan data kelahiran ini?',
                    'DATANG': 'Apakah Anda yakin ingin menyimpan data kedatangan ini?',
                    'MENINGGAL': 'Apakah Anda yakin ingin mencatat kematian penduduk ini? Status penduduk akan berubah menjadi MENINGGAL.',
                    'PINDAH': 'Apakah Anda yakin ingin mencatat kepindahan penduduk ini? Status penduduk akan berubah menjadi PINDAH.'
                };

                if (!confirm(confirmMessages[jenisMutasi])) {
                    e.preventDefault();
                    return false;
                }
            });

            // Handle arrival mode switching
            $('#modeIndividu, #modeKeluarga').click(function() {
                const mode = $(this).data('mode');
                switchArrivalMode(mode);
            });

            // Function untuk switch arrival mode
            function switchArrivalMode(mode) {
                // Update button states
                $('#modeIndividu, #modeKeluarga').removeClass('active');
                if (mode === 'individu') {
                    $('#modeIndividu').addClass('active');
                } else {
                    $('#modeKeluarga').addClass('active');
                }

                // Update hidden input
                $('#arrivalMode').val(mode);

                // Update mode description
                const descriptions = {
                    'individu': '<i class="fas fa-info-circle mr-1"></i> Satu orang bergabung dengan keluarga yang sudah ada',
                    'keluarga': '<i class="fas fa-info-circle mr-1"></i> Seluruh keluarga dengan Kartu Keluarga sendiri'
                };
                $('#modeDescription').html(descriptions[mode]);

                // Show/hide appropriate form sections
                if (mode === 'individu') {
                    $('#individuForm').show();
                    $('#keluargaForm').hide();
                    // Enable individual form fields
                    $('#individuForm input, #individuForm select').prop('required', true);
                    $('#nik').prop('required', false); // NIK remains optional
                    // Disable all family form fields
                    $('#keluargaForm input, #keluargaForm select').prop('required', false);
                } else {
                    $('#individuForm').hide();
                    $('#keluargaForm').show();
                    // Enable ONLY required family form fields, keep optional fields optional
                    // Required: family_nomor_kk and all family member fields
                    $('#family_nomor_kk').prop('required', true);
                    $('#keluargaForm input[name^="family_anggota"], #keluargaForm select[name^="family_anggota"]').prop('required', true);
                    // Keep optional fields optional (don't add required attribute)
                    $('#family_kategori_sejahtera, #family_jenis_bangunan, #family_pemakaian_air, #family_jenis_bantuan').prop('required', false);
                    // Disable individual form fields
                    $('#individuForm input, #individuForm select').prop('required', false);
                }
            }

            // Family arrival form functionality
            let familyAnggotaIndex = 1;

            // Tambah anggota keluarga
            $('#tambahFamilyAnggota').click(function() {
                const isFamilyMode = $('#keluargaForm').is(':visible');
                const rowHtml = `
                    <tr class="family-anggota-row" data-index="${familyAnggotaIndex}">
                        <td class="text-center">${familyAnggotaIndex + 1}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm family-nik-input"
                                   name="family_anggota[${familyAnggotaIndex}][nik]" placeholder="16 digit NIK" ${isFamilyMode ? 'required' : ''}>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm family-nama-input"
                                   name="family_anggota[${familyAnggotaIndex}][nama]" placeholder="Nama lengkap" ${isFamilyMode ? 'required' : ''}>
                        </td>
                        <td>
                            <select class="form-control form-control-sm family-gender-input" name="family_anggota[${familyAnggotaIndex}][jenis_kelamin]" ${isFamilyMode ? 'required' : ''}>
                                <option value="">Pilih</option>
                                <option value="L">L</option>
                                <option value="P">P</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm family-tempat-lahir-input"
                                   name="family_anggota[${familyAnggotaIndex}][tempat_lahir]" placeholder="Tempat lahir" ${isFamilyMode ? 'required' : ''}>
                        </td>
                        <td>
                            <input type="date" class="form-control form-control-sm family-tgl-lahir-input"
                                   name="family_anggota[${familyAnggotaIndex}][tgl_lahir]" ${isFamilyMode ? 'required' : ''}>
                        </td>
                        <td class="text-center">
                            <input type="number" class="form-control form-control-sm text-center family-usia-input"
                                   name="family_anggota[${familyAnggotaIndex}][usia]" readonly>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm family-pekerjaan-input"
                                   name="family_anggota[${familyAnggotaIndex}][pekerjaan]" placeholder="Pekerjaan" ${isFamilyMode ? 'required' : ''}>
                        </td>
                        <td>
                            <select class="form-control form-control-sm family-hubungan-input" name="family_anggota[${familyAnggotaIndex}][hubungan_keluarga]" ${isFamilyMode ? 'required' : ''}>
                                <option value="">Pilih</option>
                                <option value="Kepala Keluarga">K.Keluarga</option>
                                <option value="Istri">Istri</option>
                                <option value="Anak">Anak</option>
                                <option value="Menantu">Menantu</option>
                                <option value="Cucu">Cucu</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control form-control-sm family-tamatan-input" name="family_anggota[${familyAnggotaIndex}][tamatan]" ${isFamilyMode ? 'required' : ''}>
                                <option value="">Pilih</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA/SMK">SMA</option>
                                <option value="D1/D2/D3">D3</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                                <option value="Tidak Tamat SD">Tidak Tamat</option>
                                <option value="Tidak Sekolah">Tidak Sekolah</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm hapus-family-anggota">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                $('#familyAnggotaContainer').append(rowHtml);
                familyAnggotaIndex++;
                updateFamilyAnggotaNumbers();
                updateDeleteButtons();
            });

            // Hapus anggota keluarga (event delegation)
            $(document).on('click', '.hapus-family-anggota', function() {
                $(this).closest('.family-anggota-row').remove();
                updateFamilyAnggotaNumbers();
                updateDeleteButtons();
            });

            // Update nomor urut anggota keluarga
            function updateFamilyAnggotaNumbers() {
                $('#familyAnggotaContainer tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                    $(this).attr('data-index', index);
                });
            }

            // Update status tombol hapus
            function updateDeleteButtons() {
                const totalRows = $('#familyAnggotaContainer tr').length;
                $('.hapus-family-anggota').prop('disabled', totalRows <= 1);
            }

            // Auto-calculate age based on birth date
            $(document).on('change', '.family-tgl-lahir-input', function() {
                const birthDate = $(this).val();
                if (birthDate) {
                    const today = new Date();
                    const birth = new Date(birthDate);
                    let age = today.getFullYear() - birth.getFullYear();
                    const monthDiff = today.getMonth() - birth.getMonth();

                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                        age--;
                    }

                    $(this).closest('tr').find('.family-usia-input').val(age);
                } else {
                    $(this).closest('tr').find('.family-usia-input').val('');
                }
            });

            // Check if old value exists (validation error redirect)
            var oldJenis = "{{ old('jenis_mutasi') }}";
            if(oldJenis) {
                // Find the badge with this type and select it
                $('.mutasi-badge').each(function() {
                    if($(this).data('mutasi') == oldJenis) {
                        $(this).click();
                    }
                });
            }
        });
    </script>
@endpush