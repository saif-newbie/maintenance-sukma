@extends('layouts.master')
@section('title', 'Data Penduduk')

@section('content')

    <!-- Page Heading -->

    <h1 class="h3 mb-4 text-gray-800">Tabel Penduduk</h1>
    
    <!-- Action Bar: Add Data & Search/Filter -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('penduduk.create') }}">
            <button class="btn btn-danger">Tambah Data</button>
        </a>

        <div class="d-flex">
            <!-- Filter Dusun -->
            <select id="filterDusun" class="form-control mr-2" style="width: 200px;">
                <option value="">Semua Dusun</option>
                @if(isset($availableDusuns))
                    @foreach($availableDusuns as $dusun)
                        <option value="{{ $dusun }}">{{ $dusun }}</option>
                    @endforeach
                @endif
            </select>

            <!-- Search Box -->
            <div class="input-group" style="width: 300px;">
                <input type="text" id="searchPenduduk" class="form-control bg-light border-0 small" 
                    placeholder="Cari Nama, NIK, atau No KK..." aria-label="Search" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </div>
        </div>
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
                        <tbody id="pendudukTableBody">
                            @include('partials.penduduk_table')
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

            // Real-time Search and Filter
            let searchTimeout;

            function fetchPenduduk() {
                const searchQuery = $('#searchPenduduk').val();
                const dusunFilter = $('#filterDusun').val();

                $.ajax({
                    url: "{{ route('penduduk.index') }}",
                    type: "GET",
                    data: {
                        search: searchQuery,
                        dusun: dusunFilter
                    },
                    success: function(response) {
                        $('#pendudukTableBody').html(response);
                    },
                    error: function(xhr) {
                        console.error("Error fetching data:", xhr);
                    }
                });
            }

            function updateDusunFilter() {
                $.ajax({
                    url: "{{ route('penduduk.available-dusuns') }}",
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
                fetchPenduduk();
            }

            // Event listeners
            $('#searchPenduduk').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(fetchPenduduk, 300); // Debounce 300ms
            });

            $('#filterDusun').on('change', function() {
                fetchPenduduk();
            });

            // Auto-refresh after successful mutation creation from other pages
            // Check for success message from session that might indicate new data was added
            @if(session()->has('success') && (str_contains(session('success'), 'DATANG') || str_contains(session('success'), 'LAHIR') || str_contains(session('success'), 'ditambahkan')))
                refreshDataAndFilter();
            @endif

            // Also refresh dusun options on page load to ensure we have latest data
            updateDusunFilter();
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
