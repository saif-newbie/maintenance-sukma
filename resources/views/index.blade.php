@extends('layouts.master')
@section('title', 'Data Penduduk')

@section('content')

    <!-- Page Heading -->

    <h1 class="h3 mb-4 text-gray-800">Tabel Penduduk</h1>
    <div class="mb-3    ">
        <a href="{{ route('penduduk.create') }}">
            <button class="btn btn-danger">Tambah Data</button>
        </a>
    </div>

    <!-- Statistics Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Keluarga</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $groupedPenduduk->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Penduduk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $groupedPenduduk->sum(function ($group) {return $group->count();}) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rata-rata Anggota/KK</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{-- Safe calculation: Prevent division by zero --}}
                                @php
                                    $totalPenduduk = $groupedPenduduk->sum(function ($group) {
                                        return $group->count();
                                    });
                                    $totalKK = $groupedPenduduk->count();
                                    $rataRataAnggota = $totalKK > 0 ? round($totalPenduduk / $totalKK, 1) : 0;
                                @endphp
                                {{ $rataRataAnggota }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Penduduk Berdasarkan Kartu Keluarga</h6>
        </div>
        <div class="card-body">
            @if ($groupedPenduduk->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="pendudukTable">
                        <thead class="table-primary">
                            <tr>
                                <th style="width: 60px;" class="text-center align-middle">No</th>
                                <th style="width: 120px;" class="text-center align-middle">No. KK</th>
                                <th class="align-middle">Nama</th>
                                <th class="align-middle">NIK</th>
                                <th class="align-middle">Peran Keluarga</th>
                                <th class="align-middle">Jenis Kelamin</th>
                                <th class="align-middle">Tempat Lahir</th>
                                <th class="align-middle">Tanggal Lahir</th>
                                <th style="width: 120px;" class="text-center align-middle">Aksi Keluarga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $nomor = $no;
                            @endphp

                            @foreach ($groupedPenduduk as $noKk => $anggotaKeluarga)
                                @php
                                    $jumlahAnggota = $anggotaKeluarga->count();
                                    $isFirstRow = true;
                                    $kartuKeluargaId = $anggotaKeluarga->first()->kartu_keluarga_id;
                                @endphp

                                @foreach ($anggotaKeluarga as $penduduk)
                                    <tr>
                                        @if ($isFirstRow)
                                            <td rowspan="{{ $jumlahAnggota }}"
                                                class="text-center align-middle valign-middle"
                                                style="vertical-align: middle;">
                                                {{ $nomor }}
                                            </td>
                                            <td rowspan="{{ $jumlahAnggota }}"
                                                class="text-center align-middle valign-middle"
                                                style="vertical-align: middle;">
                                                <strong>{{ $penduduk->no_kk }}</strong>
                                            </td>
                                        @endif

                                        <td class="align-middle">
                                            <div>
                                                {{ $penduduk->nama }}
                                                @if ($penduduk->peran_keluarga == 'Kepala Keluarga')
                                                    <span class="badge badge-primary ml-2">KK</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-middle">{{ $penduduk->nik }}</td>
                                        <td class="align-middle">{{ $penduduk->peran_keluarga }}</td>
                                        <td class="align-middle">
                                            {{ $penduduk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </td>
                                        <td class="align-middle">{{ $penduduk->tempat_lahir }}</td>
                                        <td class="align-middle">
                                            {{ \Carbon\Carbon::parse($penduduk->tgl_lahir)->format('d/m/Y') }}</td>
                                        @if ($isFirstRow)
                                            <td rowspan="{{ $jumlahAnggota }}"
                                                class="text-center align-middle valign-middle"
                                                style="vertical-align: middle;">
                                                <div class="btn-group-vertical" role="group">
                                                    <a href="{{ route('penduduk.family.show', $kartuKeluargaId) }}"
                                                        class="btn btn-sm btn-info mb-1" title="Detail Keluarga">
                                                        <i class="fas fa-users"></i> Detail
                                                    </a>
                                                    <a href="{{ route('penduduk.family.edit', $kartuKeluargaId) }}"
                                                        class="btn btn-sm btn-warning mb-1" title="Edit Keluarga">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger mb-1"
                                                        title="Hapus Keluarga"
                                                        onclick="hapusKeluarga({{ $kartuKeluargaId }})">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                    @php
                                        $isFirstRow = false;
                                    @endphp
                                @endforeach

                                @php
                                    $nomor++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-500">Belum ada data penduduk</h5>
                    <p class="text-gray-400">Tambahkan data penduduk untuk melihat informasi di sini.</p>
                    <a href="{{ route('penduduk.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Handle form submission with better confirmation
            $('form[data-confirm]').on('submit', function(e) {
                var confirmMessage = $(this).data('confirm');
                if (!confirm(confirmMessage)) {
                    e.preventDefault();
                    return false;
                }
            });
        });

        // Function untuk menghapus keluarga
        function hapusKeluarga(kartuKeluargaId) {
            if (confirm(
                    '⚠️ PERINGATAN: Apakah Anda yakin ingin menghapus seluruh data keluarga ini? Data akan dihapus PERMANEN dan tidak dapat dikembalikan!'
                )) {
                // Buat form dinamis untuk submit DELETE request
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '/penduduk/family/' + kartuKeluargaId;
                form.style.display = 'none';

                // Tambahkan CSRF token
                var csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                // Tambahkan method spoofing untuk DELETE
                var methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Submit form
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection
