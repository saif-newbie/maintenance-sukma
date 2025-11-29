@extends('layouts.master')

@section('title', 'Detail Keluarga - ' . $kartuKeluarga->no_kk)

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-2 mb-sm-0 text-gray-800">Detail Keluarga</h1>
            <div class="d-flex flex-column flex-sm-row">
                <a href="{{ route('penduduk.family.edit', $kartuKeluarga->id) }}" class="btn btn-sm btn-warning shadow-sm mb-2 mb-sm-0 mr-sm-2">
                    <i class="fas fa-edit fa-sm text-white-50"></i> Edit Keluarga
                </a>
                <a href="{{ route('penduduk.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Card KK Info -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-id-card mr-2"></i>Informasi Kartu Keluarga
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="text-primary">Nomor KK</h5>
                        <p class="h4">{{ $kartuKeluarga->no_kk }}</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="text-primary">Dusun</h5>
                        <p class="h4">{{ $kartuKeluarga->dusun ?: '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="text-primary">Kategori Sejahtera</h5>
                        <p class="h4">{{ $kartuKeluarga->kategori_sejahtera ?: '-' }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <strong>Jenis Bangunan:</strong> {{ $kartuKeluarga->jenis_bangunan ?: '-' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Pemakaian Air:</strong> {{ $kartuKeluarga->pemakaian_air ?: '-' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Jenis Bantuan:</strong> {{ $kartuKeluarga->jenis_bantuan ?: '-' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Anggota Keluarga -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-info">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-users mr-2"></i>Anggota Keluarga ({{ $kartuKeluarga->penduduk->count() }} orang)
                </h6>
            </div>
            <div class="card-body">
                @if($kartuKeluarga->penduduk->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 50px;">No</th>
                                    <th style="min-width: 200px;">Nama Lengkap</th>
                                    <th style="min-width: 160px;">NIK</th>
                                    <th style="min-width: 120px;">Hubungan</th>
                                    <th style="min-width: 100px;">Jenis Kelamin</th>
                                    <th style="min-width: 120px;">Tempat Lahir</th>
                                    <th style="min-width: 100px;">Tanggal Lahir</th>
                                    <th style="min-width: 80px;">Usia</th>
                                    <th style="min-width: 120px;">Pekerjaan</th>
                                    <th style="min-width: 120px;">Pendidikan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $nomor = 1;
                                @endphp
                                @foreach($kartuKeluarga->penduduk as $anggota)
                                    <tr>
                                        <td class="text-center align-middle">{{ $nomor++ }}</td>
                                        <td class="align-middle">
                                            <strong>{{ $anggota->nama }}</strong>
                                            @if($anggota->hubungan_keluarga == 'Kepala Keluarga')
                                                <span class="badge badge-primary ml-1">KK</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $anggota->nik }}</td>
                                        <td class="align-middle">
                                            <span class="badge badge-info">{{ $anggota->hubungan_keluarga }}</span>
                                        </td>
                                        <td class="align-middle">
                                            {{ $anggota->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                            <i class="fas fa-{{ $anggota->jenis_kelamin == 'L' ? 'mars' : 'venus' }} text-{{ $anggota->jenis_kelamin == 'L' ? 'primary' : 'danger' }} ml-1"></i>
                                        </td>
                                        <td class="align-middle">{{ $anggota->tempat_lahir }}</td>
                                        <td class="align-middle">{{ \Carbon\Carbon::parse($anggota->tgl_lahir)->format('d/m/Y') }}</td>
                                        <td class="align-middle">
                                            <span class="badge badge-{{ $anggota->usia < 18 ? 'warning' : 'success' }}">
                                                {{ $anggota->usia }} tahun
                                            </span>
                                        </td>
                                        <td class="align-middle">{{ $anggota->pekerjaan }}</td>
                                        <td class="align-middle">{{ $anggota->tamatan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mt-4">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Anggota</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kartuKeluarga->penduduk->count() }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Laki-laki</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kartuKeluarga->penduduk->where('jenis_kelamin', 'L')->count() }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-mars fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Perempuan</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kartuKeluarga->penduduk->where('jenis_kelamin', 'P')->count() }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-venus fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Anak-anak</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kartuKeluarga->penduduk->where('usia', '<', 18)->count() }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-child fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-4x text-gray-300 mb-3"></i>
                        <h5 class="text-gray-500">Tidak ada anggota keluarga</h5>
                        <p class="text-gray-400">Data anggota keluarga belum tersedia.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection