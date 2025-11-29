@extends('layouts.master')
@section('title', 'Edit Data Keluarga')

@section('styles')
    <style>
        /* Hide number input spinners */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-edit"></i>
                            Edit Data Keluarga (Multiple Anggota Keluarga)
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Session Flash Messages -->
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Display Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <h5 class="alert-heading">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Terdapat kesalahan pada input data:
                                </h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('penduduk.family.update', $kartuKeluarga->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- HEADER SECTION - Data Kartu Keluarga (Single) -->
                            <!-- HEADER SECTION - Data Kartu Keluarga (Single) -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="mb-3">
                                        <i class="fas fa-home"></i> Data Kartu Keluarga
                                    </h5>
                                    <hr class="mb-4">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="nomor_kk" class="form-label text-uppercase fw-bold text-muted">NOMOR KARTU KELUARGA</label>
                                    <input type="text" class="form-control @error('nomor_kk') is-invalid @enderror" id="nomor_kk"
                                        name="nomor_kk" value="{{ old('nomor_kk', $kartuKeluarga->no_kk) }}" required placeholder="16 digit Nomor KK"
                                        inputmode="numeric"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);">
                                    @error('nomor_kk')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3 d-flex flex-column">
                                    <label for="dusun" class="form-label text-uppercase fw-bold text-muted">DUSUN</label>
                                    <select class="form-select @error('dusun') is-invalid @enderror" id="dusun" name="dusun" required style="width: 50%;">
                                        <option value="">Pilih Dusun</option>
                                        <option value="Dusun 1" {{ old('dusun', $kartuKeluarga->dusun) == 'Dusun 1' ? 'selected' : '' }}>Dusun 1</option>
                                        <option value="Dusun 2" {{ old('dusun', $kartuKeluarga->dusun) == 'Dusun 2' ? 'selected' : '' }}>Dusun 2</option>
                                        <option value="Dusun 3" {{ old('dusun', $kartuKeluarga->dusun) == 'Dusun 3' ? 'selected' : '' }}>Dusun 3</option>
                                    </select>
                                    @error('dusun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3 d-flex flex-column">
                                    <label for="kategori_sejahtera" class="form-label text-uppercase fw-bold text-muted">KATEGORI SEJAHTERA</label>
                                    <select class="form-select @error('kategori_sejahtera') is-invalid @enderror"
                                        id="kategori_sejahtera" name="kategori_sejahtera" style="width: 50%;">
                                        <option value="">Pilih Kategori Sejahtera</option>
                                        <option value="KS1" {{ old('kategori_sejahtera', $kartuKeluarga->kategori_sejahtera) == 'KS1' ? 'selected' : '' }}>KS1</option>
                                        <option value="KS2" {{ old('kategori_sejahtera', $kartuKeluarga->kategori_sejahtera) == 'KS2' ? 'selected' : '' }}>KS2</option>
                                        <option value="KS3" {{ old('kategori_sejahtera', $kartuKeluarga->kategori_sejahtera) == 'KS3' ? 'selected' : '' }}>KS3</option>
                                    </select>
                                    @error('kategori_sejahtera')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="jenis_bangunan" class="form-label text-uppercase fw-bold text-muted">JENIS BANGUNAN</label>
                                    <input type="text" class="form-control @error('jenis_bangunan') is-invalid @enderror"
                                        id="jenis_bangunan" name="jenis_bangunan" value="{{ old('jenis_bangunan', $kartuKeluarga->jenis_bangunan) }}" placeholder="Contoh: Permanen">
                                    @error('jenis_bangunan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="pemakaian_air" class="form-label text-uppercase fw-bold text-muted">PEMAKAIAN AIR</label>
                                    <input type="text" class="form-control @error('pemakaian_air') is-invalid @enderror"
                                        id="pemakaian_air" name="pemakaian_air" value="{{ old('pemakaian_air', $kartuKeluarga->pemakaian_air) }}" placeholder="Contoh: PDAM">
                                    @error('pemakaian_air')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="jenis_bantuan" class="form-label text-uppercase fw-bold text-muted">JENIS BANTUAN</label>
                                    <input type="text" class="form-control @error('jenis_bantuan') is-invalid @enderror"
                                        id="jenis_bantuan" name="jenis_bantuan" value="{{ old('jenis_bantuan', $kartuKeluarga->jenis_bantuan) }}" placeholder="Contoh: BPNT">
                                    @error('jenis_bantuan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- REPEATER SECTION - Data Anggota Keluarga (Multiple) -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">
                                            <i class="fas fa-users"></i> Data Anggota Keluarga
                                        </h5>
                                        <button type="button" class="btn btn-success btn-sm" id="tambahAnggota">
                                            <i class="fas fa-plus-circle"></i> Tambah Anggota
                                        </button>
                                    </div>
                                    <hr class="mb-4">
                                </div>

                                <div class="col-12">
                                    <div id="anggotaContainer">
                                        @foreach($kartuKeluarga->penduduk as $index => $anggota)
                                            <div class="card mb-3 anggota-row shadow-sm" data-index="{{ $index }}">
                                                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                                                    <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-user"></i> Anggota {{ $index + 1 }}</h6>
                                                    <button type="button" class="btn btn-danger btn-sm hapus-anggota" title="Hapus anggota ini">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <input type="hidden" name="anggota[{{ $index }}][id]" value="{{ $anggota->id }}">
                                                    <div class="row">
                                                        <!-- Row 1: NIK, Nama, Gender, Hubungan -->
                                                        <div class="col-md-3 mb-3">
                                                            <label class="form-label fw-bold small text-uppercase">NIK <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control nik-input @error("anggota.$index.nik") is-invalid @enderror"
                                                                name="anggota[{{ $index }}][nik]" value="{{ old("anggota.$index.nik", $anggota->nik) }}" required placeholder="16 digit NIK"
                                                                inputmode="numeric"
                                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);">
                                                            @error("anggota.$index.nik")
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label class="form-label fw-bold small text-uppercase">Nama Lengkap <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control nama-input @error("anggota.$index.nama") is-invalid @enderror"
                                                                name="anggota[{{ $index }}][nama]" value="{{ old("anggota.$index.nama", $anggota->nama) }}" required placeholder="Nama Lengkap">
                                                            @error("anggota.$index.nama")
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label class="form-label fw-bold small text-uppercase">Gender</label>
                                                            <select class="form-select gender-input @error("anggota.$index.jenis_kelamin") is-invalid @enderror"
                                                                name="anggota[{{ $index }}][jenis_kelamin]" required style="width: 75%;">
                                                                <option value="">Pilih Gender</option>
                                                                <option value="Laki-laki" {{ old("anggota.$index.jenis_kelamin", $anggota->jenis_kelamin) == 'Laki-laki' || old("anggota.$index.jenis_kelamin", $anggota->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                                <option value="Perempuan" {{ old("anggota.$index.jenis_kelamin", $anggota->jenis_kelamin) == 'Perempuan' || old("anggota.$index.jenis_kelamin", $anggota->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                            </select>
                                                            @error("anggota.$index.jenis_kelamin")
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label class="form-label fw-bold small text-uppercase">Hubungan</label>
                                                            <select class="form-select hubungan-input @error("anggota.$index.hubungan_keluarga") is-invalid @enderror"
                                                                name="anggota[{{ $index }}][hubungan_keluarga]" required style="width: 75%;">
                                                                <option value="">Pilih Hubungan</option>
                                                                <option value="Kepala Keluarga" {{ old("anggota.$index.hubungan_keluarga", $anggota->hubungan_keluarga) == 'Kepala Keluarga' ? 'selected' : '' }}>Kepala Keluarga</option>
                                                                <option value="Istri" {{ old("anggota.$index.hubungan_keluarga", $anggota->hubungan_keluarga) == 'Istri' ? 'selected' : '' }}>Istri</option>
                                                                <option value="Anak" {{ old("anggota.$index.hubungan_keluarga", $anggota->hubungan_keluarga) == 'Anak' ? 'selected' : '' }}>Anak</option>
                                                                <option value="Menantu" {{ old("anggota.$index.hubungan_keluarga", $anggota->hubungan_keluarga) == 'Menantu' ? 'selected' : '' }}>Menantu</option>
                                                                <option value="Cucu" {{ old("anggota.$index.hubungan_keluarga", $anggota->hubungan_keluarga) == 'Cucu' ? 'selected' : '' }}>Cucu</option>
                                                                <option value="Lainnya" {{ old("anggota.$index.hubungan_keluarga", $anggota->hubungan_keluarga) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                                            </select>
                                                            @error("anggota.$index.hubungan_keluarga")
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Row 2: Tempat Lahir, Tgl Lahir, Usia, Pekerjaan, Tamatan -->
                                                        <div class="col-md-3 mb-3">
                                                            <label class="form-label fw-bold small text-uppercase">Tempat Lahir</label>
                                                            <input type="text" class="form-control tempat-lahir-input @error("anggota.$index.tempat_lahir") is-invalid @enderror"
                                                                name="anggota[{{ $index }}][tempat_lahir]" value="{{ old("anggota.$index.tempat_lahir", $anggota->tempat_lahir) }}" required placeholder="Contoh: Jakarta">
                                                            @error("anggota.$index.tempat_lahir")
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label class="form-label fw-bold small text-uppercase">Tanggal Lahir</label>
                                                            <input type="date" class="form-control tgl-lahir-input @error("anggota.$index.tgl_lahir") is-invalid @enderror"
                                                                name="anggota[{{ $index }}][tgl_lahir]" value="{{ old("anggota.$index.tgl_lahir", $anggota->tgl_lahir) }}" required>
                                                            @error("anggota.$index.tgl_lahir")
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-1 mb-3">
                                                            <label class="form-label fw-bold small text-uppercase">Usia</label>
                                                            <input type="text" class="form-control usia-input bg-light"
                                                                name="anggota[{{ $index }}][usia]" value="{{ old("anggota.$index.usia", $anggota->usia) }}" readonly placeholder="Auto">
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label class="form-label fw-bold small text-uppercase">Pekerjaan</label>
                                                            <input type="text" class="form-control pekerjaan-input @error("anggota.$index.pekerjaan") is-invalid @enderror"
                                                                name="anggota[{{ $index }}][pekerjaan]" value="{{ old("anggota.$index.pekerjaan", $anggota->pekerjaan) }}" required placeholder="Contoh: Wiraswasta">
                                                            @error("anggota.$index.pekerjaan")
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label class="form-label fw-bold small text-uppercase">Tamatan</label>
                                                            <select class="form-select tamatan-input @error("anggota.$index.tamatan") is-invalid @enderror"
                                                                name="anggota[{{ $index }}][tamatan]" required>
                                                                <option value="">Pilih Tamatan</option>
                                                                <option value="SD" {{ old("anggota.$index.tamatan", $anggota->tamatan) == 'SD' ? 'selected' : '' }}>SD</option>
                                                                <option value="SMP" {{ old("anggota.$index.tamatan", $anggota->tamatan) == 'SMP' ? 'selected' : '' }}>SMP</option>
                                                                <option value="SMA/SMK" {{ old("anggota.$index.tamatan", $anggota->tamatan) == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                                                <option value="D1/D2/D3" {{ old("anggota.$index.tamatan", $anggota->tamatan) == 'D1/D2/D3' ? 'selected' : '' }}>D1/D2/D3</option>
                                                                <option value="S1" {{ old("anggota.$index.tamatan", $anggota->tamatan) == 'S1' ? 'selected' : '' }}>S1</option>
                                                                <option value="S2" {{ old("anggota.$index.tamatan", $anggota->tamatan) == 'S2' ? 'selected' : '' }}>S2</option>
                                                                <option value="S3" {{ old("anggota.$index.tamatan", $anggota->tamatan) == 'S3' ? 'selected' : '' }}>S3</option>
                                                                <option value="Tidak Tamat SD" {{ old("anggota.$index.tamatan", $anggota->tamatan) == 'Tidak Tamat SD' ? 'selected' : '' }}>Tidak Tamat SD</option>
                                                                <option value="Tidak Sekolah" {{ old("anggota.$index.tamatan", $anggota->tamatan) == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                                                            </select>
                                                            @error("anggota.$index.tamatan")
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- BUTTONS -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('penduduk.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left"></i> Kembali
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save"></i> Update Semua Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let anggotaIndex = {{ $kartuKeluarga->penduduk->count() }};

            // Fungsi untuk menghitung usia
            function hitungUsia(tanggalLahir) {
                const today = new Date();
                const birthDate = new Date(tanggalLahir);

                let usia = today.getFullYear() - birthDate.getFullYear();
                const bulanSekarang = today.getMonth();
                const tanggalSekarang = today.getDate();
                const bulanLahir = birthDate.getMonth();
                const tanggalLahirHari = birthDate.getDate();

                // Jika bulan lahir belum lewat atau sama tapi tanggal lahir belum lewat
                if (bulanSekarang < bulanLahir ||
                    (bulanSekarang === bulanLahir && tanggalSekarang < tanggalLahirHari)) {
                    usia--;
                }

                return usia;
            }

            // Event Delegation untuk perhitungan usia (handle dinamis added elements)
            $(document).on('change', '.tgl-lahir-input', function() {
                const tanggalLahir = $(this).val();
                const usiaInput = $(this).closest('tr, .card-body').find('.usia-input');

                if (tanggalLahir) {
                    const usia = hitungUsia(tanggalLahir);
                    usiaInput.val(usia);
                } else {
                    usiaInput.val('');
                }
            });

            // Fungsi untuk menambah anggota
            $('#tambahAnggota').click(function() {
                console.log('Tambah anggota clicked - Current index:', anggotaIndex);

                const newRow = `
                    <div class="card mb-3 anggota-row shadow-sm" data-index="${anggotaIndex}">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light">
                            <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-user"></i> Anggota ${anggotaIndex + 1}</h6>
                            <button type="button" class="btn btn-danger btn-sm hapus-anggota" title="Hapus anggota ini">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Row 1 -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold small text-uppercase">NIK <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control nik-input" name="anggota[${anggotaIndex}][nik]" required placeholder="16 digit NIK" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold small text-uppercase">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control nama-input" name="anggota[${anggotaIndex}][nama]" required placeholder="Nama Lengkap">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold small text-uppercase">Gender</label>
                                    <select class="form-select gender-input" name="anggota[${anggotaIndex}][jenis_kelamin]" required style="width: 75%;">
                                        <option value="">Pilih Gender</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold small text-uppercase">Hubungan</label>
                                    <select class="form-select hubungan-input" name="anggota[${anggotaIndex}][hubungan_keluarga]" required style="width: 75%;">
                                        <option value="">Pilih Hubungan</option>
                                        <option value="Kepala Keluarga">Kepala Keluarga</option>
                                        <option value="Istri">Istri</option>
                                        <option value="Anak">Anak</option>
                                        <option value="Menantu">Menantu</option>
                                        <option value="Cucu">Cucu</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                
                                <!-- Row 2 -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold small text-uppercase">Tempat Lahir</label>
                                    <input type="text" class="form-control tempat-lahir-input" name="anggota[${anggotaIndex}][tempat_lahir]" required placeholder="Contoh: Jakarta">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold small text-uppercase">Tanggal Lahir</label>
                                    <input type="date" class="form-control tgl-lahir-input" name="anggota[${anggotaIndex}][tgl_lahir]" required>
                                </div>
                                <div class="col-md-1 mb-3">
                                    <label class="form-label fw-bold small text-uppercase">Usia</label>
                                    <input type="text" class="form-control usia-input bg-light" name="anggota[${anggotaIndex}][usia]" readonly placeholder="Auto">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold small text-uppercase">Pekerjaan</label>
                                    <input type="text" class="form-control pekerjaan-input" name="anggota[${anggotaIndex}][pekerjaan]" required placeholder="Contoh: Wiraswasta">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label fw-bold small text-uppercase">Tamatan</label>
                                    <select class="form-select tamatan-input" name="anggota[${anggotaIndex}][tamatan]" required>
                                        <option value="">Pilih Tamatan</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA/SMK">SMA/SMK</option>
                                        <option value="D1/D2/D3">D1/D2/D3</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                        <option value="Tidak Tamat SD">Tidak Tamat SD</option>
                                        <option value="Tidak Sekolah">Tidak Sekolah</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('#anggotaContainer').append(newRow);
                console.log('New row added');
                updateDeleteButtons();
                updateRowNumbers();
                anggotaIndex++;
            });

            // Event Delegation untuk menghapus anggota
            $(document).on('click', '.hapus-anggota', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const $button = $(this);
                const $row = $button.closest('.anggota-row');
                const rowCount = $('#anggotaContainer .anggota-row').length;

                console.log('Delete clicked - Row count:', rowCount);

                // Pastikan minimal 1 baris tersisa
                if (rowCount > 1) {
                    if(confirm('Apakah Anda yakin ingin menghapus anggota ini?')) {
                         $row.fadeOut(300, function() {
                            $(this).remove();
                            updateDeleteButtons();
                            updateRowNumbers();
                            console.log('Row deleted successfully');
                        });
                    }
                } else {
                    alert('Minimal harus ada 1 anggota keluarga!');
                }
            });

            // Fungsi untuk update tombol hapus (enable/disable)
            function updateDeleteButtons() {
                const rowCount = $('#anggotaContainer .anggota-row').length;
                const shouldBeDisabled = rowCount <= 1;

                $('#anggotaContainer .hapus-anggota').each(function() {
                    const $btn = $(this);
                    $btn.prop('disabled', shouldBeDisabled);

                    if (shouldBeDisabled) {
                        $btn.removeClass('btn-danger').addClass('btn-secondary');
                        $btn.attr('title', 'Tidak bisa menghapus anggota terakhir');
                    } else {
                        $btn.removeClass('btn-secondary').addClass('btn-danger');
                        $btn.attr('title', 'Hapus anggota ini');
                    }
                });
            }

            // Fungsi untuk update nomor urut
            function updateRowNumbers() {
                $('#anggotaContainer .anggota-row').each(function(index) {
                    // Update header text
                    $(this).find('.card-header h6').html(`<i class="fas fa-user"></i> Anggota ${index + 1}`);
                    $(this).attr('data-index', index);
                });
            }

            // Hitung usia untuk input yang sudah ada saat page load
            $('.tgl-lahir-input').each(function() {
                const tanggalLahir = $(this).val();
                if (tanggalLahir) {
                    const usia = hitungUsia(tanggalLahir);
                    $(this).closest('.card-body').find('.usia-input').val(usia);
                }
            });

            // Inisialisasi tombol hapus saat page load
            updateDeleteButtons();

            // Form submission validation
            $('form').submit(function(e) {
                // Check if there are empty required fields
                let isValid = true;
                let errorMessage = '';

                // Validate KK number
                const nomorKK = $('#nomor_kk').val().trim();
                if (!nomorKK) {
                    isValid = false;
                    errorMessage = 'Nomor KK wajib diisi!';
                } else if (nomorKK.length < 1) {
                    isValid = false;
                    errorMessage = 'Nomor KK tidak valid!';
                }

                // Validate anggota rows
                const anggotaRows = $('.anggota-row');
                if (anggotaRows.length === 0) {
                    isValid = false;
                    errorMessage = 'Minimal harus ada 1 anggota keluarga!';
                }

                // Check each anggota for required fields
                anggotaRows.each(function(index) {
                    const row = $(this);
                    const nik = row.find('input[name^="anggota"][name$="[nik]"]').val().trim();
                    const nama = row.find('input[name^="anggota"][name$="[nama]"]').val().trim();
                    
                    if (!nik || nik.length !== 16) {
                        isValid = false;
                        errorMessage = `NIK anggota ${index + 1} harus 16 digit!`;
                        return false; 
                    }

                    if (!nama) {
                        isValid = false;
                        errorMessage = `Nama anggota ${index + 1} wajib diisi!`;
                        return false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Validation Error: ' + errorMessage);
                    return false;
                }

                // Disable submit button to prevent double submission
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Menyimpan...');

                return true;
            });
        });
    </script>
@endsection